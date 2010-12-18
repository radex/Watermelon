-- sample blog post

INSERT INTO `wm_blogposts` (`id`, `name`, `title`, `content`, `summary`, `author`, `created`, `updated`, `atomID`) VALUES
(
   NULL,
   'Witaj_w_Watermelonie!',
   'Witaj w Watermelonie!',
   'Jakiś dłuższy tekst z Textile\'owskim markupem',
   'Krótki wstęp do wpisu na blogu',
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
(NULL, 1, 'blogpost', 1, NULL, NULL, NULL, %1, 'Jakiś fajny koment', false),
(NULL, 1, 'page',     1, NULL, NULL, NULL, %1, 'Inny fajny koment', false);

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);