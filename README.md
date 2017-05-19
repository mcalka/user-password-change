# user-password-change

Aby uruchomić projekt użyj `composer install`

testowy użytkownik:
u: `test`
p: `!Pass123`

Zmiana zmiennych statycznych w pliku config/main.php

<!-- language: php -->
    'params' => array(
        'passwordAgeInDays'    => 30, //Liczba dni wieku hasła
        'passwordHistoryCount' => 5   //Liczba przechowanych haseł
    )


