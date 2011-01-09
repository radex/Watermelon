-- sample blog post

INSERT INTO `wm_blogposts` (`name`, `title`, `summary`, `content`, `author`, `published`, `updated`, `atomID`, `commentsCount`) VALUES
(
   'Witaj_w_Watermelonie!',
   'Witaj w Watermelonie!',
   '%2',
   '%3',
   1, %1, %1, '%5', 1
);

-- sample page

INSERT INTO `wm_pages` (`name`, `title`, `content`, `author`, `created`, `updated`) VALUES
(
   'wmelonHelp', 'Pomoc Watermelona', '%4', 1, %1, %1
);

-- sample comment for these

INSERT INTO `wm_comments` (`record`, `type`, `authorID`, `created`, `content`, `approved`)
VALUES
(
   1, 'blogpost', 1, %1,
   'W komentarzach również możesz używać _(uproszczonej)_ składni "**Textile**":http://pl.wikipedia.org/wiki/Textile',
   false
),
(
   1, 'page', 1, %1,
   'Ten artykuł jest widoczny tylko dla Ciebie. Jeśli chcesz, możesz podejrzeć, jak wygląda on "od środka". Uważaj tylko, żeby go nie usunąć! :)',
   false
),
(
   1, 'page', 1, %1,
   'Pamiętaj, aby usunąć link do tej podstrony z menu, aby inni go nie widzieli.

"Edycja menu":admin/options/nav/: Panel Admina » Ustawienia » Menu

Nadal będziesz mógł się tutaj dostać z "tabeli stron":admin/pages/ w Panelu Admina',
   false
);

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);