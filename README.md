# Przystanek na Wsparcie

Przystanek na Wsparcie to wtyczka do WordPressa służąca do zarządzania zespołem, gabinetami i rezerwacjami wsparcia. Wtyczka umożliwia dodawanie, edytowanie i usuwanie członków zespołu, zarządzanie ich dostępnością oraz przeglądanie rezerwacji.

## Struktura Plików

Poniżej znajduje się struktura plików wtyczki:
- `przystanek-na-wsparcie.php`
- `assets/`
  - `css/`
    - `przystanek-styles.css`
  - `js/`
    - `przystanek-scripts.js`

## Instalacja

1. Skopiuj wtyczkę do katalogu `wp-content/plugins`.
2. Aktywuj wtyczkę w panelu administracyjnym WordPressa.

## Funkcjonalność

1. Tworzenie tabel w bazie danych:
   - Tabela zespołu: `przystanek_zespol`
   - Tabela rezerwacji: `przystanek_rezerwacje`

2. Menu w panelu administracyjnym WordPressa:
   - Strona zarządzania zespołem
   - Strona listy członków zespołu

3. Formularz zarządzania członkami zespołu z zakładkami:
   - Informacje podstawowe
   - Dostępność
   - Rozliczenia

4. Obsługa AJAX:
   - Zapis danych członków zespołu
   - Usuwanie członków zespołu

5. Globalne style CSS i skrypty JavaScript

## Instrukcje Użytkowania

1. Zainstaluj wtyczkę poprzez dodanie jej do katalogu `wp-content/plugins`.
2. Aktywuj wtyczkę w panelu administracyjnym WordPressa.
3. Przejdź do menu 'Przystanek na Wsparcie' i zarządzaj członkami zespołu.
4. Używaj zakładek, aby przeglądać i edytować informacje, dostępność oraz rozliczenia członków zespołu.
5. Dodaj nowe daty niedostępności i usuń je, korzystając z przycisków w sekcji 'Dostępność'.
6. Przeglądaj listę członków zespołu i zarządzaj nimi na stronie 'Lista Zespołu'.
