package net.mdcz.qrpaywidget

import android.app.Activity
import android.content.Intent
import android.graphics.Bitmap
import android.net.Uri
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import java.io.File
import java.io.FileOutputStream

class ShowQRActivity : Activity() {

    private var qrBitmap: Bitmap? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_show_qr)

        val amount = intent.getStringExtra("amount") ?: return
        val account = intent.getStringExtra("account") ?: return

        val iban = IBANConverter.czechToIBAN(account) ?: account
        val spdString = QRCodeHelper.buildSPD(iban, amount)

        qrBitmap = QRCodeHelper.generateQR(spdString, 512)

        val qrImage = findViewById(R.id.qr_image) as ImageView
        val amountText = findViewById(R.id.amount_text) as TextView
        val accountText = findViewById(R.id.account_text) as TextView
        val shareButton = findViewById(R.id.share_button) as Button
        val closeButton = findViewById(R.id.close_button) as Button

        qrImage.setImageBitmap(qrBitmap)

        val amountDouble = amount.toDoubleOrNull() ?: 0.0
        amountText.text = if (amountDouble == amountDouble.toLong().toDouble()) {
            "${amountDouble.toLong()} Kč"
        } else {
            "$amount Kč"
        }
        accountText.text = iban

        shareButton.setOnClickListener {
            shareQRCode(qrBitmap!!, spdString)
        }

        closeButton.setOnClickListener {
            finish()
        }
    }

    private fun shareQRCode(bitmap: Bitmap, spdString: String) {
        try {
            val file = File(cacheDir, "qr_payment.png")
            FileOutputStream(file).use { fos ->
                bitmap.compress(Bitmap.CompressFormat.PNG, 100, fos)
            }

            val uri = Uri.parse("content://net.mdcz.qrpaywidget.fileprovider/qr_payment.png")

            val shareIntent = Intent(Intent.ACTION_SEND).apply {
                type = "image/png"
                putExtra(Intent.EXTRA_STREAM, uri)
                putExtra(Intent.EXTRA_TEXT, spdString)
                addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION)
            }

            val chooser = Intent.createChooser(shareIntent, getString(R.string.share_button))
            chooser.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION)
            startActivity(chooser)
        } catch (e: Exception) {
            val shareIntent = Intent(Intent.ACTION_SEND).apply {
                type = "text/plain"
                putExtra(Intent.EXTRA_TEXT, spdString)
            }
            startActivity(Intent.createChooser(shareIntent, getString(R.string.share_button)))
            Toast.makeText(this, "Sdílím jako text", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onDestroy() {
        super.onDestroy()
        qrBitmap?.recycle()
        qrBitmap = null
    }
}
