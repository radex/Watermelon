
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `config`
--****--

CREATE TABLE IF NOT EXISTS `wcms_config` (
  `field` varchar(255) collate latin1_general_ci NOT NULL,
  `value` text collate latin1_general_ci NOT NULL,
  KEY `field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Zrzut danych tabeli `config`
--****--

INSERT INTO `wcms_config` (`field`, `value`) VALUES
('default_salt', '0');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `groups`
--****--

CREATE TABLE IF NOT EXISTS `wcms_groups` (
  `id` smallint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) collate latin1_general_ci NOT NULL,
  `users` text collate latin1_general_ci NOT NULL,
  `style` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `groups`
--****--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `menu`
--****--

CREATE TABLE IF NOT EXISTS `wcms_menu` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `position` smallint(2) unsigned NOT NULL,
  `capt` varchar(100) collate latin1_general_ci NOT NULL,
  `content` text collate latin1_general_ci NOT NULL,
  `condition` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `menu_capt` (`capt`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `menu`
--****--

INSERT INTO `wcms_menu` (`id`, `position`, `capt`, `content`, `condition`) VALUES
(3, 0, 'Menu', '<ul>\r\n<li><a href="<?=site_url("")?>">Strona łówna</a></li>\r\n<li><a href="<?=site_url("login")?>">Logowanie</a></li>\r\n<li><a href="<?=site_url("testowa/stronka")?>">Stronka własna</a></li>\r\n<li><a href="<?=site_url("jeszcze/inny/test")?>">Inny test</a></li>\r\n</ul>', ''),
(4, 1, 'Logowanie', '<load page loginbox-mini>', '!Controller::$_user->isLoggedIn()'),
(5, 1, 'Panel uzytkownika', '<load page loginbox-mini>', 'Controller::$_user->isLoggedIn()');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `pages`
--****--

CREATE TABLE IF NOT EXISTS `wcms_pages` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `content` text collate latin1_general_ci NOT NULL,
  `title` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=27 ;

--
-- Zrzut danych tabeli `pages`
--****--

INSERT INTO `wcms_pages` (`id`, `name`, `content`, `title`) VALUES
(26, 'loginbox-mini', '<?php\r\n$uuser = new User();\r\nif($uuser->isLoggedIn())\r\n      {\r\n         ?>\r\n<a href="$/profile/pw">Prywatne wiadomosci</a>\r\n\r\n<a href="$/login/logout">Wyloguj</a>\r\n         <?php\r\n      }\r\n      else\r\n      {\r\n         Controller::addMeta(''<style type="text/css">.loginform_mini{border:0;padding:0}.loginform_mini legend{display:none} .loginform_mini label{float:left;width:50px;display:block}.loginform_mini #submit{width:51px}.loginform_mini #password,.loginform_mini #login{width:140px}</style>'');\r\n         ?>\r\n<form action="$/login/submit" method="POST">\r\n   <fieldset class="loginform_mini">\r\n      <legend>Logowanie</legend>\r\n      \r\n      <label for="login">Login:</label>\r\n      <input type="text" name="login" id="login">\r\n      \r\n      <br>\r\n      \r\n      <label for="password">Haslo:</label>\r\n      <input type="password" name="password" id="password">\r\n      \r\n      <br>\r\n      \r\n      <input type="submit" id="submit" value="Loguj!">\r\n      \r\n      <input type="checkbox" name="autologin" id="autologin">\r\n      Zapamietaj mnie\r\n\r\n      <br>\r\n\r\n      <a href="$/login/sendnewpassword" title="Wysylanie nowego hasła">Zapomnialem hasła!</a>\r\n\r\n      <br>\r\n\r\n      <a href="$/register" title="Rejestracja">Nie mam jeszcze konta!</a>\r\n\r\n   </fieldset>\r\n</form>\r\n<?php\r\n}\r\n?>', 'Mini Loginbox');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `private_messages`
--****--

CREATE TABLE IF NOT EXISTS `wcms_private_messages` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `from` smallint(5) unsigned NOT NULL,
  `to` smallint(5) unsigned NOT NULL,
  `sent` int(10) NOT NULL,
  `subject` varchar(150) collate latin1_general_ci NOT NULL,
  `text` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `private_messages`
--****--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `privileges`
--****--

CREATE TABLE IF NOT EXISTS `wcms_privileges` (
  `id` smallint(2) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Zrzut danych tabeli `privileges`
--****--


-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users`
--****--

CREATE TABLE IF NOT EXISTS `wcms_users` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `nick` varchar(32) collate latin1_general_ci NOT NULL,
  `password` varchar(255) collate latin1_general_ci NOT NULL,
  `hashalgo` tinyint(1) unsigned NOT NULL default '0',
  `salt` varchar(16) collate latin1_general_ci NOT NULL,
  `ingroups` varchar(255) collate latin1_general_ci NOT NULL,
  `privileges` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `users`
--****--

INSERT INTO `wcms_users` (`id`, `nick`, `password`, `hashalgo`, `salt`, `ingroups`, `privileges`) VALUES
(1, 'radex', 'b0498c326336851f8a47a27a07f283912a5cd0d27687bbb2b19d1aaa79ac2dc5', 0, 'b3020f914a1e00ca', '', 0);

