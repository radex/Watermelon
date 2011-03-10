-- sample blog post

INSERT INTO `wm_blogposts` (`name`, `title`, `summary`, `content`, `author`, `published`, `updated`, `atomID`, `commentsCount`, `approvedCommentsCount`) VALUES
(
   'Witaj_w_Watermelonie!',
   'Witaj w Watermelonie!',
   '{post-summary}',
   '{post-content}',
   1, {time}, {time}, '{atom-id}', 1, 1
);

-- sample page

INSERT INTO `wm_pages` (`name`, `title`, `content`, `author`, `created`, `updated`, `commentsCount`, `approvedCommentsCount`) VALUES
(
   'wmelonHelp', 'Pomoc Watermelona', '{page-content}', 1, {time}, {time}, 2, 2
);

-- sample comment for these

INSERT INTO `wm_comments` (`record`, `type`, `authorID`, `created`, `content`, `approved`)
VALUES
(
   1, 'blogpost', 1, {time},
   'W komentarzach również możesz używać _(uproszczonej)_ składni "**Textile**":http://pl.wikipedia.org/wiki/Textile',
   true
),
(
   1, 'page', 1, {time},
   'Ten artykuł jest widoczny tylko dla Ciebie. Jeśli chcesz, możesz podejrzeć, jak wygląda on "od środka". Uważaj tylko, żeby go nie usunąć! :)',
   true
),
(
   1, 'page', 1, {time},
   'Pamiętaj, aby usunąć link do tej podstrony z menu, aby inni go nie widzieli.

"Edycja menu":admin/options/nav/: Panel Admina » Ustawienia » Menu

Nadal będziesz mógł się tutaj dostać z "tabeli stron":admin/pages/ w Panelu Admina',
   true
);

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);