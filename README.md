# projektBazy
Aby projekt poprawnie działał, należy zaimportować plik "struktura bayz.txt" do bazy danych.
W tym imporcie jest jeden użytkownik z uprawnieniami admina
LOGIN: admin
HASŁO: 123

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