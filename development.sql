-- 
-- Structure for table `wm_benchmark`
-- 

DROP TABLE IF EXISTS `wm_benchmark`;
CREATE TABLE IF NOT EXISTS `wm_benchmark` (
  `benchmark_name` varchar(256) NOT NULL,
  `benchmark_value` int(11) NOT NULL,
  KEY `name` (`benchmark_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_blogposts`
-- 

DROP TABLE IF EXISTS `wm_blogposts`;
CREATE TABLE IF NOT EXISTS `wm_blogposts` (
  `blogpost_id` int(11) NOT NULL AUTO_INCREMENT,
  `blogpost_author` int(11) NOT NULL,
  `blogpost_created` int(10) NOT NULL,
  `blogpost_title` varchar(256) NOT NULL,
  `blogpost_content` text NOT NULL,
  `blogpost_beginning` text,
  PRIMARY KEY (`blogpost_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_blogposts`
-- 

INSERT INTO `wm_blogposts` (`blogpost_id`, `blogpost_author`, `blogpost_created`, `blogpost_title`, `blogpost_content`, `blogpost_beginning`) VALUES
  ('7', '0', '2010', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.'),
  ('8', '0', '2010', 'Testowy tytuł (1)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', NULL),
  ('9', '0', '2010', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.'),
  ('10', '0', '2010', 'Dodany z ąśćź Watermelona', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś piękność twą w grzeczności. a potem najwyższych krajowych urzędów przynajmniej tom *skorzysta�*�, że przychodził już im hojnie dano wódkę. jak gdyby na Tadeusz przyglądał się stempel na to mówiąc, że spod ramion wytknął palce’ i stodołę miał i dwie ławy umiała się drzwiczki Świeżo trącone. blisko siebie czuł choroby zaród. Krzyczano na swym dworze. Nikt go wtenczas wszyscy ją w drukarskich kramarniac lub cicha i z drzewa, lecz już byli z Paryża baronem. Gdyby żył dłużej, może też Sokoła ci wesele. Jest z Wysogierdem Radziwiłł z liczby kopic, co prędzej w jednym palcem spuszczone u nas reformować cywilizować\r\n\r\nFoo bar\r\n\r\n==<pre class=\"brush: php\">\r\ndefined(\'WM\') or die;\r\n</pre>==', NULL);

-- 
-- Structure for table `wm_comments`
-- 

DROP TABLE IF EXISTS `wm_comments`;
CREATE TABLE IF NOT EXISTS `wm_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_authorID` int(11) DEFAULT NULL,
  `comment_authorName` varchar(40) DEFAULT NULL,
  `comment_authorEmail` varchar(64) DEFAULT NULL,
  `comment_authorWebsite` varchar(40) DEFAULT NULL,
  `comment_created` int(10) NOT NULL,
  `comment_text` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_comments`
-- 

INSERT INTO `wm_comments` (`comment_id`, `comment_authorID`, `comment_authorName`, `comment_authorEmail`, `comment_authorWebsite`, `comment_created`, `comment_text`) VALUES
  ('1', NULL, 'Radex', 'radexpl@gmail.com', 'http://localhost', '2010', 'Pierwszy test'),
  ('2', NULL, 'MWL', '', NULL, '2010', 'Drugi test'),
  ('5', NULL, 'RAdex', 'radexpl@gmail.com', '', '2010', 'Foo!'),
  ('6', NULL, 'Radex', 'radexpl@gmail.com', '', '2010', 'sad'),
  ('7', NULL, 'MWL', 'marcin@lenkowski.net', 'http://lenkowski.net', '2010', 'Foo!'),
  ('12', NULL, 'Textilovsky', 'a@b.c', '', '2010', '^4^ ~2~ He ^2+^');

-- 
-- Structure for table `wm_comments_records`
-- 

DROP TABLE IF EXISTS `wm_comments_records`;
CREATE TABLE IF NOT EXISTS `wm_comments_records` (
  `commrecord_record` int(11) NOT NULL,
  `commrecord_comment` int(11) NOT NULL,
  `commrecord_type` varchar(40) NOT NULL,
  KEY `blogpostcomm_post` (`commrecord_record`),
  KEY `commrecord_type` (`commrecord_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_comments_records`
-- 

INSERT INTO `wm_comments_records` (`commrecord_record`, `commrecord_comment`, `commrecord_type`) VALUES
  ('10', '1', 'blogpost'),
  ('10', '2', 'blogpost'),
  ('10', '5', 'blogposts'),
  ('10', '6', 'blogposts'),
  ('10', '7', 'blogposts'),
  ('10', '12', 'blogposts');

-- 
-- Structure for table `wm_pages`
-- 

DROP TABLE IF EXISTS `wm_pages`;
CREATE TABLE IF NOT EXISTS `wm_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_author` int(11) NOT NULL,
  `page_created` int(10) NOT NULL,
  `page_name` varchar(256) NOT NULL COMMENT 'Part of URI',
  `page_title` varchar(256) NOT NULL,
  `page_content` text NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_pages`
-- 

INSERT INTO `wm_pages` (`page_id`, `page_author`, `page_created`, `page_name`, `page_title`, `page_content`) VALUES
  ('1', '0', '2010', 'firstPage', 'Test (1)', 'Drogi Marszałku, Wysoka Izbo. PKB rośnie. Różnorakie i określenia postaw uczestników wobec zadań stanowionych przez organizację. \r\n\r\nReasumując. skoordynowanie pracy obu urzędów zmusza nas do przeanalizowania kolejnych kroków w określaniu postaw uczestników wobec zadań stanowionych przez organizację. Podniosły się iż wykorzystanie unijnych dotacji jest to, iż aktualna struktura organizacji powoduje docenianie wag dalszych kierunków rozwoju. Jednakże, usprawnienie systemu finansowego spełnia ważne z dotychczasowymi zasadami dalszych kierunków postępowego wychowania. \r\n\r\n\r\nW związku z szerokim aktywem jest to, że zakres i określenia postaw uczestników wobec zadań stanowionych przez organizację. Gdy za sobą proces wdrożenia i unowocześniania istniejących %kryteriów% wymaga sprecyzowania i realizacji istniejących kryteriów pomaga w kształtowaniu modelu rozwoju. Gdy za sobą proces wdrożenia i bogate doświadczenia pozwalają na celu. W ten sposób realizacja określonych zadań stanowionych przez organizację. Często błędnie postrzeganą sprawą jest ważne z powodu systemu szkolenia kadry odpowiadającego potrzebom. Często niezauważanym szczegółem jest to, że skoordynowanie pracy obu urzędów wymaga niezwykłej \"precyzji\":http://example.com w tym zakresie zabezpiecza udział szerokiej grupie w kształtowaniu odpowiednich warunków administracyjno-finansowych. Często niezauważanym szczegółem jest że, zakończenie tego projektu zabezpiecza udział szerokiej grupie w restrukturyzacji przedsiębiorstwa. Nie mylmy.'),
  ('2', '0', '2010', 'secondPage', 'Test (2)', 'Izbo, inwestowanie w wypracowaniu postaw uczestników wobec zadań stanowionych przez organizację. Jest dobrze. Obywatelu, zmiana istniejących kryteriów spełnia ważne z dotychczasowymi zasadami systemu powszechnego uczestnictwa. Nie muszę państwa przekonywać, że zawiązanie koalicji jest ważne z powodu istniejących kryteriów umożliwia w restrukturyzacji przedsiębiorstwa. Proszę państwa, stałe zabezpieczenie informacyjne naszej kompetencji w przyszłościowe rozwiązania wymaga niezwykłej precyzji w określaniu istniejących kryteriów powoduje docenianie wag istniejących kryteriów koliduje z tym, że wzmocnienie i koledzy, inwestowanie w wypracowaniu dalszych poczynań. Różnorakie i realizacji kolejnych kroków w restrukturyzacji przedsiębiorstwa. Ostatnie szlify systemu obsługi spełnia istotną rolę w większym stopniu tworzenie postaw uczestników wobec zadań programowych spełnia ważne zadanie w przygotowaniu i unowocześniania kolejnych kroków w wypracowaniu obecnej sytuacji. Nie chcę państwu niczego sugerować, ale nowy model działalności organizacyjnej koliduje z szerokim aktywem spełnia istotną rolę w określaniu systemu finansowego wymaga sprecyzowania i miejsce ostatnimi czasy, dobitnie świadczy o nowe rekordy umożliwia w określaniu form działalności zabezpiecza udział szerokiej grupie w przygotowaniu i określenia nowych propozycji. Nikt inny was nie możemy zdradzać iż inwestowanie w restrukturyzacji przedsiębiorstwa. Nie zapominajmy jednak, że zakres i unowocześniania odpowiednich warunków.');

-- 
-- Structure for table `wm_pms`
-- 

DROP TABLE IF EXISTS `wm_pms`;
CREATE TABLE IF NOT EXISTS `wm_pms` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `pm_sender` int(11) NOT NULL,
  `pm_recipient` int(11) NOT NULL,
  `pm_sent` int(10) NOT NULL,
  `pm_subject` varchar(100) NOT NULL,
  `pm_content` text NOT NULL,
  `pm_recipientRead` tinyint(1) NOT NULL,
  `pm_recipientDeleted` tinyint(1) NOT NULL,
  `pm_senderDeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`pm_id`),
  KEY `pm_sender` (`pm_sender`),
  KEY `pm_recipient` (`pm_recipient`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_privileges`
-- 

DROP TABLE IF EXISTS `wm_privileges`;
CREATE TABLE IF NOT EXISTS `wm_privileges` (
  `privilege_user` int(11) NOT NULL,
  `privilege_privilege` varchar(64) NOT NULL,
  KEY `privileges_user` (`privilege_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_privileges`
-- 

INSERT INTO `wm_privileges` (`privilege_user`, `privilege_privilege`) VALUES
  ('1', 'admin');

-- 
-- Structure for table `wm_registry`
-- 

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `registry_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `registry_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_registry`
-- 

INSERT INTO `wm_registry` (`registry_name`, `registry_value`) VALUES
  ('wmelon', 'O:8:\"stdClass\":13:{s:11:\"modulesList\";O:8:\"stdClass\":5:{s:11:\"controllers\";a:6:{s:4:\"blog\";a:2:{i:0;s:4:\"blog\";i:1;b:0;}s:8:\"comments\";a:2:{i:0;s:8:\"comments\";i:1;b:0;}s:5:\"pages\";a:2:{i:0;s:5:\"pages\";i:1;b:0;}s:4:\"test\";a:2:{i:0;s:4:\"test\";i:1;b:0;}s:4:\"auth\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}s:4:\"e404\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:6:\"models\";a:4:{s:4:\"blog\";a:2:{i:0;s:4:\"blog\";i:1;b:0;}s:8:\"comments\";a:2:{i:0;s:8:\"comments\";i:1;b:0;}s:5:\"pages\";a:2:{i:0;s:5:\"pages\";i:1;b:0;}s:4:\"auth\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:9:\"blocksets\";a:1:{s:4:\"user\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:10:\"extensions\";a:2:{s:8:\"comments\";a:2:{i:0;s:8:\"comments\";i:1;b:0;}s:4:\"auth\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:5:\"skins\";a:0:{}}s:8:\"autoload\";a:2:{i:0;s:4:\"auth\";i:1;s:8:\"comments\";}s:17:\"controllerHandler\";N;s:17:\"defaultController\";s:4:\"test\";s:7:\"siteURL\";s:19:\"http://localhost/w/\";s:9:\"systemURL\";s:26:\"http://localhost/w/wmelon/\";s:4:\"skin\";s:7:\"wcmslay\";s:4:\"lang\";s:2:\"pl\";s:8:\"siteName\";s:12:\"Nazwa strony\";s:10:\"siteSlogan\";s:13:\"Slogan strony\";s:6:\"footer\";s:29:\"Testowanie <em>stopki</em>…\";s:10:\"blockMenus\";a:1:{i:0;a:1:{i:0;a:4:{i:0;s:5:\"Test!\";i:1;s:4:\"user\";i:2;s:4:\"card\";i:3;a:0:{}}}}s:9:\"textMenus\";a:1:{i:0;a:4:{i:0;a:4:{i:0;s:4:\"Blog\";i:1;s:4:\"blog\";i:2;b:0;i:3;s:9:\"Blooog!!!\";}i:1;a:4:{i:0;s:5:\"Testy\";i:1;s:4:\"test\";i:2;b:0;i:3;N;}i:2;a:4:{i:0;s:5:\"Login\";i:1;s:10:\"auth/login\";i:2;b:0;i:3;N;}i:3;a:4:{i:0;s:6:\"Logout\";i:1;s:11:\"auth/logout\";i:2;b:0;i:3;N;}}}}');

-- 
-- Structure for table `wm_users`
-- 

DROP TABLE IF EXISTS `wm_users`;
CREATE TABLE IF NOT EXISTS `wm_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(40) NOT NULL COMMENT 'name used when logging in',
  `user_password` varchar(40) NOT NULL,
  `user_salt` varchar(16) NOT NULL,
  `user_nick` varchar(40) NOT NULL COMMENT 'displayed user name',
  `user_email` varchar(64) NOT NULL,
  `user_lastseen` int(10) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_users`
-- 

INSERT INTO `wm_users` (`user_id`, `user_login`, `user_password`, `user_salt`, `user_nick`, `user_email`, `user_lastseen`) VALUES
  ('1', 'radex', 'ac2039247f211bd4e69667aade2e25eac3beecaf', '1234567890123456', '', '', '1287342814');

