package net.mdcz.qrpaywidget

import android.graphics.Bitmap
import android.graphics.Color
import com.google.zxing.BarcodeFormat
import com.google.zxing.EncodeHintType
import com.google.zxing.qrcode.QRCodeWriter
import com.google.zxing.qrcode.decoder.ErrorCorrectionLevel

object QRCodeHelper {

    fun generateQR(content: String, size: Int = 512): Bitmap {
        val hints = HashMap<EncodeHintType, Any>()
        hints[EncodeHintType.ERROR_CORRECTION] = ErrorCorrectionLevel.M
        hints[EncodeHintType.CHARACTER_SET] = "UTF-8"
        hints[EncodeHintType.MARGIN] = 2

        val writer = QRCodeWriter()
        val matrix = writer.encode(content, BarcodeFormat.QR_CODE, size, size, hints)

        val bitmap = Bitmap.createBitmap(size, size, Bitmap.Config.ARGB_8888)
        for (x in 0 until size) {
            for (y in 0 until size) {
                bitmap.setPixel(x, y, if (matrix[x, y]) Color.BLACK else Color.WHITE)
            }
        }
        return bitmap
    }

    fun buildSPD(iban: String, amount: String, message: String = ""): String {
        val sb = StringBuilder("SPD*1.0*ACC:$iban*AM:$amount*CC:CZK")
        if (message.isNotEmpty()) sb.append("*MSG:$message")
        return sb.toString()
    }
}
