Aby zainstalować projekt należy:
- wrzucić folder app na serwer(lub lokalnie) do wcześniej utworzonego folderu
- skonfigurować dane dostępowe do bazy danych w pliku .env w lini DATABASE_URL
- zainstalować wszystkie biblioteki za pomocą polecenia composer install wewnątrz folderu app
- stworzyć nową migracje do bazy danych za pomocą polecenia php bin/console make:migration
- wykonać migracje na bazie danych za pomocą polecenia php bin/console doctrine:migrations:migrate
- zapełnić serwis losowo wygenerowaną treścią za pomocą polecenia php bin/console doctrine:fixtures:load

