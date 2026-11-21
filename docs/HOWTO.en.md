# MageBooster Currency Rates (TCMB): how-to

- Package: `magebooster/module-currency-rates`
- Licence: paid (serial required)
- Support: info@magebooster.com

## What it does

This module adds a currency rate import service to Magento. The service is called **TCMB (Central Bank of the Republic of Türkiye) · MageBooster**. It reads the daily rates file published by the TCMB.

- You choose which TCMB rate to use: forex selling, forex buying, banknote selling or banknote buying. If a banknote rate is missing for a currency, the forex rate is used.
- An optional percentage markup can be added to every rate.
- The TCMB "Unit" field is respected. For example, JPY is quoted per 100 units.
- When neither currency in a pair is TRY, the rate is calculated through TRY.
- If the TCMB file cannot be reached, the last saved rates are kept and an admin notice is shown.
- Without a valid serial the module keeps importing for 30 days (grace period, see Licence check below). After that it imports no rates; existing rates stay untouched and the import shows a "license required" message.
- Activity is logged to `var/log/magebooster_tcmb.log`.

## Requirements

- PHP 8.0 or later, with the `simplexml` extension
- Magento 2.4.4 to 2.4.9
- The MageBooster License module (`magebooster/module-license`), installed automatically as a dependency
- Luma edition: `magebooster/module-currency-rates`
- Hyvä edition: `magebooster/module-currency-rates-hyva`
- The two editions are sold separately.

## Install with Composer (paid)

Paid MageBooster packages come from the private repository at `https://repo.magebooster.com`. Run these steps from the Magento root folder.

1. Add the repository:

   ```bash
   composer config repositories.magebooster composer https://repo.magebooster.com
   ```

2. Add your access details to `auth.json` in the Magento root. Your serial number is the password. Use the username given in your order email.

   ```json
   {
     "http-basic": {
       "repo.magebooster.com": {
         "username": "<username from your order email>",
         "password": "<your serial number>"
       }
     }
   }
   ```

   You can also set them with this command:

   ```bash
   composer config http-basic.repo.magebooster.com <username> <serial>
   ```

3. Install the package:

   ```bash
   composer require magebooster/module-currency-rates
   ```

   On a Hyvä store, install `magebooster/module-currency-rates-hyva` instead.

Keep `auth.json` out of version control.

## Install from a zip file

1. Unzip the License module into `app/code/MageBooster/License`, unless it is already installed.
2. Unzip this module into `app/code/MageBooster/CurrencyRates`.
3. Run the commands in the next section.

## Enable the module

```bash
bin/magento module:enable MageBooster_License MageBooster_CurrencyRates
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

## Enter the serial number

1. Go to **Stores > Configuration > MageBooster > Licenses**.
2. Paste your serial (it starts with `MB1`) into **Currency Rates (TCMB)**.
3. Click **Save Config**.
4. The status line should read "Valid until ...".

The serial must cover the domain of every store view. See the License guide for details.

## Admin configuration

Go to **Stores > Configuration > General > Currency Setup** and open the group **TCMB Rates (MageBooster)**. These settings exist at the default scope only.

| Field | Default | What it means |
|---|---|---|
| Rate | ForexSelling | Which TCMB rate to import. The options are ForexSelling, ForexBuying, BanknoteSelling and BanknoteBuying. A missing banknote rate falls back to the forex rate. |
| Price markup (%) | 0 | A percentage added to every imported rate. It must be 0 or more. |
| Feed URL | `https://www.tcmb.gov.tr/kurlar/today.xml` | The TCMB XML file to read. Change it only if the TCMB moves the file. |
| Connection timeout (seconds) | 15 | How long to wait for the TCMB server before giving up. |

### Choose the service

You can import rates by hand, on a schedule, or both.

