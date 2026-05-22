package net.mdcz.qrpaywidget

import java.math.BigInteger

object IBANConverter {

    fun czechToIBAN(czechAccountNumber: String): String? {
        val cleaned = czechAccountNumber.trim().replace(" ", "")

        if (cleaned.startsWith("CZ", ignoreCase = true)) {
            return cleaned.toUpperCase()
        }

        val parts = cleaned.split("/")
        if (parts.size != 2) return null

        val bankCode = parts[1].trim()
        if (bankCode.length != 4 || !bankCode.all { it.isDigit() }) return null

        val accountPart = parts[0].trim()
        val (prefix, account) = if (accountPart.contains("-")) {
            val split = accountPart.split("-", limit = 2)
            Pair(split[0].trim(), split[1].trim())
        } else {
            Pair("", accountPart)
        }

        if (!prefix.all { it.isDigit() } || !account.all { it.isDigit() }) return null
        if (account.isEmpty()) return null
        if (account.length > 10 || prefix.length > 6) return null

        val prefixPadded = prefix.padStart(6, '0')
        val accountPadded = account.padStart(10, '0')
        val bban = "$bankCode$prefixPadded$accountPadded"

        val checkDigits = calculateCheckDigits("CZ", bban) ?: return null
        return "CZ${checkDigits}${bban}"
    }

    private fun calculateCheckDigits(countryCode: String, bban: String): String? {
        return try {
            val countryNums = countryCode.map { c -> (c.toInt() - 'A'.toInt() + 10).toString() }.joinToString("")
            val numericString = bban + countryNums + "00"
            val remainder = BigInteger(numericString).mod(BigInteger.valueOf(97)).toInt()
            val checkDigit = 98 - remainder
            checkDigit.toString().padStart(2, '0')
        } catch (e: Exception) {
            null
        }
    }
}
