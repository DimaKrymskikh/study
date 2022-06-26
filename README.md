После клонирования нужно выполнить 
    composer install

Тесты запускаются командой
    composer test

Базовая команда для восстановления базы
    pg_restore -d newdb dvdrental.dump

Нужно поправить файл protected/config/db.php для фактического сервера progtest

После этого приложение должно заработать

База данных состоит из двух схем: public, info

Схема public взята из [PostgreSQL Sample Database](https://www.postgresqltutorial.com/postgresql-getting-started/postgresql-sample-database/)

Всё что добавлено - в схеме info
