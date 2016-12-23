# SpeedUpWP
This plugin make your Wordpress and Woocommerce instalation much faster! Disable some WP featured for unlock more speed admin backend and also frontend. Use some techniques like JavaScript defer loading, remove some query string, remove not used widgets etc. Only for Developers!

Tento plugin zrychluje instalaci Wordpressu a Woocommerce. Zakáže některé funkce Wordpressu, odstraní nepoužívané widgety, odstraní query strings ze zdrojového kódu, možnost defer javascriptu apod. Jenom pro vývojáře, kteří vědí, co která volba může udělat. V žádném případě není vhodný pro laiky, při nesprávném použití může kompletně odstavit webové stránky! 


Tags: Speed, Wordpress, Woocommerce, Admin, Admin speed, jQuery<br />
Requires at least: 4.0<br />
Tested up to: 4.7<br />
Stable tag: 0.5<br />

**Tento plugin zrychluje administraci Wordpressu a Woocommerce.**

# Description

Tento plugin zrychluje administraci Wordpressu a Woocommerce. Zakáže některé funkce Wordpressu, odstraní nepoužívané widgety, odstraní query strings ze zdrojového kódu, možnost defer javascriptu apod.
Jenom pro vývojáře, kteří vědí, co která volba může udělat. V žádném případě není vhodný pro laiky, při nesprávném použití může kompletně odstavit webové stránky!

Chcete se zapojit do dalšího vývoje?
Potom můžete použít přímo Github: https://github.com/novetrendy/SpeedUpWP

Nějaká funkce chybí?
Můžete ji sponzorovat a urychlit její implementaci.

Plugin zatím podporuje následující:

* Nástěnka - odstranění widgetů (metaboxů)
* Přesunutí jQuery do patičky
* Odstranění řetězce ver?xxx ze zdrojového kódu
* Odstranění meta tag generator Slider Revolution
* Načítání skriptů z ContactForm 7 jen na určených stránkách
* Deregistrace zadaných javascriptů
* Optimalizace Heartbeatu
* Zakázání oEmbed
* Zakázání Emojis
* Zakázání automatických aktualizací
* Zakázání XMLRPC
* Zakázání JSON REST API
* Odebrání komentářů
* Možnost zadání komprese jpg obrázků při nahrávání do knihovny médií
* Možnost zakázání jednotlivých Worpdpress a Woocommerce widgetů

Plugin currently supports the following:

* Dashboard - remove some WordPress and WooCommerce widgets
* Move jQuery to the footer
* Remove query string ver?xxx from source 
* Remove meta tag generator Slider Revolution 
* Deregister Contact Form7 styles and scripts in some pages
* Deregister script
* Optimize Heartbeat 
* Disable Oembed
* Disable Emojis
* Disable check for updates
* Disable XMLRPC
* Disable JSON REST API
* Completely disable comments
* Settings JPEG compression
* Disable some Wordpress Widgets
* Disable some WooCommerce Widgets


# Frequently Asked Questions

**Je plugin určen pro produkční prostředí ?**

Ano, ale pouze lidem, kteří vědí, co jednotlivé funkce dělají.

**Mohu špatným nastavením znepřístupnit stránky ?**
Ano můžete. Pokud se toto stane, ručně smažte plugin přes FTP.

# Changelog
= 161223 =
* Add remove metabox Semper plugins RSS (All In One Seo Pack) from dashboard

= 161221 =
* Completely rewriting admin UI to new FLAT UI
* Add disable load from wistia (WooCommerce)
* Add disable WooCommerce Dashboard Status
* Add disable WooCommerce Recent Review Dashboard widget
* Add remove post type Portfolio for Envision theme
* Add admin interface for completely remove WordPress comments
* Update PO/MO Czech language

= 161215 =
* Small admin CSS changes

= 161206 =
* Completely remove WordPress comments - without admin interface (on/off) 

= 161128 =
* Completely rewriting plugin - E_NOTICE - all variables are now being tested with isset()

= 161127 =
* Drobné opravy kvůli kompatibilitě s různými hostingy
* Přidání tlačítka uložit změny i do vrchní části stránky
* Přepsání kódu pro automatické aktualizace
* Oprava chyby v navigaci na stránku s nastavením
* Přidána podpora pro aktualizaci s GitHubu - nutno mít nainstalovaný GitHub Updater

= 1 =
* První vydání


# Screenshots

![Image of Speed Up WP] (https://github.com/novetrendy/SpeedUpWP/blob/master/SpeedUpWP.jpg)

# Installation

Instalovat plugin, aktivovat a přejít do menu Nové Trendy - Speed Up WP !.

Standard Wordpress installation. Settings page: New Trends -> Speed Up WP !
