package net.mdcz.qrpaywidget

import android.app.Activity
import android.appwidget.AppWidgetManager
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast

class WidgetConfigActivity : Activity() {

    private var widgetId = AppWidgetManager.INVALID_APPWIDGET_ID

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setResult(RESULT_CANCELED)

        widgetId = intent.getIntExtra(
            AppWidgetManager.EXTRA_APPWIDGET_ID,
            AppWidgetManager.INVALID_APPWIDGET_ID
        )

        if (widgetId == AppWidgetManager.INVALID_APPWIDGET_ID) {
            finish()
            return
        }

        setContentView(R.layout.activity_widget_config)

        val prefs = getSharedPreferences(QRPaymentWidget.PREFS_NAME, MODE_PRIVATE)
        val accountEdit = findViewById(R.id.account_edit) as EditText
        val ibanPreview = findViewById(R.id.iban_preview) as TextView
        val saveButton = findViewById(R.id.save_button) as Button
        val cancelButton = findViewById(R.id.cancel_button) as Button

        val existing = prefs.getString("account_$widgetId", "") ?: ""
        if (existing.isNotEmpty()) accountEdit.setText(existing)

        accountEdit.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {}
            override fun afterTextChanged(s: Editable?) {
                val text = s?.toString()?.trim() ?: ""
                if (text.length >= 10) {
                    val iban = IBANConverter.czechToIBAN(text)
                    if (iban != null && !iban.equals(text, ignoreCase = true)) {
                        ibanPreview.text = "IBAN: $iban"
                        ibanPreview.visibility = android.view.View.VISIBLE
                    } else {
                        ibanPreview.visibility = android.view.View.GONE
                    }
                } else {
                    ibanPreview.visibility = android.view.View.GONE
                }
            }
        })

        saveButton.setOnClickListener {
            val accountNumber = accountEdit.text.toString().trim()
            if (accountNumber.isEmpty()) {
                Toast.makeText(this, R.string.invalid_account_error, Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            val iban = IBANConverter.czechToIBAN(accountNumber)
            if (iban == null) {
                Toast.makeText(this, R.string.invalid_account_error, Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            prefs.edit().putString("account_$widgetId", iban).apply()

            val appWidgetManager = AppWidgetManager.getInstance(this)
            QRPaymentWidget.updateWidget(this, appWidgetManager, widgetId)

            Toast.makeText(this, R.string.account_saved, Toast.LENGTH_SHORT).show()

            val resultValue = Intent().apply {
                putExtra(AppWidgetManager.EXTRA_APPWIDGET_ID, widgetId)
            }
            setResult(RESULT_OK, resultValue)
            finish()
        }

        cancelButton.setOnClickListener {
            finish()
        }
    }
}
