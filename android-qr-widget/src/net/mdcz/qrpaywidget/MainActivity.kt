package net.mdcz.qrpaywidget

import android.app.Activity
import android.app.PendingIntent
import android.appwidget.AppWidgetManager
import android.content.ComponentName
import android.os.Build
import android.os.Bundle
import android.widget.Toast

class MainActivity : Activity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        if (Build.VERSION.SDK_INT >= 26) {
            try {
                val manager = AppWidgetManager.getInstance(this)
                val provider = ComponentName(this, QRPaymentWidget::class.java)

                val isSupported = manager.javaClass
                    .getMethod("isRequestPinAppWidgetSupported")
                    .invoke(manager) as Boolean

                if (isSupported) {
                    manager.javaClass.getMethod(
                        "requestPinAppWidget",
                        ComponentName::class.java,
                        Bundle::class.java,
                        PendingIntent::class.java
                    ).invoke(manager, provider, null, null)
                } else {
                    Toast.makeText(this, R.string.add_widget_manual, Toast.LENGTH_LONG).show()
                }
            } catch (e: Exception) {
                Toast.makeText(this, R.string.add_widget_manual, Toast.LENGTH_LONG).show()
            }
        } else {
            Toast.makeText(this, R.string.add_widget_manual, Toast.LENGTH_LONG).show()
        }

        finish()
    }
}
