-- 
-- Structure for table `wm_benchmark`
-- 

DROP TABLE IF EXISTS `wm_benchmark`;
CREATE TABLE IF NOT EXISTS `wm_benchmark` (
  `name` varchar(256) NOT NULL,
  `value` int(11) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_blogposts`
-- 

DROP TABLE IF EXISTS `wm_blogposts`;
CREATE TABLE IF NOT EXISTS `wm_blogposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `beginning` text,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_blogposts`
-- 

INSERT INTO `wm_blogposts` (`id`, `name`, `title`, `beginning`, `content`, `author`, `created`) VALUES
  ('8', 'test1', 'Testowy tytuł (1)', NULL, 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', '1', '1234567890'),
  ('9', 'test2', 'Testowy tytuł (2)', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', '1', '1234567890'),
  ('13', 'foo', 'Foo', NULL, 'Boo hoo *hoo*!', '1', '1289161470');

-- 
-- Structure for table `wm_comments`
-- 

DROP TABLE IF EXISTS `wm_comments`;
CREATE TABLE IF NOT EXISTS `wm_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authorID` int(11) DEFAULT NULL,
  `authorName` varchar(40) DEFAULT NULL,
  `authorEmail` varchar(64) DEFAULT NULL,
  `authorWebsite` varchar(40) DEFAULT NULL,
  `created` int(10) NOT NULL,
  `content` text NOT NULL,
  `awaitingModeration` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments_records`
-- 

DROP TABLE IF EXISTS `wm_comments_records`;
CREATE TABLE IF NOT EXISTS `wm_comments_records` (
  `record` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  KEY `blogpostcomm_post` (`record`),
  KEY `commrecord_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_pages`
-- 

DROP TABLE IF EXISTS `wm_pages`;
CREATE TABLE IF NOT EXISTS `wm_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_pages`
-- 

INSERT INTO `wm_pages` (`id`, `name`, `title`, `content`, `author`, `created`) VALUES
  ('1', 'fooPage', 'Test (foo)', 'Drogi Marszałku, Wysoka Izbo. PKB rośnie. Różnorakie i określenia postaw uczestników wobec zadań stanowionych przez organizację. \r\n\r\nReasumując. skoordynowanie pracy obu urzędów zmusza nas do przeanalizowania kolejnych kroków w określaniu postaw uczestników wobec zadań stanowionych przez organizację. Podniosły się iż wykorzystanie unijnych dotacji jest to, iż aktualna struktura organizacji powoduje docenianie wag dalszych kierunków rozwoju. Jednakże, usprawnienie systemu finansowego spełnia ważne z dotychczasowymi zasadami dalszych kierunków postępowego wychowania. \r\n\r\n\r\nW związku z szerokim aktywem jest to, że zakres i określenia postaw uczestników wobec zadań stanowionych przez organizację. Gdy za sobą proces wdrożenia i unowocześniania istniejących %kryteriów% wymaga sprecyzowania i realizacji istniejących kryteriów pomaga w kształtowaniu modelu rozwoju. Gdy za sobą proces wdrożenia i bogate doświadczenia pozwalają na celu. W ten sposób realizacja określonych zadań stanowionych przez organizację. Często błędnie postrzeganą sprawą jest ważne z powodu systemu szkolenia kadry odpowiadającego potrzebom. Często niezauważanym szczegółem jest to, że skoordynowanie pracy obu urzędów wymaga niezwykłej \"precyzji\":http://example.com w tym zakresie zabezpiecza udział szerokiej grupie w kształtowaniu odpowiednich warunków administracyjno-finansowych. Często niezauważanym szczegółem jest że, zakończenie tego projektu zabezpiecza udział szerokiej grupie w restrukturyzacji przedsiębiorstwa. Nie mylmy.', '1', '1234579890'),
  ('2', 'secondPage', 'Test!', 'Izbo, inwestowanie w wypracowaniu postaw uczestników wobec zadań stanowionych przez organizację. Jest dobrze. Obywatelu, zmiana istniejących kryteriów spełnia ważne z dotychczasowymi zasadami systemu powszechnego uczestnictwa. Nie muszę państwa przekonywać, że zawiązanie koalicji jest ważne z powodu istniejących kryteriów umożliwia w restrukturyzacji przedsiębiorstwa. Proszę państwa, stałe zabezpieczenie informacyjne naszej kompetencji w przyszłościowe rozwiązania wymaga niezwykłej precyzji w określaniu istniejących kryteriów powoduje docenianie wag istniejących kryteriów koliduje z tym, że wzmocnienie i koledzy, inwestowanie w wypracowaniu dalszych poczynań. Różnorakie i realizacji kolejnych kroków w restrukturyzacji przedsiębiorstwa. Ostatnie szlify systemu obsługi spełnia istotną rolę w większym stopniu tworzenie postaw uczestników wobec zadań programowych spełnia ważne zadanie w przygotowaniu i unowocześniania kolejnych kroków w wypracowaniu obecnej sytuacji. Nie chcę państwu niczego sugerować, ale nowy model działalności organizacyjnej koliduje z szerokim aktywem spełnia istotną rolę w określaniu systemu finansowego wymaga sprecyzowania i miejsce ostatnimi czasy, dobitnie świadczy o nowe rekordy umożliwia w określaniu form działalności zabezpiecza udział szerokiej grupie w przygotowaniu i określenia nowych propozycji. Nikt inny was nie możemy zdradzać iż inwestowanie w restrukturyzacji przedsiębiorstwa. Nie zapominajmy jednak, że zakres i unowocześniania odpowiednich warunków.', '1', '1234567890');

-- 
-- Structure for table `wm_privileges`
-- 

DROP TABLE IF EXISTS `wm_privileges`;
CREATE TABLE IF NOT EXISTS `wm_privileges` (
  `user` int(11) NOT NULL,
  `privilege` varchar(64) NOT NULL,
  KEY `privileges_user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_privileges`
-- 

INSERT INTO `wm_privileges` (`user`, `privilege`) VALUES
  ('1', 'admin');

-- 
-- Structure for table `wm_registry`
-- 

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_registry`
-- 

INSERT INTO `wm_registry` (`name`, `value`) VALUES
  ('wmelon', 'O:8:\"stdClass\":13:{s:11:\"modulesList\";O:8:\"stdClass\":7:{s:11:\"controllers\";a:6:{s:4:\"blog\";s:4:\"blog\";s:8:\"comments\";s:8:\"comments\";s:5:\"pages\";s:5:\"pages\";s:4:\"test\";s:4:\"test\";s:4:\"auth\";s:10:\"watermelon\";s:4:\"e404\";s:10:\"watermelon\";}s:6:\"models\";a:4:{s:4:\"blog\";s:4:\"blog\";s:8:\"comments\";s:8:\"comments\";s:5:\"pages\";s:5:\"pages\";s:4:\"auth\";s:10:\"watermelon\";}s:9:\"blocksets\";a:1:{s:4:\"user\";s:10:\"watermelon\";}s:10:\"extensions\";a:3:{s:8:\"comments\";s:8:\"comments\";s:5:\"sblam\";s:5:\"sblam\";s:4:\"auth\";s:10:\"watermelon\";}s:5:\"skins\";a:1:{i:0;s:12:\"wcmslay_skin\";}s:14:\"acpcontrollers\";a:4:{s:4:\"blog\";s:4:\"blog\";s:8:\"comments\";s:8:\"comments\";s:5:\"pages\";s:5:\"pages\";s:4:\"test\";s:4:\"test\";}s:12:\"acpinfofiles\";a:4:{i:0;s:4:\"blog\";i:1;s:8:\"comments\";i:2;s:5:\"pages\";i:3;s:4:\"test\";}}s:8:\"autoload\";a:3:{i:0;s:4:\"auth\";i:1;s:8:\"comments\";i:2;s:5:\"sblam\";}s:17:\"controllerHandler\";N;s:17:\"defaultController\";s:4:\"blog\";s:7:\"siteURL\";s:19:\"http://localhost/w/\";s:9:\"systemURL\";s:26:\"http://localhost/w/wmelon/\";s:4:\"skin\";s:7:\"wcmslay\";s:4:\"lang\";s:2:\"pl\";s:8:\"siteName\";s:10:\"Watermelon\";s:10:\"siteSlogan\";s:6:\"Slogan\";s:6:\"footer\";s:29:\"Testowanie <em>stopki</em>…\";s:10:\"blockMenus\";a:1:{i:0;a:1:{i:0;a:4:{i:0;s:5:\"Test!\";i:1;s:4:\"user\";i:2;s:4:\"card\";i:3;a:0:{}}}}s:9:\"textMenus\";a:1:{i:0;a:5:{i:0;a:4:{i:0;s:4:\"Blog\";i:1;s:4:\"blog\";i:2;b:0;i:3;s:9:\"Blooog!!!\";}i:1;a:4:{i:0;s:5:\"Testy\";i:1;s:4:\"test\";i:2;b:0;i:3;N;}i:2;a:4:{i:0;s:5:\"Login\";i:1;s:10:\"auth/login\";i:2;b:0;i:3;N;}i:3;a:4:{i:0;s:6:\"Logout\";i:1;s:11:\"auth/logout\";i:2;b:0;i:3;N;}i:4;a:4:{i:0;s:3:\"ACP\";i:1;s:5:\"admin\";i:2;b:0;i:3;N;}}}}');

-- 
-- Structure for table `wm_users`
-- 

DROP TABLE IF EXISTS `wm_users`;
CREATE TABLE IF NOT EXISTS `wm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL COMMENT 'name used when logging in',
  `password` varchar(40) NOT NULL,
  `salt` varchar(16) NOT NULL,
  `nick` varchar(40) NOT NULL COMMENT 'displayed user name',
  `email` varchar(64) NOT NULL,
  `lastseen` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_login` (`login`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_users`
-- 

INSERT INTO `wm_users` (`id`, `login`, `password`, `salt`, `nick`, `email`, `lastseen`) VALUES
  ('1', 'radex', 'ac2039247f211bd4e69667aade2e25eac3beecaf', '1234567890123456', 'Radex ®', 'radexpl@gmail.com', '1290369332');

