-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 07, 2024 alle 15:21
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magazzino`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`username`, `password`) VALUES
('nicola', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetti`
--

CREATE TABLE `oggetti` (
  `id` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `altezza` int(11) NOT NULL,
  `larghezza` int(11) NOT NULL,
  `peso` int(11) NOT NULL,
  `rif_scaffale` int(11) NOT NULL,
  `Fornitori` varchar(30) NOT NULL,
  `Prezzo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `oggetti`
--

INSERT INTO `oggetti` (`id`, `nome`, `altezza`, `larghezza`, `peso`, `rif_scaffale`, `Fornitori`, `Prezzo`) VALUES
(1, 'contenitore_acqua', 15, 60, 3, 1, 'Lacor', 50),
(2, 'contenitore_pellet', 50, 50, 5, 1, 'Zenith', 25),
(3, 'scala', 100, 30, 25, 2, 'Sicos', 78),
(4, 'imbragatura', 10, 30, 2, 2, 'Sicos', 90),
(5, 'crema_viso_donna', 5, 2, 1, 3, 'sephora', 35),
(6, 'scheda_madre', 0, 0, 0, 4, 'nvidia', 1500),
(7, 'contorno_occhi', 5, 1, 1, 3, 'sephora', 75);

-- --------------------------------------------------------

--
-- Struttura della tabella `scaffali`
--

CREATE TABLE `scaffali` (
  `categoria` varchar(30) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `scaffali`
--

INSERT INTO `scaffali` (`categoria`, `id`) VALUES
('contenitori', 1),
('attrezzi_lavoro', 2),
('cosmetici', 3),
('elettronica', 4);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `credenziali`
--
ALTER TABLE `credenziali`
  ADD PRIMARY KEY (`username`);

--
-- Indici per le tabelle `oggetti`
--
ALTER TABLE `oggetti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rel_scaffale` (`rif_scaffale`);

--
-- Indici per le tabelle `scaffali`
--
ALTER TABLE `scaffali`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `oggetti`
--
ALTER TABLE `oggetti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `scaffali`
--
ALTER TABLE `scaffali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `oggetti`
--
ALTER TABLE `oggetti`
  ADD CONSTRAINT `rel_scaffale` FOREIGN KEY (`rif_scaffale`) REFERENCES `scaffali` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
