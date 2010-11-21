-- todo!

-- sample blog post

INSERT INTO `wm_blogposts` (`id`, `name`, `title`, `beginning`, `content`, `author`, `created`) VALUES
(
   NULL, 'welcome', 'Witaj w Watermelonie!', 'Jakiś wstęp', 'Jakiś dłuższy tekst z Textile\'owskim markupem', 1, %
);

-- sample page

INSERT INTO `wm_pages` (`id`, `name`, `title`, `content`, `author`, `created`) VALUES
(
   NULL, 'sample', 'Jakiś tytuł', 'Jakaś strona, może z linkami do pomocy, etc.', 1, %1
);

-- sample comment for these

INSERT INTO `wm_comments` (`id`, `authorID`, `authorName`, `authorEmail`, `authorWebsite`, `created`, `content`, `awaitingModeration`)
VALUES
(NULL, 1, NULL, NULL, NULL, %1, 'Jakiś fajny koment', false),
(NULL, 1, NULL, NULL, NULL, %1, 'Inny fajny koment', false);

-- and connection between

INSERT INTO `wm_comments_records` (`record`, `comment`, `type`)
VALUES
('1', '1', 'blogpost'),
('1', '2', 'page');

-- user privileges (user itself is created in controller)

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
(
   '1', 'admin'
);