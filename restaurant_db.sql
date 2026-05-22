-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Gegenereerd op: 22 mei 2026 om 09:37
-- Serverversie: 8.2.0
-- PHP-versie: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$12$nKVbU9WMgUjBNlZz1fw22OLyfAmUrtXzwRKm9l6VshQZ6ICudoAty', '2026-04-15 09:40:11');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Pieter de Vries', 'pieter@example.com', 'Vraag over allergieën', 'Hebben jullie ook glutenvrije opties?', '2026-04-15 09:40:11');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Bruschetta', 'Voorgerechten', 'Geroosterd brood met tomaat, basilicum en olijfolie.', 6.50, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(2, 'Soep van de Dag', 'Voorgerechten', 'Dagverse soep bereid met seizoensgroenten.', 5.95, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(3, 'Gegrilde Zalm', 'Hoofdgerechten', 'Zalmfilet met citroen, groenten en aardappelpuree.', 18.50, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(4, 'Pasta Carbonara', 'Hoofdgerechten', 'Romige pasta met spek, Parmezaanse kaas en ei.', 15.75, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(5, 'Tiramisu', 'Desserts', 'Klassieke Italiaanse tiramisu met cacao.', 6.95, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(6, 'Cheesecake', 'Desserts', 'Romige cheesecake met rood fruit.', 8.75, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(7, 'Cola', 'Drankjes', 'Frisdrank 33cl.', 2.80, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(8, 'Verse Jus d’orange', 'Drankjes', 'Vers geperst sinaasappelsap.', 3.95, 'assets/images/placeholder.jpg', '2026-04-15 09:40:11'),
(12, 'Roney Zakko', 'Voorgerechten', 'asdasd', 22.00, 'uploads/menu/menu_69e8dfb09987a1.96784356.jpg', '2026-04-22 14:48:16'),
(13, 'cola', 'Drankjes', 'cola', 3.00, 'uploads/menu/menu_69e8ecb3126ac4.55635829.jpg', '2026-04-22 15:43:47');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `guests` int NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `phone`, `email`, `reservation_date`, `reservation_time`, `guests`, `notes`, `created_at`) VALUES
(1, 'Jan Jansen', '0612345678', 'jan@example.com', '2026-04-20', '19:00:00', 2, 'Tafel bij het raam', '2026-04-15 09:40:11'),
(2, 'Fatima El Amrani', '0687654321', 'fatima@example.com', '2026-04-22', '20:00:00', 4, 'Verjaardag', '2026-04-15 09:40:11'),
(3, 'Roney Zakko', '0630804143', 'rooney.zakko@gmail.com', '2026-04-15', '12:51:00', 3, 'hhvhjbc asd', '2026-04-15 09:51:15'),
(4, 'Fadi Grgi', '06 52897516', 'abo_ghash@gmail.com', '2026-04-22', '19:00:00', 12, 'verjaardag', '2026-04-22 16:00:26'),
(5, 'Mohamad', '0646556146', 'jasguhdgh@gmail.com', '2026-05-23', '12:33:00', 2, 'ik wil een tafel voor 2 personen', '2026-05-22 09:32:08');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
