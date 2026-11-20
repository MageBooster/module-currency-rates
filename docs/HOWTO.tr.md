# MageBooster Currency Rates (TCMB): Kullanım Kılavuzu

- **Paket:** `magebooster/module-currency-rates`
- **Lisans:** ücretli, seri numarası gerekir
- **Destek:** info@magebooster.com

## Ne işe yarar

Modül, Magento'ya **TCMB (Central Bank of the Republic of Türkiye) · MageBooster** adlı bir kur içe aktarma servisi ekler. Bu servis, Türkiye Cumhuriyet Merkez Bankası'nın her gün yayımladığı kur dosyasını okur.

- **Kur türü:** Döviz satış, döviz alış, efektif satış veya efektif alış kurlarından birini seçersiniz. Bir para biriminin efektif kuru yoksa döviz kuru kullanılır.
- **Fiyat farkı:** İsterseniz tüm kurlara yüzde olarak bir fark ekleyebilirsiniz.
- **Birim:** TCMB dosyasındaki "Unit" alanı dikkate alınır. Örneğin JPY 100 birim üzerinden verilir.
- **Çapraz kurlar:** İki para biriminden hiçbiri TRY değilse kur TRY üzerinden hesaplanır.
- **Dosyaya ulaşılamazsa:** Son kaydedilen kurlar kullanılmaya devam eder ve yönetim panelinde uyarı çıkar.
- **Seri numarası yoksa:** Geçerli bir seri numarası yokken modül kur aktarmaz. Mevcut kurlara dokunmaz ve "seri numaranızı girin" uyarısı gösterir.
- **Kayıtlar:** Tüm işlemler `var/log/magebooster_tcmb.log` dosyasına yazılır.

## Gereksinimler

- PHP 8.0 veya üzeri, `simplexml` eklentisi açık
- Magento 2.4.4 – 2.4.9
- MageBooster License modülü (`magebooster/module-license`). Bağımlılık olarak kendiliğinden kurulur.
- Luma sürümü: `magebooster/module-currency-rates`
- Hyvä sürümü: `magebooster/module-currency-rates-hyva`. Ayrı satılır.

## Composer ile kurulum (ücretli paket)

Ücretli MageBooster paketleri `https://repo.magebooster.com` adresindeki özel Composer deposundan gelir. Aşağıdaki komutları Magento kök klasöründe çalıştırın.

1. Depoyu ekleyin:

   ```bash
   composer config repositories.magebooster composer https://repo.magebooster.com
   ```

2. Erişim bilgilerinizi Magento kök klasöründeki `auth.json` dosyasına yazın. Şifre olarak seri numaranızı kullanın. Kullanıcı adı, sipariş e-postanızda size bildirilen addır.

   ```json
   {
     "http-basic": {
       "repo.magebooster.com": {
         "username": "<sipariş e-postasındaki kullanıcı adı>",
         "password": "<seri numaranız>"
       }
     }
   }
   ```

   Aynı ayarı tek komutla da yapabilirsiniz:

   ```bash
   composer config http-basic.repo.magebooster.com <kullanici-adi> <seri-numarasi>
   ```

3. Paketi kurun:

   ```bash
   composer require magebooster/module-currency-rates
   ```

   Hyvä temalı mağazada bunun yerine `magebooster/module-currency-rates-hyva` paketini kurun.

`auth.json` dosyasını sürüm kontrolüne (git) eklemeyin.

## Zip dosyasıyla kurulum

1. License modülü kurulu değilse `app/code/MageBooster/License` klasörüne açın.
2. Bu modülü `app/code/MageBooster/CurrencyRates` klasörüne açın.
3. Aşağıdaki etkinleştirme komutlarını çalıştırın.

## Modülü etkinleştirme

```bash
bin/magento module:enable MageBooster_License MageBooster_CurrencyRates
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

## Seri numarasını girme

1. **Stores > Configuration > MageBooster > Licenses** sayfasını açın.
2. `MB1` ile başlayan seri numaranızı **Currency Rates (TCMB)** alanına yapıştırın.
3. **Save Config** düğmesine tıklayın.
4. Alanın altında "Valid until ..." yazdığını kontrol edin.

Seri numarası her mağaza görünümünün alan adını kapsamalıdır. Ayrıntılar License kılavuzunda.

## Yönetim paneli ayarları

Ayarlar **Stores > Configuration > General > Currency Setup** sayfasında, **TCMB Rates (MageBooster)** grubundadır. Bu ayarlar yalnızca varsayılan (default) kapsamda yapılır.

| Alan | Varsayılan | Açıklama |
|---|---|---|
| Rate | ForexSelling | Hangi TCMB kurunun alınacağı. Seçenekler: ForexSelling (döviz satış), ForexBuying (döviz alış), BanknoteSelling (efektif satış), BanknoteBuying (efektif alış). Efektif kur yoksa döviz kuru kullanılır. |
| Price markup (%) | 0 | Her kura eklenecek yüzde. 0 veya daha büyük olmalı. |
| Feed URL | `https://www.tcmb.gov.tr/kurlar/today.xml` | Okunacak TCMB XML dosyası. TCMB dosyanın yerini değiştirmedikçe dokunmayın. |
| Connection timeout (seconds) | 15 | TCMB sunucusunun yanıtı için en fazla kaç saniye beklenecek. |

