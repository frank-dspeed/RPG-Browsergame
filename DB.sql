-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Nov 2020 um 14:20
-- Server-Version: 5.5.60-0+deb7u1
-- PHP-Version: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ni1099597_2sql4`
--
CREATE DATABASE IF NOT EXISTS `ni1099597_2sql4` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `ni1099597_2sql4`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gegner`
--

CREATE TABLE `gegner` (
  `gegnerid` int(11) NOT NULL,
  `gegnername` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lvl` int(11) NOT NULL,
  `leben` int(11) NOT NULL,
  `angriff` int(11) NOT NULL,
  `geld` int(11) NOT NULL,
  `gegnerbildpfad` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `thema` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gesperrt` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `gegner`
--

INSERT INTO `gegner` (`gegnerid`, `gegnername`, `lvl`, `leben`, `angriff`, `geld`, `gegnerbildpfad`, `thema`, `gesperrt`) VALUES
(3, 'Knaugsch', 1, 0, 1, 0, '/Gegneravatare/Knaugsch202011041604526114.png', 'Piraten', 0),
(4, 'Rextor', 1, 0, 5, 0, '/Gegneravatare/Rextor202011051604609383.png', 'Orclager', 0),
(5, 'Trax', 5, 50, 5, 40, '/Gegneravatare/Trax202011081604875838.png', 'Orclager', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konto`
--

CREATE TABLE `konto` (
  `id` int(11) NOT NULL,
  `benutzername` varchar(30) CHARACTER SET utf8 NOT NULL,
  `passwort` text CHARACTER SET utf8 NOT NULL,
  `erstellt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `konto`
--

INSERT INTO `konto` (`id`, `benutzername`, `passwort`, `erstellt`) VALUES
(8, 'Hans', '$2y$10$/MEbpgdBrwZ6CG4pgqE.BuRFxg791ky5k0xuRTV7bObt96Bqyzjke', '2020-06-07 14:22:46'),
(9, 'Ben', '$2y$10$lyfve25GLqCea.WP7UUnkeebTTc9gc6S9lLrl1N8bUwp1vgswgBpO', '2020-06-07 17:10:35'),
(10, 'Bernd', '$2y$10$FWwZW7Av5tWw/L7PumNnxO8qOLt8mXOsY.IcU9FLvNP95EAJSFCAK', '2020-06-07 17:12:24'),
(11, 'Test', '$2y$10$Pelbkg1WEvh/wEgPK1gdq.IRXDRxOt190R9RqTV6FeMnx1GmyVq.K', '2020-06-07 17:21:58'),
(18, 'Tester5', '$2y$10$v5j0UNvMeND0l6Wc0sZ.L.VXHsCUf67I.q95g3Vy.aQ3UG0GMiBZi', '2020-11-08 20:59:24'),
(19, 'Tester1', '$2y$10$JJBUbRztE.zpbv.ZZ/jZP.DXMFzQ4VDS0O9oMlm74G5UYdSs6QKm.', '2020-11-08 21:01:40'),
(20, 'Tester2', '$2y$10$.zoLQPb0S4TdU5/OnEllFuTkgdsOH/Mo3aAORUoZ67JKxzMJMcjbK', '2020-11-08 21:02:39'),
(21, 'Tester3', '$2y$10$blL81FD0VkLDxsxBcJqh2eCRW.VEj8bwi.Au3CsOqfeIfJZ4pubuC', '2020-11-08 21:05:17'),
(22, 'Tester', '$2y$10$Dl8V.dj9.Ypnd8R3gcHAZOxv9Rfr1veFyyuEu1Mq3lD0LLlHUbcfG', '2020-11-14 20:29:51'),
(23, 'BenusHonus', '$2y$10$pD0FB2debz/GjaRbHNYy4.DzVMLlmnkNeZw86R4olbFQITaaZga7a', '2020-11-24 20:03:53');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ruestung`
--

CREATE TABLE `ruestung` (
  `ruestungsid` int(11) NOT NULL,
  `ruestungsname` text NOT NULL,
  `ruestungswert` int(11) NOT NULL,
  `geldwert` int(11) NOT NULL,
  `ruestungsbildpfad` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `ruestung`
--

INSERT INTO `ruestung` (`ruestungsid`, `ruestungsname`, `ruestungswert`, `geldwert`, `ruestungsbildpfad`) VALUES
(0, 'Lumpen', 0, 0, '/Ruestungsbilder/Ruestung_Default.png'),
(1, 'Holzruestung', 1, 5, '/Ruestungsbilder/Holzruestung.png'),
(2, 'Goldruestung', 10, 10, '/Ruestungsbilder/Goldruestung.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spieler`
--

CREATE TABLE `spieler` (
  `spielername` varchar(30) CHARACTER SET utf8 NOT NULL,
  `lvl` int(11) NOT NULL,
  `erfahrung` int(11) NOT NULL,
  `geld` int(11) NOT NULL,
  `leben` int(11) NOT NULL,
  `maxleben` int(11) NOT NULL,
  `angriff` int(11) NOT NULL,
  `waffenid` int(11) DEFAULT NULL,
  `ruestungsid` int(11) DEFAULT NULL,
  `spielerbildpfad` text CHARACTER SET utf8,
  `rechte` text CHARACTER SET utf8 COLLATE utf8_bin,
  `gesperrt` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `spieler`
--

INSERT INTO `spieler` (`spielername`, `lvl`, `erfahrung`, `geld`, `leben`, `maxleben`, `angriff`, `waffenid`, `ruestungsid`, `spielerbildpfad`, `rechte`, `gesperrt`) VALUES
('Ben', 1, 0, 10, 10, 10, 1, 1, 1, '/Spieleravatare/Default.png', 'Spieler', 0),
('BenusHonus', 1, 0, 0, 3, 3, 1, 0, 0, '/Spieleravatare/Default.png', 'Spieler', 0),
('Bernd', 3, 0, 5, 0, 2, 0, 0, 0, '/Spieleravatare/Default.png', 'Spieler', 0),
('Hans', 2, 39, 60, 10, 13, 2, 6, 2, '/Spieleravatare/Hans.png', 'Admin', 0),
('Test', 3, 0, 5, 0, 2, 0, 0, 0, '/Spieleravatare/Default.png', 'Spieler', 0),
('Tester', 3, 0, 5, 0, 2, 0, 0, 0, '/Spieleravatare/Default.png', 'Spieler', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `themen`
--

CREATE TABLE `themen` (
  `id` int(11) NOT NULL,
  `themenname` text COLLATE utf8_bin NOT NULL,
  `themenbildpfad` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `themen`
--

INSERT INTO `themen` (`id`, `themenname`, `themenbildpfad`) VALUES
(1, 'Orclager', '/Bilder/Geldsack.png'),
(2, 'Piraten', '/Bilder/Marktplatzbutton.png'),
(7, 'Banditen', '/Themenbilder/Banditen202011041604530378.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `traenke`
--

CREATE TABLE `traenke` (
  `trankid` int(11) NOT NULL,
  `trankname` text CHARACTER SET utf8 NOT NULL,
  `trankwert` int(11) NOT NULL,
  `trankwertpermanent` int(11) NOT NULL,
  `geldwert` int(11) NOT NULL,
  `trankbildpfad` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `traenke`
--

INSERT INTO `traenke` (`trankid`, `trankname`, `trankwert`, `trankwertpermanent`, `geldwert`, `trankbildpfad`) VALUES
(3, 'Kleiner Permatrank', 0, 5, 100, '/Traenkebilder/KleinerPermatrank.png'),
(4, 'KleinerHeiltrank', 10, 0, 10, '/Traenkebilder/KleinerHeiltrank202011051604617069.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `waffen`
--

CREATE TABLE `waffen` (
  `waffenid` int(11) NOT NULL,
  `waffenname` text CHARACTER SET utf8 NOT NULL,
  `waffenwert` int(11) NOT NULL,
  `geldwert` int(11) NOT NULL,
  `waffenbildpfad` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `waffen`
--

INSERT INTO `waffen` (`waffenid`, `waffenname`, `waffenwert`, `geldwert`, `waffenbildpfad`) VALUES
(0, 'Faust', 0, 0, '/Waffenbilder/Waffe_Default.png'),
(1, 'Ast', 1, 1, '/Waffenbilder/Ast.png'),
(2, 'Nudelholz', 2, 3, '/Waffenbilder/Nudelholz.png'),
(6, 'MegaAst', 5, 10, '/Waffenbilder/MegaAst202011051604612881.png'),
(8, 'Zerstoerer', 5, 50, '/Waffenbilder/Zerstoerer202011281606571907.png');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `gegner`
--
ALTER TABLE `gegner`
  ADD PRIMARY KEY (`gegnerid`);

--
-- Indizes für die Tabelle `konto`
--
ALTER TABLE `konto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `benutzername` (`benutzername`);

--
-- Indizes für die Tabelle `ruestung`
--
ALTER TABLE `ruestung`
  ADD PRIMARY KEY (`ruestungsid`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`spielername`);

--
-- Indizes für die Tabelle `themen`
--
ALTER TABLE `themen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `traenke`
--
ALTER TABLE `traenke`
  ADD PRIMARY KEY (`trankid`);

--
-- Indizes für die Tabelle `waffen`
--
ALTER TABLE `waffen`
  ADD PRIMARY KEY (`waffenid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `gegner`
--
ALTER TABLE `gegner`
  MODIFY `gegnerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `konto`
--
ALTER TABLE `konto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `ruestung`
--
ALTER TABLE `ruestung`
  MODIFY `ruestungsid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `themen`
--
ALTER TABLE `themen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `traenke`
--
ALTER TABLE `traenke`
  MODIFY `trankid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `waffen`
--
ALTER TABLE `waffen`
  MODIFY `waffenid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
