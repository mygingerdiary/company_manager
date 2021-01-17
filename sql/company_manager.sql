-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 17 Sty 2021, 19:47
-- Wersja serwera: 10.4.14-MariaDB
-- Wersja PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `company_manager`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `duzeImieNazwisko` ()  BEGIN
	 UPDATE uzytkownicy SET 
     imie=CONCAT(UPPER(LEFT(imie, 1)), RIGHT(imie, LENGTH(imie)-1));
     UPDATE uzytkownicy SET
     nazwisko=CONCAT(UPPER(LEFT(nazwisko, 1)), RIGHT(nazwisko, LENGTH(nazwisko)-1));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dokumenty`
--

CREATE TABLE `dokumenty` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `l_stron` int(11) NOT NULL,
  `notatki` text NOT NULL,
  `zdjecie_dokumentu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `dokumenty`
--

INSERT INTO `dokumenty` (`id`, `data`, `l_stron`, `notatki`, `zdjecie_dokumentu`) VALUES
(2, '2021-01-04', 20, 'adsadadawdaw', 'testpdf.pdf'),
(3, '2021-01-05', 21, 'jhgdjhgjhg', 'img_lights.jpg'),
(5, '2021-01-15', 10, 'czczczcczcxzcx', 'testpdf.pdf'),
(6, '2020-09-17', 2, 'notatka', 'testpdf.pdf'),
(7, '2021-01-10', 1, 'ważne', 'phonepicutres-TA.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `faktury`
--

CREATE TABLE `faktury` (
  `id` int(11) NOT NULL,
  `rodzaj` int(11) NOT NULL,
  `nr_faktury` text NOT NULL,
  `netto_pln` decimal(10,0) NOT NULL,
  `netto` decimal(11,0) NOT NULL,
  `waluta` int(11) NOT NULL,
  `brutto` decimal(11,0) NOT NULL,
  `vat` int(11) NOT NULL,
  `kontrahent_id` int(11) NOT NULL,
  `id_dokumentu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `faktury`
--

INSERT INTO `faktury` (`id`, `rodzaj`, `nr_faktury`, `netto_pln`, `netto`, `waluta`, `brutto`, `vat`, `kontrahent_id`, `id_dokumentu`) VALUES
(23, 1, '6776', '200', '200', 1, '220', 7, 18, NULL),
(24, 2, '444', '433', '433', 2, '441', 8, 1, 2),
(25, 1, '765', '877', '301', 2, '321', 8, 19, 3),
(26, 2, '334', '334', '334', 1, '356', 8, 1, 5),
(27, 1, '546', '1025', '200', 1, '228', 23, 20, 7),
(28, 2, '6776', '2557', '2557', 1, '2757', 23, 20, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie_faktur`
--

CREATE TABLE `kategorie_faktur` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `kategorie_faktur`
--

INSERT INTO `kategorie_faktur` (`id`, `nazwa`) VALUES
(1, 'sprzedaz'),
(2, 'zakup');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontrahenci`
--

CREATE TABLE `kontrahenci` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `vat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `kontrahenci`
--

INSERT INTO `kontrahenci` (`id`, `nazwa`, `vat_id`) VALUES
(1, 'nazwa', 123),
(2, 'nowy', 333),
(3, 'nowszy', 999),
(4, 'kokosik', 666),
(5, 'najnowszy', 555),
(6, 'kontrahent7', 777),
(7, 'najnowszyyy', 0),
(8, 'jjj', 12),
(9, 'dupa', 888),
(10, 'ppp', 877),
(11, 'oiuoiu', 876),
(12, 'llkj', 2137),
(13, 'mmm', 4322),
(14, 'fsdfds', 654),
(15, 'gsgsdgsd', 234),
(16, 'jjjjjjj', 543),
(17, 'kkkk', 6),
(18, 'dasdas', 32412421),
(19, 'dsadsa', 9),
(20, 'mon', 23);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `licencje`
--

CREATE TABLE `licencje` (
  `id` int(11) NOT NULL,
  `nr_inwentarzowy` text NOT NULL,
  `nazwa` text NOT NULL,
  `opis` text DEFAULT NULL,
  `klucz_seryjny` text NOT NULL,
  `data_zakupu` date NOT NULL,
  `id_faktury` int(11) DEFAULT NULL,
  `wsparcie_do` date NOT NULL,
  `licencja_do` date DEFAULT NULL,
  `notatki` text DEFAULT NULL,
  `id_wlasciciela` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `licencje`
--

INSERT INTO `licencje` (`id`, `nr_inwentarzowy`, `nazwa`, `opis`, `klucz_seryjny`, `data_zakupu`, `id_faktury`, `wsparcie_do`, `licencja_do`, `notatki`, `id_wlasciciela`) VALUES
(3, 'numer', 'nazwa', '', 'klucz', '2021-01-07', NULL, '2021-01-23', NULL, '', 1),
(4, '2', 'licencja1', 'licencja1', 'ABC-823', '2021-01-17', 24, '2021-04-30', NULL, '', 24),
(5, '12', 'licencja2', 'licencja2', 'XYZ-213', '2021-01-05', 24, '2021-02-28', '2021-02-28', 'tescik', 8);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `role`
--

INSERT INTO `role` (`id`, `nazwa`) VALUES
(1, 'wlasciciel'),
(2, 'pracownik'),
(3, 'auditor');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sprzety`
--

CREATE TABLE `sprzety` (
  `id` int(11) NOT NULL,
  `nr_inwentarzowy` text NOT NULL,
  `nazwa` text NOT NULL,
  `opis` text DEFAULT NULL,
  `nr_seryjny` text NOT NULL,
  `data_zakupu` date NOT NULL,
  `nr_faktury` int(11) DEFAULT NULL,
  `gwarancja_do` date NOT NULL,
  `netto_pln` decimal(10,0) NOT NULL,
  `notatki` text DEFAULT NULL,
  `id_wlasciciela` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `sprzety`
--

INSERT INTO `sprzety` (`id`, `nr_inwentarzowy`, `nazwa`, `opis`, `nr_seryjny`, `data_zakupu`, `nr_faktury`, `gwarancja_do`, `netto_pln`, `notatki`, `id_wlasciciela`) VALUES
(2, '1', 'blabla', 'blabla', '12321', '2021-01-08', 24, '2021-02-28', '201', '', 14),
(3, 'numer1', 'numer', '', '75984', '2021-01-06', 24, '2021-05-30', '550', 'blabla', 6),
(4, '5555', 'dsdfdsf', '', '213ffa', '2021-01-13', 25, '2021-02-07', '278', 'test', 25);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
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
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `id_roli`) VALUES
(1, 'Kokos', 'Asowy', 'kokosik123', '$2y$10$TPQH6Dm9lfLjUwhT4s6y4uHrS7ogsJQJOHdVeHhedJSZHzeIziXei', 2),
(6, 'Patrycja', 'Nowak', 'patrycja123', '$2y$10$RXBpVYFXvpZ5iTNo71AnluDppV95ncx7s1O..h9W5NxYrywhOPHEa', 1),
(7, 'Katarzyna', 'Kowalska', 'kasia123', '$2y$10$Q/CpMLr7GydzcNgLxMo7gurimnit.2kRhLapl5zD9dCnqDsD7uRgC', 3),
(8, 'Marta', 'Karta', 'marta123', '$2y$10$AcSxYkJEgW57ja7s6yw1uOTXQFoYswFuOrQHaEsfoRFi3Sgv7zP/2', 2),
(14, 'Dominika', 'Kostecka', 'dominika123', '$2y$10$.6wh41apSfZCBSOEc8om7OZhhd2yMyz5aNnbCcHDaWPZB70A2amR6', 2),
(15, 'Adam', 'Nowicki', 'adam1234', '$2y$10$QJWqLErINE5x1kMzMzQy9etknMvdFAfKnFlKvsirWbbiiDFueQWHm', 2),
(16, 'Marcel', 'Iwanicki', 'marcel123', '$2y$10$m2.jfgySZwKCfEklw5DA6ekJg/bORl4m/wJZf8vj0T1wjI0mBFVJq', 2),
(20, 'izabela', 'łęcka', 'izabela123', '$2y$10$1B30tHIOZGP1.uWBoMjJ/ehgRS4hmQrd3Zj/DEi9FgrDpGmAyAaqm', 3),
(21, 'Paweł', 'Stępnik', 'pawel123', '$2y$10$gew.Ol1J1TSOEw7Z0Dtedu6WIba2hDOLC5XCXZimxah5g1pYxXesO', 3),
(24, 'Luka', 'Lolo', 'luka1234', '$2y$10$Pi5xtpkKdF.JF2Cjvu42deUocqfGOSoQNnoCPsa7qfdNJZ5s8r6Yq', 1),
(25, 'Łukasz', 'Ąę', 'lukasz1234', '$2y$10$8rV7b19ASok2iZXw2PLVwukyRTvlb2qzK2hEXISgcfLqk192lMrS2', 2),
(26, 'Paweł', 'Kowalski', 'pawel12345', '$2y$10$30rF.Ap1tPvcM1/KpnwFbeEKyBVDqn4JPPvFAMDEWBJ4KBRJ3WOl.', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `waluty`
--

CREATE TABLE `waluty` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `waluty`
--

INSERT INTO `waluty` (`id`, `nazwa`) VALUES
(1, 'PLN'),
(2, 'USD'),
(3, 'EUR'),
(4, 'GBP'),
(5, 'JPY');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dokumenty`
--
ALTER TABLE `dokumenty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `faktury`
--
ALTER TABLE `faktury`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kontrahent_id` (`kontrahent_id`),
  ADD KEY `waluta` (`waluta`),
  ADD KEY `rodzaj` (`rodzaj`),
  ADD KEY `id_dokumentu` (`id_dokumentu`);

--
-- Indeksy dla tabeli `kategorie_faktur`
--
ALTER TABLE `kategorie_faktur`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `kontrahenci`
--
ALTER TABLE `kontrahenci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `licencje`
--
ALTER TABLE `licencje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_faktury` (`id_faktury`),
  ADD KEY `id_wlasciciela` (`id_wlasciciela`);

--
-- Indeksy dla tabeli `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `sprzety`
--
ALTER TABLE `sprzety`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nr_faktury` (`nr_faktury`),
  ADD KEY `id_wlasciciela` (`id_wlasciciela`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_roli` (`id_roli`);

--
-- Indeksy dla tabeli `waluty`
--
ALTER TABLE `waluty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `dokumenty`
--
ALTER TABLE `dokumenty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `faktury`
--
ALTER TABLE `faktury`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT dla tabeli `kategorie_faktur`
--
ALTER TABLE `kategorie_faktur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `kontrahenci`
--
ALTER TABLE `kontrahenci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT dla tabeli `licencje`
--
ALTER TABLE `licencje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `sprzety`
--
ALTER TABLE `sprzety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT dla tabeli `waluty`
--
ALTER TABLE `waluty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `faktury`
--
ALTER TABLE `faktury`
  ADD CONSTRAINT `faktury_ibfk_1` FOREIGN KEY (`kontrahent_id`) REFERENCES `kontrahenci` (`id`),
  ADD CONSTRAINT `faktury_ibfk_2` FOREIGN KEY (`waluta`) REFERENCES `waluty` (`id`),
  ADD CONSTRAINT `faktury_ibfk_3` FOREIGN KEY (`rodzaj`) REFERENCES `kategorie_faktur` (`id`),
  ADD CONSTRAINT `faktury_ibfk_4` FOREIGN KEY (`id_dokumentu`) REFERENCES `dokumenty` (`id`);

--
-- Ograniczenia dla tabeli `licencje`
--
ALTER TABLE `licencje`
  ADD CONSTRAINT `licencje_ibfk_1` FOREIGN KEY (`id_faktury`) REFERENCES `faktury` (`id`),
  ADD CONSTRAINT `licencje_ibfk_2` FOREIGN KEY (`id_wlasciciela`) REFERENCES `uzytkownicy` (`id`);

--
-- Ograniczenia dla tabeli `sprzety`
--
ALTER TABLE `sprzety`
  ADD CONSTRAINT `sprzety_ibfk_1` FOREIGN KEY (`nr_faktury`) REFERENCES `faktury` (`id`),
  ADD CONSTRAINT `sprzety_ibfk_2` FOREIGN KEY (`id_wlasciciela`) REFERENCES `uzytkownicy` (`id`);

--
-- Ograniczenia dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `uzytkownicy_ibfk_1` FOREIGN KEY (`id_roli`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
