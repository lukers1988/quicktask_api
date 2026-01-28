# QuickTask API

## Wymagania

* Docker
* Docker Compose

---

##  Technologie

- **PHP** – wersja 8.4.1
- **Symfony** – elastyczny framework PHP do tworzenia aplikacji webowych i API
- **Doctrine** – ORM do komunikacji z bazą danych
- **MySQL** – serwer baz danych
- **Docker** – zarządzanie infrastrukturą aplikacji pod lokalny development
---

## Uruchomienie projektu

Aby uruchomić projekt lokalnie, wykonaj:

```bash
docker compose up -d
```

Po uruchomieniu kontenerów aplikacja API będzie dostępna zgodnie z konfiguracją w `docker-compose.yml`.

---

## Baza danych testowa

### Utworzenie testowej bazy danych

Testowa baza danych jest tworzona ręcznie przy pomocy konsoli Symfony:

```bash
docker exec -it quicktask_api php bin/console doctrine:database:create --env=test
```

### Migracje bazy testowej

Po utworzeniu bazy danych należy uruchomić migracje:

```bash
docker exec -it quicktask_api php bin/console doctrine:migrations:migrate --env=test
```

---

## Testy

### Uruchamianie testów

Testy uruchamiane są za pomocą Composer:

```bash
docker exec -it quicktask_api composer run test
```

### Co robi uruchomienie testów?

Uruchomienie testów:

* odpala **Codeception**, który koordynuje:

  * testy API
  * testy jednostkowe (unit)
* uruchamia **PHPStan**, który sprawdza poprawność statyczną kodu (błędy typów, niespójności, potencjalne bugi)
* automatycznie **aktualizuje migracje bazy testowej**, jeżeli wykryje nowe lub zmienione migracje

---

## Architektura testów

* **Codeception** odpowiada za:

  * testy API (end‑to‑end / integracyjne)
  * testy jednostkowe
* Testy są uruchamiane w środowisku `test`
* Każdy test działa na odseparowanej bazie danych testowej

---

## Przydatne komendy

Wejście do kontenera aplikacji:

```bash
docker exec -it quicktask_api bash
```

Sprawdzenie statusu kontenerów:

```bash
docker compose ps
```

Zatrzymanie środowiska:

```bash
docker compose down
```

---

## Dobre praktyki

* Zawsze uruchamiaj testy przed pushowaniem zmian
* Nie używaj bazy testowej do lokalnego developmentu
* Migracje traktuj jako część kontraktu aplikacji — testy powinny je weryfikować

---

## Uwagi końcowe

Projekt jest przygotowany pod automatyzację (CI/CD) — jedno polecenie testowe weryfikuje:

* jakość kodu
* poprawność migracji
* stabilność API

Jeśli coś się wywala w testach, **to nie testy są problemem** 😉
