#!/bin/bash
set -e

ANDROID_JAR="/usr/lib/android-sdk/platforms/android-23/android.jar"
KOTLIN_STDLIB="/usr/share/kotlin/kotlinc/lib/kotlin-stdlib.jar"
KOTLIN_STDLIB_JDK8="/usr/share/kotlin/kotlinc/lib/kotlin-stdlib-jdk8.jar"
ZXING_JAR="libs/zxing-core.jar"
PACKAGE="net.mdcz.qrpaywidget"
PACKAGE_PATH="net/mdcz/qrpaywidget"
OUT="build"
APK_NAME="qr-platba-widget.apk"
AAB_NAME="qr-platba-widget.aab"
RELEASE_KEYSTORE="release.keystore"
BUNDLETOOL="/usr/local/bin/bundletool.jar"

echo "=== QR Platba Widget Build ==="

# Clean
rm -rf "$OUT"
mkdir -p "$OUT/gen/$PACKAGE_PATH" "$OUT/classes" "$OUT/dex"

# --- Keystore ---
if [ ! -f "$RELEASE_KEYSTORE" ]; then
    echo "  Generuji release keystore..."
    keytool -genkey -v \
        -keystore "$RELEASE_KEYSTORE" \
        -alias qrplatba \
        -keyalg RSA \
        -keysize 2048 \
        -validity 10000 \
        -storepass "$(cat .keystore_pass 2>/dev/null || echo 'qrplatba2024')" \
        -keypass "$(cat .keystore_pass 2>/dev/null || echo 'qrplatba2024')" \
        -dname "CN=QR Platba,O=MDCZnet,L=Prague,ST=Prague,C=CZ" 2>/dev/null
fi
KSPASS="$(cat .keystore_pass 2>/dev/null || echo 'qrplatba2024')"

# ============================================================
# FÁZE 1 – Kompilace zdrojového kódu (sdílená pro APK i AAB)
# ============================================================

echo "[1/8] Kompilace zdrojů – R.java (aapt)..."
aapt package -f -m \
    -J "$OUT/gen/" \
    -S res/ \
    -M AndroidManifest.xml \
    -I "$ANDROID_JAR" \
    -F "$OUT/resources.ap_"

echo "[2/8] Kompilace R.java (javac)..."
javac -source 1.8 -target 1.8 \
    -bootclasspath "$ANDROID_JAR" \
    -classpath "$ANDROID_JAR" \
    -d "$OUT/classes" \
    "$OUT/gen/$PACKAGE_PATH/R.java"

echo "[3/8] Kompilace Kotlin zdrojů (kotlinc)..."
kotlinc \
    -jvm-target 1.8 \
    -classpath "$ANDROID_JAR:$ZXING_JAR:$OUT/classes" \
    src/$PACKAGE_PATH/*.kt \
    -d "$OUT/classes" 2>&1

echo "[4/8] Konverze do DEX (dx)..."
dalvik-exchange --dex \
    --output="$OUT/dex/classes.dex" \
    "$OUT/classes" \
    "$ZXING_JAR" \
    "$KOTLIN_STDLIB" \
    "$KOTLIN_STDLIB_JDK8"

# ============================================================
# FÁZE 2 – Sestavení APK
# ============================================================

echo "[5/8] Sestavení APK..."
cp "$OUT/resources.ap_" "$OUT/unsigned.apk"
cd "$OUT/dex" && zip -0 "../unsigned.apk" classes.dex && cd ../..
zipalign -f 4 "$OUT/unsigned.apk" "$OUT/aligned.apk"

echo "[6/8] Podepisování APK..."
apksigner sign \
    --ks "$RELEASE_KEYSTORE" \
    --ks-pass "pass:$KSPASS" \
    --key-pass "pass:$KSPASS" \
    --ks-key-alias qrplatba \
    --out "$APK_NAME" \
    "$OUT/aligned.apk"

# ============================================================
# FÁZE 3 – Sestavení AAB (Android App Bundle)
# ============================================================

echo "[7/8] Sestavení AAB (Android App Bundle)..."

# Kompilace zdrojů do proto formátu (aapt2)
mkdir -p "$OUT/aapt2"
aapt2 compile --dir res/ -o "$OUT/aapt2/compiled.zip"

# Linkování v proto formátu
aapt2 link --proto-format \
    -o "$OUT/aapt2/linked.zip" \
    -I "$ANDROID_JAR" \
    --manifest AndroidManifest.xml \
    "$OUT/aapt2/compiled.zip"

# Sestavení struktury base modulu pro bundletool
mkdir -p "$OUT/module/manifest" "$OUT/module/dex"
unzip -q "$OUT/aapt2/linked.zip" -d "$OUT/aapt2/linked_extracted/"
cp "$OUT/aapt2/linked_extracted/AndroidManifest.xml" "$OUT/module/manifest/"
if [ -d "$OUT/aapt2/linked_extracted/res" ]; then
    cp -r "$OUT/aapt2/linked_extracted/res" "$OUT/module/"
fi
cp "$OUT/aapt2/linked_extracted/resources.pb" "$OUT/module/"
cp "$OUT/dex/classes.dex" "$OUT/module/dex/"

# Zabalení base modulu
cd "$OUT/module" && zip -r "../base.zip" . && cd ../..

# Sestavení AAB
java -jar "$BUNDLETOOL" build-bundle \
    --modules="$OUT/base.zip" \
    --output="$AAB_NAME"

echo "[8/8] Podepisování AAB..."
jarsigner -sigalg SHA256withRSA -digestalg SHA-256 \
    -keystore "$RELEASE_KEYSTORE" \
    -storepass "$KSPASS" \
    -keypass "$KSPASS" \
    "$AAB_NAME" qrplatba

echo ""
echo "=== HOTOVO ==="
echo "APK: $(pwd)/$APK_NAME  $(ls -lh $APK_NAME | awk '{print $5}')"
echo "AAB: $(pwd)/$AAB_NAME  $(ls -lh $AAB_NAME | awk '{print $5}')"
