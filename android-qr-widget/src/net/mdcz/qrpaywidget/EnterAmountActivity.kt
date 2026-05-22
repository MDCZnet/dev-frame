package net.mdcz.qrpaywidget

import android.app.Activity
import android.appwidget.AppWidgetManager
import android.content.Intent
import android.os.Bundle
import android.view.inputmethod.InputMethodManager
import android.widget.Button
import android.widget.EditText
import android.widget.Toast

class EnterAmountActivity : Activity() {

    private var widgetId = AppWidgetManager.INVALID_APPWIDGET_ID

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        widgetId = intent.getIntExtra(
            AppWidgetManager.EXTRA_APPWIDGET_ID,
            AppWidgetManager.INVALID_APPWIDGET_ID
        )

        val prefs = getSharedPreferences(QRPaymentWidget.PREFS_NAME, MODE_PRIVATE)
        val accountNumber = prefs.getString("account_$widgetId", "") ?: ""

        if (accountNumber.isEmpty()) {
            Toast.makeText(this, R.string.no_account_error, Toast.LENGTH_LONG).show()
            val configIntent = Intent(this, WidgetConfigActivity::class.java).apply {
                putExtra(AppWidgetManager.EXTRA_APPWIDGET_ID, widgetId)
            }
            startActivity(configIntent)
            finish()
            return
        }

        setContentView(R.layout.activity_enter_amount)

        val amountEdit = findViewById(R.id.amount_edit) as EditText
        val okButton = findViewById(R.id.ok_button) as Button
        val cancelButton = findViewById(R.id.cancel_button) as Button

        amountEdit.requestFocus()
        val imm = getSystemService(INPUT_METHOD_SERVICE) as InputMethodManager
        imm.showSoftInput(amountEdit, InputMethodManager.SHOW_IMPLICIT)

        okButton.setOnClickListener {
            val amountText = amountEdit.text.toString().trim().replace(",", ".")
            if (amountText.isEmpty() || amountText.toDoubleOrNull() == null || amountText.toDouble() <= 0) {
                Toast.makeText(this, R.string.invalid_amount_error, Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            val formattedAmount = "%.2f".format(amountText.toDouble())
            val name = prefs.getString("name_$widgetId", "") ?: ""

            val showQRIntent = Intent(this, ShowQRActivity::class.java).apply {
                putExtra("amount", formattedAmount)
                putExtra("account", accountNumber)
                putExtra("name", name)
                flags = Intent.FLAG_ACTIVITY_NEW_TASK
            }
            startActivity(showQRIntent)
            finish()
        }

        cancelButton.setOnClickListener {
            finish()
        }
    }
}
