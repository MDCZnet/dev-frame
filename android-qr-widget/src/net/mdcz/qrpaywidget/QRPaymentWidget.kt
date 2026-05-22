package net.mdcz.qrpaywidget

import android.appwidget.AppWidgetManager
import android.appwidget.AppWidgetProvider
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.view.View
import android.widget.RemoteViews

class QRPaymentWidget : AppWidgetProvider() {

    override fun onUpdate(context: Context, appWidgetManager: AppWidgetManager, appWidgetIds: IntArray) {
        for (widgetId in appWidgetIds) {
            updateWidget(context, appWidgetManager, widgetId)
        }
    }

    override fun onDeleted(context: Context, appWidgetIds: IntArray) {
        val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        val editor = prefs.edit()
        for (id in appWidgetIds) {
            editor.remove("account_$id")
            editor.remove("name_$id")
        }
        editor.apply()
    }

    companion object {
        const val PREFS_NAME = "qr_widget_prefs"

        fun updateWidget(context: Context, appWidgetManager: AppWidgetManager, widgetId: Int) {
            val views = RemoteViews(context.packageName, R.layout.widget_layout)

            val intent = Intent(context, EnterAmountActivity::class.java).apply {
                putExtra(AppWidgetManager.EXTRA_APPWIDGET_ID, widgetId)
                flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TOP
            }

            val pendingIntent = PendingIntent.getActivity(
                context, widgetId, intent,
                PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE
            )
            views.setOnClickPendingIntent(R.id.widget_root, pendingIntent)

            val prefs = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            val account = prefs.getString("account_$widgetId", "") ?: ""
            val name = prefs.getString("name_$widgetId", "") ?: ""

            val label = if (name.isNotEmpty()) "QR Platba - $name" else "QR Platba"
            views.setTextViewText(R.id.widget_label, label)

            if (account.isNotEmpty()) {
                val spdString = QRCodeHelper.buildSPD(account)
                val qrBitmap = QRCodeHelper.generateQR(spdString, 256)
                views.setImageViewBitmap(R.id.widget_qr_image, qrBitmap)
                views.setViewVisibility(R.id.widget_qr_image, View.VISIBLE)
                views.setViewVisibility(R.id.widget_no_account, View.GONE)
            } else {
                views.setViewVisibility(R.id.widget_qr_image, View.GONE)
                views.setViewVisibility(R.id.widget_no_account, View.VISIBLE)
            }

            appWidgetManager.updateAppWidget(widgetId, views)
        }
    }
}
