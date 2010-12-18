-- sample blog post

INSERT INTO `wm_blogposts` (`id`, `name`, `title`, `content`, `summary`, `author`, `created`, `updated`, `atomID`) VALUES
(
   NULL,
   'Witaj_w_Watermelonie!',
   'Witaj w Watermelonie!',
   'Cześć! Przed tobą pierwsza publiczna wersja Watermelon CMS(Content Management System), aplikacji internetowej, za pomocą której możesz stworzyć własną stronę internetową lub bloga. Watermelon jest całkowicie darmowy, a jego kod jest udostępniony na wolnej licencji.

   Naciśnij "czytaj dalej", aby dowiedzieć się więcej na temat obsługi Watermelona i tego jak możesz pomóc projektowi.',
   
   'Cześć! Przed tobą pierwsza publiczna wersja Watermelon CMS(Content Management System), aplikacji internetowej, za pomocą której możesz stworzyć własną stronę internetową lub bloga. Watermelon jest całkowicie darmowy, a jego kod jest udostępniony na wolnej licencji.

   h2. Obsługa

   Po prawej stronie znajduje się link do Panelu Admina -- centrum sterowania Twoją stroną. Gdy się wylogujesz, link do Panelu Admina znajdziesz również na dole strony. Polecamy zapisać go w zakładkach Twojej przeglądarki -- będzie Ci znacznie łatwiej się tam dostać.

   Niektóre czynności możesz wykonać bezpośrednio na stronie, bez wchodzenia do Panelu Admina. Gdy jesteś zalogowany, w prawym górnym rogu każdej strony własnej i każdego wpisu na blogu są linki do szybkiej edycji i usunięcia. To samo dotyczy komentarzy, gdzie prócz tych dwóch opcji znajdują się linki do zaakceptowania niepewnego komentarza i odrzucenia spamu.

   W Panelu Admina, oprócz zarządzania treścią, po prawej stronie znajdują się ustawienia, gdzie możesz zmieniać niektóre właściwości strony, oraz edytować menu.

   h2. Zaawansowane opcje

   * Textile -- prosty język formatowania tekstu, bez użycia HTML, o dużych możliwościach. Wersja Textile w Watermelonie zawiera także modyfikacje pozwalające na: załączenie (kolorowanego) fragmentu kodu, proste dołączenie filmiku z YouTube i dołączenie wykonywalnego kodu PHP
   * "jQuery":http://jquery.com/, "PHPTAL":http://phptal.org/, "Turbine":http://turbine.peterkroener.de/ -- wygodne narzędzia dla programistów
   * "Sblam!":http://sblam.com -- skuteczne filtrowanie anty-spamowe

   h2. Rozwój

   Potrzebujemy Twojej pomocy! Watermelon jest we wczesnej fazie rozwoju i mamy jeszcze bardzo wiele rzeczy do zrobienia. Możesz pomóc nawet jeśli nie potrafisz programować. Wystarczy, że polecisz Watermelona swoim znajomym. Wiele to dla nas znaczy. Jeśli natomiast potrafisz programować, lub myślisz, że możesz w inny sposób pomóc bezpośrednio projektowi -- wejdź na "stronę projektu":https://github.com/radex/Watermelon i wyślij mi wiadomość. Zobaczymy co da się zrobić :) W szczególności potrzebna jest pomoc przy:

   * wyszukiwaniu błędów (szczególnie błędów bezpieczeństwa), testowaniu pod różnymi przeglądarkami
   * dokumentacji
   * wyglądzie (udoskonalanie domyślnej skórki, tworzenie nowych)
   * stronie projektu',
   1, %1, %1, 'ba09226058bc1ea3711e035f932d1b7c66996625'
);

-- sample page

INSERT INTO `wm_pages` (`id`, `name`, `title`, `content`, `author`, `created`, `updated`) VALUES
(
   NULL, 'Jakiś_tytuł', 'Jakiś tytuł', 'Jakaś strona, może z linkami do pomocy, etc.', 1, %1, %1
);

-- sample comment for these

INSERT INTO `wm_comments` (`id`, `record`, `type`, `authorID`, `authorName`, `authorEmail`, `authorWebsite`, `created`, `content`, `awaitingModeration`)
VALUES
(NULL, 1, 'blogpost', 1, NULL, NULL, NULL, %1,
   'W komentarzach również możesz używać __(uproszczonej)__ składni "**Textile**":http://pl.wikipedia.org/wiki/Textile',
false),
(NULL, 1, 'page',     1, NULL, NULL, NULL, %1, 'Inny fajny koment', false);

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);