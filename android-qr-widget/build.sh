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

echo "[6/6] Podepisování APK..."
KEYSTORE="$OUT/debug.keystore"
if [ ! -f "$KEYSTORE" ]; then
    keytool -genkey -v \
        -keystore "$KEYSTORE" \
        -alias android \
        -keyalg RSA \
        -keysize 2048 \
        -validity 10000 \
        -storepass android123 \
        -keypass android123 \
        -dname "CN=QR Platba,O=MDCZnet,C=CZ" 2>/dev/null
fi

apksigner sign \
    --ks "$KEYSTORE" \
    --ks-pass pass:android123 \
    --key-pass pass:android123 \
    --ks-key-alias android \
    --out "$APK_NAME" \
    "$OUT/aligned.apk"

echo ""
echo "=== HOTOVO ==="
echo "APK: $(pwd)/$APK_NAME"
ls -lh "$APK_NAME"
