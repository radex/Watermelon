-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 10 Sie 2010, 19:32
-- Wersja serwera: 5.1.37
-- Wersja PHP: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `watermelon`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `wm_registry`
--

CREATE TABLE `wm_registry` (
  `registry_name` varchar(256) NOT NULL,
  `registry_value` text NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `wm_registry`
--

