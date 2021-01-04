-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 21, 2020 at 11:05 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `company_manager`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `duzeImieNazwisko` ()    BEGIN
	 UPDATE uzytkownicy SET 
          imie=CONCAT(UPPER(LEFT(imie, 1)), RIGHT(imie, LENGTH(imie)-1));
          UPDATE uzytkownicy SET
          nazwisko=CONCAT(UPPER(LEFT(nazwisko, 1)), RIGHT(nazwisko, LENGTH(nazwisko)-1));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dokumenty`
--

CREATE TABLE `dokumenty` (
    `id` int(11) NOT NULL,
    `data` date NOT NULL,
    `l_stron` int(11) NOT NULL,
    `notatki` text NOT NULL,
    `zdjecie_dokumentu` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faktury`
--

CREATE TABLE `faktury` (
    `id` int(11) NOT NULL,
    `nr_faktury` text NOT NULL,
    `netto` decimal(11,0) NOT NULL,
    `vat` int(11) NOT NULL,
    `brutto` decimal(11,0) NOT NULL,
    `waluta` int(11) NOT NULL,
    `kontrahent_id` int(11) NOT NULL,
    `zdjecie_faktury` blob,
    `rodzaj` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kategorie_faktur`
--

CREATE TABLE `kategorie_faktur` (
    `id` int(11) NOT NULL,
    `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategorie_faktur`
--

INSERT INTO `kategorie_faktur` (`id`, `nazwa`) VALUES
(1, 'sprzedaz'),
(2, 'zakup');

-- --------------------------------------------------------

--
-- Table structure for table `kontrahenci`
--

CREATE TABLE `kontrahenci` (
    `id` int(11) NOT NULL,
    `nazwa` text NOT NULL,
    `vat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `licencje`
--

CREATE TABLE `licencje` (
    `id` int(11) NOT NULL,
    `nr_inwentarzowy` int(11) NOT NULL,
    `nazwa` text NOT NULL,
    `opis` text,
    `klucz_seryjny` text NOT NULL,
    `data_zakupu` date NOT NULL,
    `id_faktury` int(11) NOT NULL,
    `wsparcie_do` date NOT NULL,
    `licencja_do` date NOT NULL,
    `notatki` text,
    `id_wlasciciela` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
    `id` int(11) NOT NULL,
    `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `nazwa`) VALUES
(1, 'wlasciciel'),
(2, 'pracownik'),
(3, 'auditor');

-- --------------------------------------------------------

--
-- Table structure for table `sprzety`
--

CREATE TABLE `sprzety` (
    `id` int(11) NOT NULL,
    `nr_inwentarzowy` int(11) NOT NULL,
    `nazwa` text NOT NULL,
    `opis` text,
    `nr_seryjny` text NOT NULL,
    `data_zakupu` date NOT NULL,
    `nr_faktury` int(11) NOT NULL,
    `gwarancja_do` date NOT NULL,
    `netto_pl` decimal(10,0) NOT NULL,
    `notatki` text,
    `id_wlasciciela` int(11) NOT NULL,
    `netto` decimal(10,0) NOT NULL,
    `waluta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
    `id` int(11) NOT NULL,
    `imie` text NOT NULL,
    `nazwisko` text NOT NULL,
    `login` text NOT NULL,
    `haslo` text NOT NULL,
    `id_roli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `id_roli`) VALUES
(1, 'Kokos', 'Asowy', 'kokosik123', '$2y$10$TPQH6Dm9lfLjUwhT4s6y4uHrS7ogsJQJOHdVeHhedJSZHzeIziXei', 2),
(6, 'Patrycja', 'Nowak', 'patrycja123', '$2y$10$RXBpVYFXvpZ5iTNo71AnluDppV95ncx7s1O..h9W5NxYrywhOPHEa', 1),
(7, 'Katarzyna', 'Kowalska', 'kasia123', '$2y$10$Q/CpMLr7GydzcNgLxMo7gurimnit.2kRhLapl5zD9dCnqDsD7uRgC', 3),
(8, 'Marta', 'Karta', 'marta123', '$2y$10$AcSxYkJEgW57ja7s6yw1uOTXQFoYswFuOrQHaEsfoRFi3Sgv7zP/2', 2),
(14, 'Dominika', 'Kostecka', 'dominika123', '$2y$10$.6wh41apSfZCBSOEc8om7OZhhd2yMyz5aNnbCcHDaWPZB70A2amR6', 2),
(15, 'Adam', 'Nowicki', 'adam1234', '$2y$10$QJWqLErINE5x1kMzMzQy9etknMvdFAfKnFlKvsirWbbiiDFueQWHm', 2),
(16, 'Marcel', 'Iwanicki', 'marcel123', '$2y$10$m2.jfgySZwKCfEklw5DA6ekJg/bORl4m/wJZf8vj0T1wjI0mBFVJq', 2);

-- --------------------------------------------------------

--
-- Table structure for table `waluty`
--

CREATE TABLE `waluty` (
    `id` int(11) NOT NULL,
    `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `waluty`
--

INSERT INTO `waluty` (`id`, `nazwa`) VALUES
(1, 'PLN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumenty`
--
ALTER TABLE `dokumenty`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faktury`
--
ALTER TABLE `faktury`
    ADD PRIMARY KEY (`id`),
    ADD KEY `kontrahent_id` (`kontrahent_id`),
    ADD KEY `waluta` (`waluta`),
    ADD KEY `rodzaj` (`rodzaj`);

--
-- Indexes for table `kategorie_faktur`
--
ALTER TABLE `kategorie_faktur`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontrahenci`
--
ALTER TABLE `kontrahenci`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `licencje`
--
ALTER TABLE `licencje`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_faktury` (`id_faktury`),
    ADD KEY `id_wlasciciela` (`id_wlasciciela`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sprzety`
--
ALTER TABLE `sprzety`
    ADD PRIMARY KEY (`id`),
    ADD KEY `nr_faktury` (`nr_faktury`),
    ADD KEY `id_wlasciciela` (`id_wlasciciela`),
    ADD KEY `waluta` (`waluta`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_roli` (`id_roli`);

--
-- Indexes for table `waluty`
--
ALTER TABLE `waluty`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumenty`
--
ALTER TABLE `dokumenty`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faktury`
--
ALTER TABLE `faktury`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategorie_faktur`
--
ALTER TABLE `kategorie_faktur`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kontrahenci`
--
ALTER TABLE `kontrahenci`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `licencje`
--
ALTER TABLE `licencje`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sprzety`
--
ALTER TABLE `sprzety`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `waluty`
--
ALTER TABLE `waluty`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faktury`
--
ALTER TABLE `faktury`
    ADD CONSTRAINT `faktury_ibfk_1` FOREIGN KEY (`kontrahent_id`) REFERENCES `kontrahenci` (`id`),
    ADD CONSTRAINT `faktury_ibfk_2` FOREIGN KEY (`waluta`) REFERENCES `waluty` (`id`),
    ADD CONSTRAINT `faktury_ibfk_3` FOREIGN KEY (`rodzaj`) REFERENCES `kategorie_faktur` (`id`);

--
-- Constraints for table `licencje`
--
ALTER TABLE `licencje`
    ADD CONSTRAINT `licencje_ibfk_1` FOREIGN KEY (`id_faktury`) REFERENCES `faktury` (`id`),
    ADD CONSTRAINT `licencje_ibfk_2` FOREIGN KEY (`id_wlasciciela`) REFERENCES `uzytkownicy` (`id`);

--
-- Constraints for table `sprzety`
--
ALTER TABLE `sprzety`
    ADD CONSTRAINT `sprzety_ibfk_1` FOREIGN KEY (`nr_faktury`) REFERENCES `faktury` (`id`),
    ADD CONSTRAINT `sprzety_ibfk_2` FOREIGN KEY (`id_wlasciciela`) REFERENCES `uzytkownicy` (`id`),
    ADD CONSTRAINT `sprzety_ibfk_3` FOREIGN KEY (`waluta`) REFERENCES `waluty` (`id`);

--
-- Constraints for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
    ADD CONSTRAINT `uzytkownicy_ibfk_1` FOREIGN KEY (`id_roli`) REFERENCES `role` (`id`);