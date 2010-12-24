-- sample blog post

INSERT INTO `wm_blogposts` (`id`, `name`, `title`, `summary`, `content`, `author`, `created`, `updated`, `atomID`) VALUES
(
   NULL,
   'Witaj_w_Watermelonie!',
   'Witaj w Watermelonie!',
   'Cześć! Przed Tobą pierwsza publiczna wersja Watermelon CMS(Content Management System), aplikacji internetowej, za pomocą której możesz stworzyć własną stronę internetową lub bloga. Watermelon jest całkowicie darmowy, a jego kod jest udostępniony na wolnej licencji.

Naciśnij "czytaj dalej", aby dowiedzieć się więcej na temat obsługi Watermelona i tego jak możesz pomóc projektowi.',
   '%2',
   1, %1, %1, 'ba09226058bc1ea3711e035f932d1b7c66996625'
);

-- sample page

INSERT INTO `wm_pages` (`id`, `name`, `title`, `content`, `author`, `created`, `updated`) VALUES
(
   NULL, 'wmelonHelp', 'Pomoc Watermelona', '%3', 1, %1, %1
);

-- sample comment for these

INSERT INTO `wm_comments` (`id`, `record`, `type`, `authorID`, `authorName`, `authorEmail`, `authorWebsite`, `created`, `content`, `awaitingModeration`)
VALUES
(
   NULL, 1, 'blogpost', 1, NULL, NULL, NULL, %1,
   'W komentarzach również możesz używać __(uproszczonej)__ składni "**Textile**":http://pl.wikipedia.org/wiki/Textile',
   false
),
(
   NULL, 1, 'page', 1, NULL, NULL, NULL, %1,
   'Inny fajny koment',
   false
);

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);