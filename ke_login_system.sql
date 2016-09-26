-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2016. Sze 26. 08:12
-- Szerver verzió: 5.5.51-cll-lve
-- PHP verzió: 5.6.25

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `kenarios_ci_login`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_captcha`
--

CREATE TABLE IF NOT EXISTS `ke_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_languages`
--

CREATE TABLE IF NOT EXISTS `ke_languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `image` varchar(64) NOT NULL,
  `directory` varchar(32) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `language_id` (`language_id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `directory` (`directory`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `ke_languages`
--

INSERT INTO `ke_languages` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`) VALUES
(1, 'Magyar', 'hu', 'hu_HU.UTF-8,hu_HU,hu-hu,hungarian', 'hu.png', 'hungarian', 1, 1),
(2, 'English', 'en', 'en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 2, 1),
(3, 'Romana', 'ro', 'ro_RO.UTF8', 'ro.png', 'romanian', 3, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_login_attempts`
--

CREATE TABLE IF NOT EXISTS `ke_login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` int(11) NOT NULL,
  `ip_16` int(11) NOT NULL,
  `ip_24` int(11) NOT NULL,
  `login` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_sessions`
--

CREATE TABLE IF NOT EXISTS `ke_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ke_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_settings`
--

CREATE TABLE IF NOT EXISTS `ke_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_id` (`setting_id`),
  UNIQUE KEY `key` (`key`),
  KEY `setting_id_2` (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `ke_settings`
--

INSERT INTO `ke_settings` (`setting_id`, `code`, `key`, `value`) VALUES
(1, 'config', 'config_base_url', 'http://login-system.kenariosz.hu/'),
(2, 'config', 'config_template_name', 'default'),
(3, 'config', 'config_language', 'hungarian');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_summarised_login_attempts`
--

CREATE TABLE IF NOT EXISTS `ke_summarised_login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `all` varchar(15) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `ip_16` varchar(15) NOT NULL,
  `ip_24` varchar(15) NOT NULL,
  `user` varchar(255) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához: `ke_users`
--

CREATE TABLE IF NOT EXISTS `ke_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
