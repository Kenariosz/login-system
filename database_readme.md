# KE_System Database 


A rendszerhez használt táblák leírása.

1. languages
2. sessions
3. settings


## languages
Ebben a táblában tároljuk a nyelveket.

#### Tábla struktúra
**`language_id`**
A tábla egyedi azonosítója.
value: int(11) NOT NULL auto_increment, UNIQUE, PRIMARY KEY
**`name`**
**`code`**
**`locale`**
**`image`**
**`directory`**
**`sort_order`**
**`status`**


## sessions
A CI ebben a táblába menti a session adatokat.
További info: [CI Session](https://www.codeigniter.com/user_guide/libraries/sessions.html#database-driver)


## settings table
Ebben a táblában tároljuk a beállításokat. Ezek lehetnek config beállítások, rendszer adatok, modul adatok/beállítások... stb.

### Tábla struktúra
**`setting_id`**
A tábla egyedi azonosítója.
value: UNIQUE PRIMARY KEY, INTEGER
**`code`**
Ez tartalmazza az adott sor típusát: config, rendszer, model... beállítás.
value: VARCHAR 32
**`key`**
Ez a beállítás egyedi elnevezése. Ezt úgy kell felépíteni, hogy a megnevezés első része a code oszlopban megadott típus legyen. Tehát ha a code részben config-ot adtunk meg, akkor a key az így épül fel: config_base_url.
value: UNIQUE KEY, VARCHAR 32
**`value`**
Ez tartalmazza a beállítás értékét.
value: TEXT