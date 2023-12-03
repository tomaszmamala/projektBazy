# projektBazy
Aby projekt poprawnie działał, należy zaimportować plik `struktura bazy.txt` do bazy danych o nazwie `hotele`. Całą zawartość programu umieścić w katalagu `htdocs` programu `xampp`. Potem w wyszukiwarce należy przejść na `localahost/projektBazy`

Domyślnie dodany jest jeden użytkownik z uprawnieniami administratora.
LOGIN: admin
HASŁO: 123

Konfiguracja bazy znajduje się w pliku `\services\DatabaseHandler.php`.

Po wejściu na stronę wyświetli się panel logowania i rejestracji. Aby zalogować się jako zwykły użytkownik należy najpierw się zarejestrować. Po zalogwaniu zwykły użytkownik może przeglądać hotele, rezerwować pokoje w danym przedziale czasowym, dodawać opinie do hoteli oraz przeglądać swoje rezerwacje. Administrator posiada możliwość dodawania. usuwania i edytowania hoteli.

Baza danych składa się z następujących tabel:
hotel
opinie
pokoj
rezerwacje
uzytkownik
zdjecie

opinie są połączone z tabelami:
uzytkownik
hotel

pokoj jest połączony z tabelą:
hotel

rezerwacje są połączone z tabelami:
uzytkownik
pokoj

zdjecie jest połączone z tabelą:
hotel