### Servisi seçme

- **Elle aktarma:**
  1. **Stores > Currency Rates** sayfasında **TCMB (Central Bank of the Republic of Türkiye) · MageBooster** servisini seçin.
  2. **Import** düğmesine, ardından **Save Currency Rates** düğmesine tıklayın.
- **Zamanlanmış aktarma:**
  1. **Stores > Configuration > General > Currency Setup > Scheduled Import Settings** bölümünde **Enabled** ayarını **Yes** yapın.
  2. TCMB servisini seçin.
  3. Sıklığı ve saati belirleyin.

  Zamanlanmış aktarmanın çalışması için Magento cron'unun çalışıyor olması gerekir.

## Test

1. Temel para biriminin ve izin verilen para birimlerinin TCMB'nin yayımladığı birimler (TRY, USD, EUR, GBP gibi) arasında olduğundan emin olun.
2. **Stores > Currency Rates** sayfasında TCMB servisini seçip **Import** düğmesine tıklayın.
3. Gelen değerleri TCMB sitesindeki kurlarla karşılaştırın. Fiyat farkı tanımladıysanız onu da hesaba katın.
4. `var/log/magebooster_tcmb.log` dosyasında aktarma kaydının oluştuğunu kontrol edin.

## Sorun giderme

- **"TCMB rates: enter your serial number in Stores > Configuration > MageBooster > Licenses."**
  - Lisanslar sayfasına geçerli bir seri numarası girin.
  - Seri numarasının tüm mağaza alan adlarını kapsadığını kontrol edin.
- **"TCMB rates could not be loaded (...). The last saved rates stay in use."** TCMB dosyasına ulaşılamadı ya da istek zaman aşımına uğradı.
  - Sunucunun `www.tcmb.gov.tr` adresine HTTPS ile bağlanabildiğini kontrol edin.
  - Bekleme süresini (Connection timeout) artırın.
  - Bir sonraki başarılı aktarmaya kadar eski kurlar kullanılır.
- **"TCMB has no rate for XXX."** TCMB her para birimi için kur yayımlamaz. Desteklenmeyen birimler atlanır.
- **Hafta sonu ve tatil günleri:** TCMB bu günlerde yeni kur yayımlamaz, bu yüzden son yayımlanan dosya kullanılır.

## Kaldırma

1. **Scheduled Import Settings** bölümünde başka bir kur servisini seçin.
2. Şu komutları çalıştırın:

   ```bash
   bin/magento module:disable MageBooster_CurrencyRates
   composer remove magebooster/module-currency-rates
   bin/magento setup:upgrade
   bin/magento cache:flush
   ```

Aktarılmış kurlar Magento'nun `directory_currency_rate` tablosunda kalır.

## Lisans ve kopyalama

Ticari lisans, bkz. `LICENSE.txt`. Telif hakkı (c) 2026 WebWorks 101 OÜ (MageBooster). Tüm hakları saklıdır.

- Bir seri numarası = bir canlı kurulum (bir mağaza alan adı). Aynı mağazanın test (staging) ve geliştirme kopyaları serbesttir.
- Her seri numarası ve her indirme, satın alan müşteriye bağlıdır. Luma ve Hyvä sürümleri ayrı ürünlerdir, seri numaraları da ayrıdır.
- Kodu kendi lisanslı mağazanız için değiştirebilirsiniz. Telif ve lisans notlarını silmeyin.
- Yasak olanlar: başka mağazalara kopyalamak, dağıtmak, yeniden satmak, alt lisans vermek, herkese açık depolarda, pazar yerlerinde veya forumlarda yayınlamak, lisans kontrolünü ya da bu notları kaldırmak, seri numarasını paylaşmak.
- İzinsiz kopyalama veya kullanımda seri numarası iade yapılmadan iptal edilir, güncelleme ve destek durur; WebWorks 101 OÜ tazminat isteyebilir ve yasal yola gidebilir. Estonya hukuku geçerlidir; yetkili mahkeme Harju İlçe Mahkemesi'dir (Harju Maakohus).

## Destek

info@magebooster.com
