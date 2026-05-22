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
RELEASE_KEYSTORE="release.keystore"

echo "=== QR Platba Widget Build ==="

# Clean
rm -rf "$OUT"
mkdir -p "$OUT/gen/$PACKAGE_PATH" "$OUT/classes" "$OUT/dex"

echo "[1/6] Kompilace zdrojů (aapt)..."
aapt package -f -m \
    -J "$OUT/gen/" \
    -S res/ \
    -M AndroidManifest.xml \
    -I "$ANDROID_JAR" \
    -F "$OUT/resources.ap_"

echo "[2/6] Kompilace R.java (javac)..."
javac -source 1.8 -target 1.8 \
    -bootclasspath "$ANDROID_JAR" \
    -classpath "$ANDROID_JAR" \
    -d "$OUT/classes" \
    "$OUT/gen/$PACKAGE_PATH/R.java"

echo "[3/6] Kompilace Kotlin zdrojů (kotlinc)..."
kotlinc \
    -jvm-target 1.8 \
    -classpath "$ANDROID_JAR:$ZXING_JAR:$OUT/classes" \
    src/$PACKAGE_PATH/*.kt \
    -d "$OUT/classes" 2>&1

echo "[4/6] Konverze do DEX (dx)..."
dalvik-exchange --dex \
    --output="$OUT/dex/classes.dex" \
    "$OUT/classes" \
    "$ZXING_JAR" \
    "$KOTLIN_STDLIB" \
    "$KOTLIN_STDLIB_JDK8"

echo "[5/6] Sestavení APK..."
cp "$OUT/resources.ap_" "$OUT/unsigned.apk"
cd "$OUT/dex" && zip -0 "../unsigned.apk" classes.dex && cd ../..

zipalign -f 4 "$OUT/unsigned.apk" "$OUT/aligned.apk"

echo "[6/6] Podepisování APK (release key)..."
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

apksigner sign \
    --ks "$RELEASE_KEYSTORE" \
    --ks-pass "pass:$KSPASS" \
    --key-pass "pass:$KSPASS" \
    --ks-key-alias qrplatba \
    --out "$APK_NAME" \
    "$OUT/aligned.apk"

echo ""
echo "=== HOTOVO ==="
echo "APK: $(pwd)/$APK_NAME"
ls -lh "$APK_NAME"
echo ""
echo "Play Store 512x512 ikona: $(ls -lh /tmp/icon_512.png 2>/dev/null || echo 'neni k dispozici - spustte znovu')"
