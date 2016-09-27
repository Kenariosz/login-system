# CodeIgniter Basic Login

[Demó](http://login-system.kenariosz.hu/index.php/login)

A login rendszerhez tartozó file-ok:

1. frontend/libraries/Authentication_lib.php
2. frontend/models/accounts/Authentication.php
3. frontend/models/accounts/Login_attempt.php
4. frontend/models/accounts/User.php
5. frontend/models/accounts/User.php
6. frontend/config/settings.php


Alavetően a Authentication_lib vezérli az autentikációs folyamatokat. Ehhez tartozik 3 model:

## Authentication
A belépéshez/kilépéshez tartozó függvények.

## Login_attempt
A hibás belépések logolását végző függvények.

## User
A regisztrációhoz tartozó függvényeket tartalmazza