# CodeIgniter Core

A rendszerhez tartozó core class-ok: (prefix nélkül)

1. Config.php
2. Controller.php
3. Exceptions.php
4. Lang.php
5. Loader.php
6. Model.php
7. Router.php
8. Security.php


## Config.php
Ez az osztály CI_Config egy bővített változata. Jelenlegi verziója lehetővé teszi, hogy adatbázisból is tudjuk kezelni a beállításokat. 

**`Fontos!`**
- A CI default config (config.php) felül definiálható, ha a settings tábla code oszlopának az értéke megegyezik a config/settings.php db_config_type változó értéke egyenlő. A default beállítás: config|config_admin.
- Van 1-2 config beállítás amit midnenképp meg kell adnunk file-ba, mert a rendszer inicializálásának elején még nem áll rendelkezésre az adatbázis kezelés és ezért a  config fáljra hivatkozunk.

### Dependencies
1. config/settings.php  (Részletes leírás a fájlban található)
2. models/settings/Setting.php

### Methods
**`load_settings_from_db()`**
A beállítások betöltése adatbázisból.


## Controller.php
Ez a használt controller-ek szülő osztálya. Ez tartalmazza az összes olyan hívást, betöltést és/vagy műveletet amit mindig el kell végezni.
**Például:** Login ellenőzés, nyelv kezelés... stb.
`Fejlesztés alatt...`


## Exceptions.php
Ez az osztály CI_Exceptions egy bővített változata. Template kezelés lett hozzáadva, egyéb funkcionális változás nem történt az eredetihez képest.

**`show_error()`**
Általános hibák megjelenítése.
Exceptions->show_error() függvény felül definiálása.
Változás: template file-ok elérésének módosítása.

**`show_exception()`**
Exception-ök megjelenítése.
Exceptions->show_exception() függvény felül definiálása.
Változás: template file-ok elérésének módosítása.

**`show_php_error()`**
PHP hibák megjelenítése.
Exceptions->show_php_error() függvény felül definiálása.
Változás: template file-ok elérésének módosítása.


## Lang.php
A több nyelvűségért felelős osztály. Jelenleg domain és URL sulg alapján képes a nyelvet kezelni.


## Loader.php
Beállítja a template view-t, ami a configban meg lett adva.


## Model.php
Ez a használt modellek szülő osztálya. Jelenleg alap CRUD műveleteket tartalmaz, amik láncolhatók, így megkönnyíti az alap rutin feladatokat.


## Router.php
`Fejlesztés alatt...`


## Security.php
**`xss_clean()`**
A függvényen belül a $naughty változó lett átalakítva. Ezáltal hatékonyabban szűri és eltávoltítja a külvilág felől érkező károsnak ítélt tartalmakat (tag-ek, script-ek).
Security->xss_clean() függvény felül definiálása.



# Dependency Classes


## Setting.php
Ez az osztály kezeli a beállításokkal történő minden műveletet, amit a gyári Config class nem tud vagy vagy nem úgy tudja ahogy szeretnénk.
**Megjegyzés:** Jelenleg nem tartalmaz tagfüggvényt, mert amire szükségünk van azt a szülő osztály (core/KE_Model.php) elvégzi.