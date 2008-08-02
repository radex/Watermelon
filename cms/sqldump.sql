# --------------------------------------------------------
#
# Struktura tabeli dla 'groups'
#

CREATE TABLE groups (
   id smallint(3) unsigned NOT NULL auto_increment,
   name varchar(30) NOT NULL,
   users text NOT NULL,
   style tinyint(1) unsigned NOT NULL,
   PRIMARY KEY (id)
);

#
# Zawartosc tabeli 'groups'
#


# --------------------------------------------------------
#
# Struktura tabeli dla 'menu'
#

CREATE TABLE menu (
   menu_id smallint(5) unsigned NOT NULL auto_increment,
   menu_capt varchar(100) NOT NULL,
   menu_content text NOT NULL,
   menu_if text NOT NULL,
   PRIMARY KEY (menu_id)
);

#
# Zawartosc tabeli 'menu'
#

INSERT INTO menu VALUES ( '1', 'Menu', 'Testowe menu. <? echo site_url(\'/\') ?><hr>lorem ipsum', '');
INSERT INTO menu VALUES ( '2', 'Kolejne menu', 'hehe ;)', '');

# --------------------------------------------------------
#
# Struktura tabeli dla 'pages'
#

CREATE TABLE pages (
   id smallint(5) unsigned NOT NULL auto_increment,
   name varchar(255) NOT NULL,
   content text NOT NULL,
   title varchar(255) NOT NULL,
   PRIMARY KEY (id)
);

#
# Zawartosc tabeli 'pages'
#

INSERT INTO pages VALUES ( '22', 'testowa/stronka', 'Heh elo no :D <strong>test</strong>

<?php
echo microtime();
?>

<?=microtime()?>

<?microtime()?>

<?
$a = \'!kemoiz ameiS\';
echo strrev($a);
?>

;)', 'Elo heh');

# --------------------------------------------------------
#
# Struktura tabeli dla 'privileges'
#

CREATE TABLE privileges (
   id smallint(2) unsigned NOT NULL auto_increment,
   PRIMARY KEY (id)
);

#
# Zawartosc tabeli 'privileges'
#


# --------------------------------------------------------
#
# Struktura tabeli dla 'users'
#

CREATE TABLE users (
   id int(5) unsigned NOT NULL auto_increment,
   nick varchar(32) NOT NULL,
   password varchar(255) NOT NULL,
   hashalgo tinyint(1) unsigned DEFAULT '0' NOT NULL,
   salt varchar(16) NOT NULL,
   ingroups varchar(255) NOT NULL,
   privileges tinyint(2) unsigned DEFAULT '0' NOT NULL,
   PRIMARY KEY (id)
);

#
# Zawartosc tabeli 'users'
#