- **Manual import:** go to **Stores > Currency Rates**. In the import list, pick **TCMB (Central Bank of the Republic of Türkiye) · MageBooster**, then click **Import** and then **Save Currency Rates**.
- **Scheduled import:** go to **Stores > Configuration > General > Currency Setup > Scheduled Import Settings**.
  1. Set **Enabled** to Yes.
  2. Choose the TCMB service.
  3. Set the frequency and time.

  Magento cron must be running for scheduled imports.

## Testing

1. Make sure the base currency and the allowed currencies include currencies that the TCMB publishes, such as TRY, USD, EUR and GBP.
2. Go to **Stores > Currency Rates**, choose the TCMB service and click **Import**.
3. Compare the imported values with the TCMB website. If you set a markup, add it to the TCMB value before comparing.
4. Check `var/log/magebooster_tcmb.log` for the import entry.

## Troubleshooting

- **"TCMB rates need a MageBooster license. Enter your serial number in Stores > Configuration > MageBooster > Licenses."**
  - Enter a valid serial on the Licenses page.
  - Check that the serial covers every store view's domain.
- **"TCMB rates could not be loaded (...). The last saved rates stay in use."** The TCMB file was unreachable or timed out.
  - Make sure the server can make outbound HTTPS requests to `www.tcmb.gov.tr`.
  - Increase the timeout.
  - The last saved rates are kept until the next successful import.
- **"TCMB has no rate for XXX."** The TCMB does not publish every currency. Unsupported currencies are skipped.
- **Weekends and holidays.** The TCMB does not publish new rates on these days, so the latest file is used.

## Uninstall

1. Under **Scheduled Import Settings**, switch to another import service.
2. Run:

   ```bash
   bin/magento module:disable MageBooster_CurrencyRates
   composer remove magebooster/module-currency-rates
   bin/magento setup:upgrade
   bin/magento cache:flush
   ```

Imported rates stay in Magento's `directory_currency_rate` table.

## Licence check (serial number)

- Enter the serial in **Stores > Configuration > MageBooster > Licenses**, field **Currency Rates (TCMB)** (Hyvä edition: **Currency Rates (TCMB) (Hyvä edition)**). The serial starts with `MB1`.
- The **License status** table on that page shows each module as **Active**, **Grace period** or **Switched off**. `bin/magento magebooster:license:status` shows the same.
- Without a valid serial (none entered, not valid, expired, another domain or another product) the module keeps working for **30 days**.
  - The 30 days start the first time the store sees the module without a valid serial.
  - A valid serial ends the grace period. If that serial later stops being valid (for example it expires), a new 30-day period starts.
- During and after the grace period, every admin page shows a notice that cannot be closed. It names the module, the reason, and the days left or the date it was switched off, with links to the Licenses page and to your serial numbers at https://store.magebooster.com/licences/account/.
- Shoppers never see a licence message, and the check runs inside your store: nothing is sent to MageBooster.
- Development hosts (`localhost`, `127.0.0.1`, `*.localhost`, `*.test`, `*.local`) always pass the domain check.
- **After the 30 days:** TCMB rates are no longer imported, neither by the scheduled update nor by **Import** in Stores > Currency Rates. The last saved rates stay in use.

## Licence & copying

Commercial licence, see `LICENSE.txt`. Copyright (c) 2026 WebWorks 101 OÜ (MageBooster). All rights reserved.

- One serial number = one production installation (one store domain). Staging and development copies of that same store are allowed.
- Each serial number and each download is tied to the customer who bought it. Luma and Hyvä editions are separate products with separate serial numbers.
- You may modify the code for your own licensed store. Keep the copyright and licence notices.
- Not allowed: copying to other stores, redistributing, reselling, sublicensing, publishing in public repositories, marketplaces or forums, removing licence checks or these notices, sharing serial numbers.
- In case of unauthorised copying or use, the serial number is revoked without refund, updates and support stop, and WebWorks 101 OÜ may claim damages and take legal action. Estonian law applies; the court is Harju County Court.

## Support

info@magebooster.com
