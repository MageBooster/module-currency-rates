<!-- mb-top -->
# MageBooster TCMB Currency Rates

Central Bank of the Republic of Türkiye (TCMB) currency rates import for Magento 2, with optional price markup. Needs a MageBooster serial number.

![Magento 2.4.4–2.4.9](https://img.shields.io/badge/Magento-2.4.4%E2%80%932.4.9-orange) ![PHP 8.0+](https://img.shields.io/badge/PHP-8.0%2B-777bb4) ![Luma | Hyvä](https://img.shields.io/badge/themes-Luma%20%7C%20Hyv%C3%A4-0a7) ![Licence: Commercial](https://img.shields.io/badge/licence-Commercial-red)

```bash
composer require magebooster/module-currency-rates
bin/magento setup:upgrade
```

Paid packages are served from the MageBooster Composer repository (`https://repo.magebooster.com`); your serial number is the password. See the guide for the full steps.

- Guide: [English](docs/HOWTO.en.md) · [Türkçe](docs/HOWTO.tr.md)
- Changes: [CHANGELOG.md](CHANGELOG.md)
- Buy a licence: https://magebooster.com
- Support: info@magebooster.com

---

<!-- /mb-top -->
# MageBooster_CurrencyRates – TCMB exchange rates for Magento 2

Daily Central Bank of the Republic of Türkiye (TCMB) rates as a Magento currency import service, for Magento 2.4.9 /
Mage-OS (PHP 8.0+ – 8.5). Commercial licence (see LICENSE.txt): using it needs a **MageBooster serial number** per production domain. Türkçe açıklama aşağıda.

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

## Licence & copying

Commercial licence, see `LICENSE.txt`. Copyright (c) 2026 WebWorks 101 OÜ (MageBooster). All rights reserved.

- One serial number = one production installation (one store domain). Staging and development copies of that same store are allowed.
- Each serial number and each download is tied to the customer who bought it. Luma and Hyvä editions are separate products with separate serial numbers.
- You may modify the code for your own licensed store. Keep the copyright and licence notices.
- Not allowed: copying to other stores, redistributing, reselling, sublicensing, publishing in public repositories, marketplaces or forums, removing licence checks or these notices, sharing serial numbers.
- In case of unauthorised copying or use, the serial number is revoked without refund, updates and support stop, and WebWorks 101 OÜ may claim damages and take legal action. Estonian law applies; the court is Harju County Court.

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

## Lisans ve kopyalama

Ticari lisans, bkz. `LICENSE.txt`. Telif hakkı (c) 2026 WebWorks 101 OÜ (MageBooster). Tüm hakları saklıdır.

- Bir seri numarası = bir canlı kurulum (bir mağaza alan adı). Aynı mağazanın test (staging) ve geliştirme kopyaları serbesttir.
- Her seri numarası ve her indirme, satın alan müşteriye bağlıdır. Luma ve Hyvä sürümleri ayrı ürünlerdir, seri numaraları da ayrıdır.
- Kodu kendi lisanslı mağazanız için değiştirebilirsiniz. Telif ve lisans notlarını silmeyin.
- Yasak olanlar: başka mağazalara kopyalamak, dağıtmak, yeniden satmak, alt lisans vermek, herkese açık depolarda, pazar yerlerinde veya forumlarda yayınlamak, lisans kontrolünü ya da bu notları kaldırmak, seri numarasını paylaşmak.
- İzinsiz kopyalama veya kullanımda seri numarası iade yapılmadan iptal edilir, güncelleme ve destek durur; WebWorks 101 OÜ tazminat isteyebilir ve yasal yola gidebilir. Estonya hukuku geçerlidir; yetkili mahkeme Harju İlçe Mahkemesi'dir (Harju Maakohus).
