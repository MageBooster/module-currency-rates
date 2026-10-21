# MageBooster_CurrencyRates – TCMB exchange rates for Magento 2

Daily Central Bank of the Republic of Türkiye (TCMB) rates as a Magento currency import service, for Magento 2.4.9 /
Mage-OS (PHP 8.3 – 8.5). Code is MIT; using it needs a **MageBooster serial number**. Türkçe açıklama aşağıda.

## Install

```bash
composer require magebooster/module-currency-rates   # also installs magebooster/module-license
bin/magento module:enable MageBooster_License MageBooster_CurrencyRates
bin/magento setup:upgrade
```

Enter the serial in Stores › Configuration › MageBooster › Licenses.

## Settings

Stores › Configuration › General › Currency Setup › **TCMB Rates (MageBooster)**:
- **Rate:** Forex selling (default), Forex buying, Banknote selling or Banknote buying. If TCMB has no banknote
  rate for a currency, the forex rate is used.
- **Price markup (%):** added on top of every converted rate (0 = none).
- Feed URL (default `https://www.tcmb.gov.tr/kurlar/today.xml`) and timeout.

Then choose **TCMB · MageBooster** as the service under Currency Setup › Scheduled Import Settings (cron) and/or in
Stores › Currency Rates › Import (manual).

## How rates are worked out

- TCMB publishes TRY per unit; the `Unit` field is respected (e.g. JPY is per 100).
- Any pair is calculated through TRY: `EUR→USD = (TRY per EUR) / (TRY per USD)`, then the markup is applied.
- Rates are saved to Magento's own rate table, so prices, the currency switcher and orders use standard Magento conversion.
- Log: `var/log/magebooster_tcmb.log`.

## When the feed is down

The import fails cleanly: the last saved rates stay as they are (Magento's scheduled import saves nothing when there are
errors), and an admin notice shows the error until the next successful import.

## Without a valid serial

The import service returns no rates and shows "enter your serial number"; the existing rates in Magento are left
untouched and no markup is applied. MageBooster payment modules (Moka, Rubikpara, Ödeal) keep working and use whatever
rates Magento has.

---

# MageBooster_CurrencyRates (TR)

TCMB günlük kurlarını Magento'nun kur içe aktarma servisi olarak ekler. Kullanmak için **MageBooster seri numarası** gerekir.

- Ayarlar: Mağazalar › Yapılandırma › Genel › Para Birimi Ayarları › **TCMB Rates (MageBooster)** — Döviz satış (varsayılan),
  Döviz alış, Efektif satış, Efektif alış; isteğe bağlı sabit **% fiyat farkı**; feed adresi ve zaman aşımı.
- Servis olarak **TCMB · MageBooster** seçilir: zamanlanmış içe aktarma (cron) ve/veya Mağazalar › Döviz Kurları › İçe aktar (elle).
- TCMB'nin `Unit` alanı dikkate alınır (ör. JPY 100 birim). Çapraz kurlar TL üzerinden hesaplanır. Kurlar Magento'nun kendi
  tablosuna yazılır; fiyatlar ve siparişler standart Magento dönüşümünü kullanır.
- TCMB'ye ulaşılamazsa son kayıtlı kurlar korunur, admin'de uyarı görünür.
- Geçerli seri numarası yoksa içe aktarma çalışmaz, "seri numaranızı girin" uyarısı çıkar; mevcut kurlar değişmez.
  MageBooster ödeme modülleri çalışmaya devam eder ve Magento'daki mevcut kurları kullanır.
