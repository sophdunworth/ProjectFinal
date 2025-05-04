-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               9.0.1 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for travelwizard1
CREATE DATABASE IF NOT EXISTS `travelwizard1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `travelwizard1`;

-- Dumping structure for table travelwizard1.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `adminName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.admins: ~4 rows (approximately)
INSERT INTO `admins` (`userID`, `adminName`, `password`) VALUES
	(3, 'Hannah12', '$2y$10$Gdr/9dQmXSVQLfkd2wCghut3H9AnyfgTUnsD1mQGa3PkaFECbplHu'),
	(22, 'Milosz', '$2y$10$wmVdxhDOjz.cS/Y.pJ611OcTcqCYeYPH.jyHVG8YbAyiN.FyYn0c2'),
	(29, 'Trevor', '$2y$10$FOFrzKIejfe7phvnTnHL7Og7rldM9jI8cGQdRcsHstFnSz.XuF/1u'),
	(31, 'John', '$2y$10$MiQiv9l8V/JnRNXrq0mThuzgXcKoIW36tHcUHdVU7ANemZk5nYQGO');

-- Dumping structure for table travelwizard1.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `bookingID` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `package_id` int NOT NULL,
  `dateBooked` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','completed') DEFAULT 'pending',
  `departureFlight` varchar(255) DEFAULT NULL,
  `returnFlight` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bookingID`),
  KEY `user_id` (`user_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`userID`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`packageID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.bookings: ~3 rows (approximately)
INSERT INTO `bookings` (`bookingID`, `user_id`, `package_id`, `dateBooked`, `status`, `departureFlight`, `returnFlight`) VALUES
	(17, 28, 2, '2025-05-03 20:55:09', 'pending', '2025-05-15', '2025-05-29'),
	(18, 28, 10, '2025-05-03 20:55:27', 'pending', '2025-04-15', '2025-05-06'),
	(19, 30, 20, '2025-05-04 16:17:26', 'pending', '2025-06-15', '2025-07-08');

-- Dumping structure for table travelwizard1.contactusrequests
CREATE TABLE IF NOT EXISTS `contactusrequests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_id` int DEFAULT NULL,
  `answered` tinyint(1) DEFAULT '0',
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `response` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `contactusrequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`userID`) ON DELETE SET NULL,
  CONSTRAINT `contactusrequests_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`userID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.contactusrequests: ~5 rows (approximately)
INSERT INTO `contactusrequests` (`id`, `user_id`, `email`, `subject`, `message`, `admin_id`, `answered`, `status`, `created_at`, `response`) VALUES
	(15, 28, 'dunworthsophie@gmail.com', 'Booking', 'If i make a booking and change my mind can i cancel it', NULL, 1, 'closed', '2025-05-03 22:05:47', 'Yes you can'),
	(16, 28, 'dunworthsophie@gmail.com', 'Booking', 'I want to cancel', NULL, 1, 'closed', '2025-05-03 22:20:49', 'which one'),
	(17, 28, 'dunworthsophie@gmail.com', 'Booking', 'help', NULL, 1, 'closed', '2025-05-03 22:42:03', 'yes'),
	(18, NULL, 'sean@gmail.com', 'Account and Booking', 'Hi do I need an account to make a booking', NULL, 0, 'open', '2025-05-04 16:09:07', NULL),
	(19, 30, 'rachel@gmail.com', 'Username Change', 'Can I change my username', NULL, 0, 'open', '2025-05-04 16:18:25', NULL);

-- Dumping structure for table travelwizard1.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `userID` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.customers: ~2 rows (approximately)
INSERT INTO `customers` (`userID`, `username`, `email`, `password`, `created_at`) VALUES
	(28, 'soph123', 'dunworthsophie@gmail.com', '$2y$10$M18m1.z4wdD86UuuOO5b7OjayVwzv1ip4WNdVfu.b6aYzR1lpgNry', '2025-05-03 21:54:51'),
	(30, 'Rachel', 'rachel@gmail.com', '$2y$10$gbFcd69vc9mkz9NxzXj93uiPthhYAzApq8PCtbkRmtJrN19OgoYia', '2025-05-04 17:13:56');

-- Dumping structure for table travelwizard1.destinations
CREATE TABLE IF NOT EXISTS `destinations` (
  `destinationID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `continent` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`destinationID`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.destinations: ~118 rows (approximately)
INSERT INTO `destinations` (`destinationID`, `name`, `description`, `continent`, `country`) VALUES
	(1, 'Mykonos', 'A beautiful Greek island known for its white-washed buildings and vibrant nightlife.', 'Europe', 'Greece'),
	(2, 'Santorini', 'Famous for its stunning sunsets and white-washed buildings on a volcanic island.', 'Europe', 'Greece'),
	(3, 'Paros', 'A charming island with crystal-clear waters and traditional Greek architecture.', 'Europe', 'Greece'),
	(4, 'Naxos', 'The largest of the Cyclades islands, offering beautiful beaches and ancient ruins.', 'Europe', 'Greece'),
	(5, 'Zakynthos', 'Home to the iconic Navagio Beach and crystal-clear waters.', 'Europe', 'Greece'),
	(6, 'Seychelles', 'A paradise of white sand beaches and turquoise waters in the Indian Ocean.', 'Africa', 'Seychelles'),
	(7, 'Mauritius', 'A volcanic island with stunning coral reefs and lush mountains.', 'Africa', 'Mauritius'),
	(8, 'Sri Lanka', 'A culturally rich country with stunning beaches and lush tea plantations.', 'Asia', 'Sri Lanka'),
	(9, 'Maldives', 'A tropical paradise with overwater bungalows and crystal-clear waters.', 'Asia', 'Maldives'),
	(10, 'Paris', 'The romantic capital of France, home to the Eiffel Tower and Louvre Museum.', 'Europe', 'France'),
	(11, 'Lyon', 'A historical French city known for its gastronomy and Renaissance architecture.', 'Europe', 'France'),
	(12, 'Bordeaux', 'Famous for its wine culture and beautiful Garonne River views.', 'Europe', 'France'),
	(13, 'Marseille', 'A vibrant port city with a mix of French and Mediterranean cultures.', 'Europe', 'France'),
	(14, 'Monaco', 'A glamorous city-state known for its casinos and luxurious lifestyle.', 'Europe', 'Monaco'),
	(15, 'Madrid', 'The capital of Spain, known for its grand boulevards and rich history.', 'Europe', 'Spain'),
	(16, 'Barcelona', 'A Catalan city famous for its unique architecture and Mediterranean beaches.', 'Europe', 'Spain'),
	(17, 'Valencia', 'A beautiful Spanish city with futuristic architecture and great food.', 'Europe', 'Spain'),
	(18, 'Alicante', 'A coastal city in Spain known for its golden beaches and historic sites.', 'Europe', 'Spain'),
	(19, 'Malaga', 'A beautiful coastal city in Spain known for its art museums and beaches.', 'Europe', 'Spain'),
	(20, 'Palma (Mallorca)', 'The stunning capital of Mallorca with a rich history and beautiful beaches.', 'Europe', 'Spain'),
	(21, 'Colombia', 'A diverse South American country with vibrant culture and stunning landscapes.', 'South America', 'Colombia'),
	(22, 'Venezuela', 'Home to Angel Falls, the world’s highest waterfall, and stunning Caribbean beaches.', 'South America', 'Venezuela'),
	(23, 'Brazil', 'The largest country in South America, known for Rio de Janeiro, Amazon Rainforest, and vibrant culture.', 'South America', 'Brazil'),
	(24, 'Argentina', 'Famous for tango, Patagonia, and its rich European heritage.', 'South America', 'Argentina'),
	(25, 'Peru', 'Home to Machu Picchu and the Inca Trail, with breathtaking Andean landscapes.', 'South America', 'Peru'),
	(26, 'Panama', 'A country bridging Central and South America, famous for the Panama Canal.', 'South America', 'Panama'),
	(27, 'Costa Rica', 'A nature lover’s paradise with lush rainforests and stunning beaches.', 'South America', 'Costa Rica'),
	(28, 'Mexico', 'A country known for its ancient ruins, stunning beaches, and vibrant culture.', 'North America', 'Mexico'),
	(29, 'Dubai', 'A futuristic city in the UAE known for its luxury, skyscrapers, and desert adventures.', 'Asia', 'United Arab Emirates'),
	(30, 'Abu Dhabi', 'The capital of the UAE, home to the Sheikh Zayed Grand Mosque.', 'Asia', 'United Arab Emirates'),
	(31, 'Doha', 'The capital of Qatar, a modern city with cultural heritage and luxury.', 'Asia', 'Qatar'),
	(32, 'Sydney', 'Australia’s largest city, known for the Opera House and stunning harbor.', 'Oceania', 'Australia'),
	(33, 'Uluru', 'A massive sandstone monolith in Australia’s outback, sacred to Indigenous people.', 'Oceania', 'Australia'),
	(34, 'Great Barrier Reef', 'The world’s largest coral reef system, offering incredible diving experiences.', 'Oceania', 'Australia'),
	(35, 'Auckland', 'New Zealand’s largest city, a gateway to adventure and Maori culture.', 'Oceania', 'New Zealand'),
	(36, 'Rotorua', 'A geothermal wonderland in New Zealand, rich in Maori culture.', 'Oceania', 'New Zealand'),
	(37, 'Queenstown', 'A famous adventure hub in New Zealand known for skiing and bungee jumping.', 'Oceania', 'New Zealand'),
	(38, 'Cape Town', 'A stunning coastal city in South Africa with Table Mountain and Robben Island.', 'Africa', 'South Africa'),
	(39, 'Johannesburg', 'South Africa’s largest city, rich in history and culture.', 'Africa', 'South Africa'),
	(40, 'Durban', 'A coastal city in South Africa known for its beaches and cultural diversity.', 'Africa', 'South Africa'),
	(41, 'Stockholm', 'The capital of Sweden, known for its archipelago and historic old town.', 'Europe', 'Sweden'),
	(42, 'Oslo', 'The capital of Norway, a city of Viking history and stunning fjords.', 'Europe', 'Norway'),
	(43, 'Copenhagen', 'Denmark’s capital, famous for its colorful harbor and fairy-tale history.', 'Europe', 'Denmark'),
	(44, 'Helsinki', 'The capital of Finland, known for its design scene and Baltic charm.', 'Europe', 'Finland'),
	(45, 'Amsterdam', 'A picturesque city with canals, museums, and a vibrant culture.', 'Europe', 'Netherlands'),
	(46, 'Brussels', 'Belgium’s capital, known for its medieval architecture and chocolates.', 'Europe', 'Belgium'),
	(47, 'Cologne', 'A historic German city famous for its Gothic cathedral.', 'Europe', 'Germany'),
	(48, 'Prague', 'The fairytale capital of the Czech Republic with stunning architecture.', 'Europe', 'Czech Republic'),
	(49, 'Vienna', 'Austria’s imperial city, known for its palaces, music, and cafes.', 'Europe', 'Austria'),
	(50, 'Zurich', 'Switzerland’s financial hub with stunning lake and mountain views.', 'Europe', 'Switzerland'),
	(51, 'Geneva', 'A Swiss city known for its international organizations and scenic beauty.', 'Europe', 'Switzerland'),
	(52, 'Interlaken', 'A Swiss resort town nestled between lakes and mountains.', 'Europe', 'Switzerland'),
	(53, 'Bangkok', 'Thailand’s capital, known for its temples, street food, and nightlife.', 'Asia', 'Thailand'),
	(54, 'Chiang Mai', 'A cultural hub in northern Thailand, famous for its temples and markets.', 'Asia', 'Thailand'),
	(55, 'Hanoi', 'Vietnam’s capital, blending French colonial charm and ancient history.', 'Asia', 'Vietnam'),
	(56, 'Halong Bay', 'A UNESCO site in Vietnam, famous for its limestone islands.', 'Asia', 'Vietnam'),
	(57, 'Kuala Lumpur', 'Malaysia’s modern capital, home to the Petronas Towers.', 'Asia', 'Malaysia'),
	(58, 'Bali', 'Indonesia’s paradise island, known for beaches and culture.', 'Asia', 'Indonesia'),
	(59, 'Jakarta', 'Indonesia’s bustling capital, a blend of cultures and skyscrapers.', 'Asia', 'Indonesia'),
	(60, 'Seoul', 'South Korea’s capital, a mix of ancient palaces and modern skyscrapers.', 'Asia', 'South Korea'),
	(61, 'Tokyo', 'Japan’s high-tech capital with historic temples and neon-lit districts.', 'Asia', 'Japan'),
	(62, 'Shanghai', 'China’s biggest city, known for its skyline and historic Bund.', 'Asia', 'China'),
	(63, 'Hong Kong', 'A vibrant metropolis blending East and West, with stunning harbor views.', 'Asia', 'Hong Kong'),
	(64, 'Taipei', 'Taiwan’s capital, famous for night markets and Taipei 101.', 'Asia', 'Taiwan'),
	(65, 'Manila', 'The Philippines’ capital, a city of contrasts with historic sites and malls.', 'Asia', 'Philippines'),
	(66, 'Boracay', 'A small island in the Philippines, known for its white sand beaches.', 'Asia', 'Philippines'),
	(67, 'Montreal', 'A Canadian city with European charm and a thriving arts scene.', 'North America', 'Canada'),
	(68, 'Toronto', 'Canada’s largest city, known for the CN Tower and multicultural vibe.', 'North America', 'Canada'),
	(69, 'Detroit', 'The Motor City, famous for its music and automotive history.', 'North America', 'United States'),
	(70, 'Chicago', 'A major US city known for its architecture, deep-dish pizza, and jazz.', 'North America', 'United States'),
	(71, 'Houston', 'A Texas metropolis, home to NASA and a diverse food scene.', 'North America', 'United States'),
	(72, 'Austin', 'The music capital of Texas, known for its live music and tech scene.', 'North America', 'United States'),
	(73, 'Dallas', 'A modern city with cowboy culture and a bustling economy.', 'North America', 'United States'),
	(74, 'New Orleans', 'A vibrant city famous for jazz, Mardi Gras, and Creole cuisine.', 'North America', 'United States'),
	(75, 'Atlanta', 'A major city in Georgia, known for its history and Southern charm.', 'North America', 'United States'),
	(76, 'Nashville', 'Music City, USA, home to country music legends.', 'North America', 'United States'),
	(77, 'Kentucky', 'A US state known for horse racing, bourbon, and bluegrass music.', 'North America', 'United States'),
	(78, 'Alabama', 'A Southern US state with rich history and scenic landscapes.', 'North America', 'United States'),
	(79, 'Savannah', 'A charming Georgian city with cobblestone streets and historic homes.', 'North America', 'United States'),
	(80, 'Boston', 'One of America’s oldest cities, rich in history and culture.', 'North America', 'United States'),
	(81, 'New York', 'The Big Apple, a global city known for Broadway, skyscrapers, and Central Park.', 'North America', 'United States'),
	(82, 'Philadelphia', 'A city steeped in American history, home to the Liberty Bell.', 'North America', 'United States'),
	(83, 'Washington D.C.', 'The capital of the United States, filled with monuments and museums.', 'North America', 'United States'),
	(84, 'Charlotte', 'A banking hub in North Carolina with a growing cultural scene.', 'North America', 'United States'),
	(85, 'Miami', 'A beach city with Cuban influence, nightlife, and Art Deco charm.', 'North America', 'United States'),
	(86, 'Tahiti', 'A stunning island in French Polynesia, known for its lagoons.', 'Oceania', 'French Polynesia'),
	(87, 'Bora Bora', 'A luxury destination in French Polynesia, famous for overwater bungalows.', 'Oceania', 'French Polynesia'),
	(88, 'Oahu', 'A Hawaiian island, home to Honolulu and Waikiki Beach.', 'Oceania', 'United States'),
	(89, 'Maui', 'A Hawaiian paradise with stunning beaches and lush landscapes.', 'Oceania', 'United States'),
	(90, 'Vancouver', 'Vancouver is known for its beautiful waterfront, city parks, and surrounding mountains.', 'North America', 'Canada'),
	(91, 'Seattle', 'Offers a rich cultural scene with iconic landmarks like the Space Needle and Pike Place Market.', 'North America', 'United States'),
	(92, 'Phoenix', 'Phoenix, Arizona, is famous for its desert landscape, hiking trails, and vibrant arts scene.', 'North America', 'United States'),
	(93, 'San Francisco', 'Is a famous city known for the Golden Gate Bridge, Alcatraz Island, and diverse neighborhoods.', 'North America', 'United States'),
	(94, 'Los Angeles', 'Is a cultural hub known for its entertainment industry, beaches, and vibrant atmosphere.', 'North America', 'United States'),
	(95, 'Las Vegas', 'Is famous for its vibrant nightlife, casinos, and entertainment.', 'North America', 'United States'),
	(96, 'Jamaica', 'Is known for its beautiful beaches, reggae music, and vibrant culture.', 'Caribbean', 'Jamaica'),
	(97, 'Cuba', 'Offers vibrant culture, rich history, and beautiful beaches.', 'Caribbean', 'Cuba'),
	(98, 'Puerto Rico', 'Is famous for its beautiful beaches, old town San Juan, and tropical rainforests.', 'Caribbean', 'Puerto Rico'),
	(99, 'Dominican Republic', 'Offers stunning beaches, resorts, and rich cultural heritage. ', 'Caribbean', 'Dominican Republic'),
	(100, 'Turks and Caicos', 'Is known for its stunning white-sand beaches and crystal-clear waters.', 'Caribbean', 'Turks and Caicos Islands'),
	(101, 'Bahamas', 'Is an archipelago known for its beautiful beaches, crystal-clear water, and vibrant culture.', 'Caribbean', 'Bahamas'),
	(102, 'Saint Lucia', 'Is known for its volcanic beaches, lush rainforests, and luxurious resorts.', 'Caribbean', 'Saint Lucia'),
	(103, 'Barbados', ' Is a Caribbean island known for its stunning beaches, rich culture, and vibrant nightlife.', 'Caribbean', 'Barbados'),
	(104, 'Trinidad and Tobago', 'Offers beautiful beaches, diverse wildlife, and rich culture.', 'Caribbean', 'Trinidad and Tobago'),
	(105, 'Milan', 'Is a global fashion capital known for its stunning architecture and vibrant nightlife.', 'Europe', 'Italy'),
	(106, 'Lake Como', 'Is known for its breathtaking landscapes, luxury villas, and charming villages.', 'Europe', 'Italy'),
	(107, 'Venice', ' Is famous for its canals, gondola rides, and rich history.', 'Europe', 'Italy'),
	(108, 'Florence', 'Is a Renaissance city known for its art, museums, and architecture..', 'Europe', 'Italy'),
	(109, 'Tuscany', 'Is known for its beautiful rolling hills, vineyards, and charming towns.', 'Europe', 'Italy'),
	(110, 'Pisa', 'Is home to the world-famous Leaning Tower and a charming medieval town.', 'Europe', 'Italy'),
	(111, 'Rome', ' Is a city steeped in history with landmarks like the Colosseum and the Vatican. ', 'Europe', 'Italy'),
	(112, 'Vatican City', 'Is the heart of Catholicism, home to St. Peter’s Basilica and the Sistine Chapel.', 'Europe', 'Italy'),
	(113, 'Naples', 'Is known for its historic sites and proximity to Pompeii and the Amalfi Coast.', 'Europe', 'Italy'),
	(114, 'Pompeii', ' Is an ancient Roman city, famously preserved after the eruption of Mount Vesuvius. ', 'Europe', 'Italy'),
	(115, 'Sicily', 'Is the largest Mediterranean island, known for its ancient ruins, beaches, and cuisine.', 'Europe', 'Italy'),
	(116, 'Rhodes', 'Is famous for its medieval Old Town, stunning beaches, and ancient ruins.', 'Europe', 'Greece'),
	(117, 'Kos', 'Is known for its beautiful beaches, historical sites, and vibrant nightlife.', 'Europe', 'Greece'),
	(118, 'Crete', 'Is the largest Greek island, known for its beaches, ancient ruins, and rich culture.', 'Europe', 'Greece');

-- Dumping structure for table travelwizard1.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.notifications: ~2 rows (approximately)
INSERT INTO `notifications` (`id`, `user_id`, `name`, `message`, `sent_at`) VALUES
	(20, 3, 'Summer Holidays', 'Have you Booked your summer Holiday for 2025.\r\n\r\nIf not Book Now for the adventure of a lifetime', '2025-05-02 09:33:56'),
	(24, 3, 'May Bank Holiday', 'Getaway This May', '2025-05-03 19:37:43');

-- Dumping structure for table travelwizard1.packagedestinations
CREATE TABLE IF NOT EXISTS `packagedestinations` (
  `PackageID` int NOT NULL,
  `DestinationID` int NOT NULL,
  `packagedestinationsID` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`PackageID`,`DestinationID`),
  KEY `DestinationID` (`DestinationID`),
  CONSTRAINT `packagedestinations_ibfk_1` FOREIGN KEY (`PackageID`) REFERENCES `packages` (`packageID`),
  CONSTRAINT `packagedestinations_ibfk_2` FOREIGN KEY (`DestinationID`) REFERENCES `destinations` (`destinationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.packagedestinations: ~118 rows (approximately)
INSERT INTO `packagedestinations` (`PackageID`, `DestinationID`, `packagedestinationsID`) VALUES
	(1, 1, 'PD001'),
	(1, 2, 'PD002'),
	(1, 3, 'PD003'),
	(1, 4, 'PD004'),
	(1, 5, 'PD005'),
	(2, 6, 'PD006'),
	(2, 7, 'PD007'),
	(2, 8, 'PD008'),
	(2, 9, 'PD009'),
	(3, 10, 'PD010'),
	(3, 11, 'PD011'),
	(3, 12, 'PD012'),
	(3, 13, 'PD013'),
	(3, 14, 'PD014'),
	(4, 15, 'PD015'),
	(4, 16, 'PD016'),
	(4, 17, 'PD017'),
	(4, 18, 'PD018'),
	(4, 19, 'PD019'),
	(4, 20, 'PD020'),
	(5, 21, 'PD021'),
	(5, 22, 'PD022'),
	(5, 23, 'PD023'),
	(5, 24, 'PD024'),
	(5, 25, 'PD025'),
	(5, 26, 'PD026'),
	(5, 27, 'PD027'),
	(5, 28, 'PD028'),
	(6, 29, 'PD029'),
	(6, 30, 'PD030'),
	(6, 31, 'PD031'),
	(7, 32, 'PD032'),
	(7, 33, 'PD033'),
	(7, 34, 'PD034'),
	(7, 35, 'PD035'),
	(7, 36, 'PD036'),
	(7, 37, 'PD037'),
	(8, 38, 'PD038'),
	(8, 39, 'PD039'),
	(8, 40, 'PD040'),
	(9, 41, 'PD041'),
	(9, 42, 'PD042'),
	(9, 43, 'PD043'),
	(9, 44, 'PD044'),
	(10, 45, 'PD045'),
	(10, 46, 'PD046'),
	(10, 47, 'PD047'),
	(10, 48, 'PD048'),
	(10, 49, 'PD049'),
	(10, 50, 'PD050'),
	(10, 51, 'PD051'),
	(10, 52, 'PD052'),
	(11, 53, 'PD053'),
	(11, 54, 'PD054'),
	(11, 55, 'PD055'),
	(11, 56, 'PD056'),
	(11, 57, 'PD057'),
	(11, 58, 'PD058'),
	(11, 59, 'PD059'),
	(12, 60, 'PD060'),
	(12, 61, 'PD061'),
	(12, 62, 'PD062'),
	(12, 63, 'PD063'),
	(12, 64, 'PD064'),
	(12, 65, 'PD065'),
	(12, 66, 'PD066'),
	(13, 67, 'PD067'),
	(13, 68, 'PD068'),
	(13, 69, 'PD069'),
	(13, 70, 'PD070'),
	(14, 71, 'PD071'),
	(14, 72, 'PD072'),
	(14, 73, 'PD073'),
	(14, 74, 'PD074'),
	(14, 75, 'PD075'),
	(14, 76, 'PD076'),
	(14, 77, 'PD077'),
	(14, 78, 'PD078'),
	(14, 79, 'PD079'),
	(15, 80, 'PD080'),
	(15, 81, 'PD081'),
	(15, 82, 'PD082'),
	(15, 83, 'PD083'),
	(15, 84, 'PD084'),
	(15, 85, 'PD085'),
	(16, 86, 'PD086'),
	(16, 87, 'PD087'),
	(16, 88, 'PD088'),
	(16, 89, 'PD089'),
	(17, 90, 'PD090'),
	(17, 91, 'PD091'),
	(17, 92, 'PD092'),
	(17, 93, 'PD093'),
	(17, 94, 'PD094'),
	(17, 95, 'PD095'),
	(18, 96, 'PD096'),
	(18, 97, 'PD097'),
	(18, 98, 'PD098'),
	(18, 99, 'PD099'),
	(18, 100, 'PD100'),
	(18, 101, 'PD101'),
	(18, 102, 'PD102'),
	(18, 103, 'PD103'),
	(18, 104, 'PD104'),
	(19, 105, 'PD105'),
	(19, 106, 'PD106'),
	(19, 107, 'PD107'),
	(19, 108, 'PD108'),
	(19, 109, 'PD109'),
	(19, 110, 'PD110'),
	(19, 111, 'PD111'),
	(19, 112, 'PD112'),
	(19, 113, 'PD113'),
	(19, 114, 'PD114'),
	(19, 115, 'PD115'),
	(20, 116, 'PD116'),
	(20, 117, 'PD117'),
	(20, 118, 'PD118');

-- Dumping structure for table travelwizard1.packages
CREATE TABLE IF NOT EXISTS `packages` (
  `packageID` int NOT NULL AUTO_INCREMENT,
  `packageName` varchar(255) NOT NULL,
  `airline` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `departureFlight` varchar(255) DEFAULT NULL,
  `returnFlight` varchar(255) DEFAULT NULL,
  `bookingDate` varchar(255) DEFAULT NULL,
  `destinations` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `packageType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `hotels` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`packageID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.packages: ~21 rows (approximately)
INSERT INTO `packages` (`packageID`, `packageName`, `airline`, `price`, `departureFlight`, `returnFlight`, `bookingDate`, `destinations`, `packageType`, `hotels`) VALUES
	(1, 'Greek Island Party Cruise: Sun, Shots & Sea', 'Ryanair', 2000, 'Dublin (DUB) → Mykonos (JMK) at 10:30 AM', 'Zakynthos (ZTH) → Dublin (DUB) at 10:45 PM', 'June 15 - June 29, 2025; August 1 - August 15, 2025', 'Mykonos, Santorini, Paros, Naxos, Zakynthos', 'Beach Getaway', 'Cavo Tagoo Mykonos Katikies Hotel Santorini, Parilio Hotel Paros, Naxian Collection Naxos, Lesante Blu Luxury Hotel & Spa Zante'),
	(2, 'Tropical Treasures: Maldives, Seychelles & Beyond', 'Emirates', 3500, 'Dublin (DUB) → Seychelles (SEZ) at 8:00 AM', 'Maldives (MLE) → Dublin (DUB) at 10:15 PM', 'May 15 - May 29, 2025; August 10 - August 24, 2025', 'Seychelles, Mauritius, Sri Lanka, Maldives', 'Beach Getaway', 'Four Seasons Resort Seychelles, One&Only Le Saint Géran Mauritius, Cinnamon Grand Colombo Sri Lanka, Soneva Fushi Maldives'),
	(3, 'C’est la vie dans France', 'Air France', 1800, 'Dublin (DUB) → Paris (CDG) at 9:00 AM', 'Nice (NCE) → Dublin (DUB) at 8:30 PM', 'May 10 - May 25, 2025; September 15 - September 30, 2025', 'Paris, Lyon, Bordeaux, Marseille, Monaco', 'City Breaks', 'Le Meurice Paris, Villa Florentine Lyon, Les Sources de Caudalie Bordeaux, InterContinental Marseille - Hotel Dieu Marseille, Hotel de Paris Monte-Carlo Monaco'),
	(4, 'Costa del Sol & Beyond: A Spanish Dream', 'Iberia', 2250, 'Dublin (DUB) → Madrid (MAD) - 10:00 AM', 'Palma de Mallorca (PMI) → Dublin (DUB) - 9:00 PM', 'June 10 - July 1, 2025; August 10 - August 31, 2025', 'Madrid, Barcelona, Valencia, Alicante, Malaga, Palma (Mallorca)', 'City Breaks', 'The Westin Palace, Madrid, Majestic Hotel & Spa Barcelona, The Westin Valencia, Meliá Alicante, Gran Hotel Miramar Malaga, Hotel Nixe Palace Mallorca'),
	(5, 'Amazon to Andes: A South American Adventure', 'LATAM Airlines', 4800, 'Dublin (DUB) → Bogotá (BOG) - 8:00 AM', 'Cancún (CUN) → Dublin (DUB) - 7:30 PM', 'July 1 - July 29, 2025; September 1 - September 29, 2025', 'Colombia, Venezuela, Brazil, Argentina, Peru, Panama, Costa Rica, Mexico', 'Adventure Tours', 'Hotel de la Opera Columbia, The Charlee Hotel Medellín, Gran Melia Caracas Hotel Venuezuela, Hotel Venetur Mérida, Belmond Copacabana Palace Brazil, Hotel das Cataratas Iguazu, Alvear Palace Hotel Argentina, Park Hyatt Mendoza, Belmond Miraflores Park Peru, Belmond Hotel Monasterio Cusco, The Waldorf Astoria Panama, Grano de Oro Hotel Costa Rica, Four Seasons Hotel Mexico City, Rosewood Mayakoba Riviera Maya'),
	(6, 'Arabian Nights: A Grand Tour of the Gulf', 'Emirates', 3200, 'Dublin (DUB) → Dubai (DXB) at 9:00 AM', 'Doha (DOH) → Dublin (DUB) at 8:00 PM', 'May 10 - May 24, 2025; November 5 - November 19, 2025', 'Dubai, Abu Dhabi, Doha', 'Cultural Experiences', 'Burj Al Arab Jumeirah Dubai, Emirates Palace Abu Dhabi, The Ritz-Carlton, Doha Qatar'),
	(7, 'The Land Down Under: From the Outback to Kiwi Wonders', 'Qantas', 4500, 'Dublin (DUB) - London (LON) - Sydney (SYD) at 10:00 AM', 'Queenstown (ZQN) - London (LON) - Dublin (DUB) at 7:00 PM', 'May 15 - May 30, 2025; August 10 - August 25, 2025', 'Sydney, Uluru, Great Barrier Reef, Auckland, Rotorua, Queenstown', 'Adventure Tours', 'The Langham, Sydney, Sails in the Desert Uluru, Shangri-La Hotel The Marina Cairns, SKYCITY Grand Hotel Auckland, Polynesian Spa Resort Rotoura, The Rees Hotel & Luxury Apartments Queenstown'),
	(8, 'African Safari: Culture, Wildlife, and Coastal Wonders', 'South African Airways', 6200, 'Dublin (DUB) - Cape Town (CPT) at 9:00 AM', 'Durban (DUR) - Dublin (DUB) at 8:00 PM', 'June 1 - June 14, 2025; September 1 - September 14, 2025', 'Cape Town, Johannesburg, Durban', 'Adventure Tours', 'One&Only Cape Town, The Saxon Hotel Villas & Spa Johannesburg, The Oyster Box Hotel Durban'),
	(9, 'Aurora & Fjords: The Best of Scandinavia', 'Scandinavian Airlines', 2800, 'Dublin (DUB) → Stockholm (ARN) at 10:00 AM', 'Stockholm (ARN) → Dublin (DUB) at 7:00 PM', 'October 5 - October 19, 2025; April 10 - April 24, 2025', 'Stockholm, Oslo, Copenhagen, Helsinki', 'Cultural Experiences', 'Grand Hôtel Stockholm, The Thief Oslo Norway, Hotel dAngleterre Denmark, Hotel Kämp Finland, Nobis Hotel Sweden'),
	(10, 'Grand European Escapade', 'Various European Carriers', 2000, 'Dublin (DUB) → Amsterdam (AMS) at 10:00 AM', 'Zurich (ZRH) → Dublin (DUB) at 7:00 PM', 'April 15 - May 6, 2025; September 10 - October 1, 2025', 'Amsterdam, Brussels, Cologne, Prague, Vienna, Zurich, Geneva, Interlaken', 'Cultural Experiences', 'Hotel De L’Europe Amsterdam, The Dominican Belgium, Excelsior Hotel Ernst Germany, Four Seasons Hotel Prague, Hotel Sacher Austria, Badrutt’s Palace Hotel Zurich, Four Seasons Hotel des Bergues Geneva, Victoria Jungfrau Grand Hotel & Spa Interlaken'),
	(11, 'South East Asia Uncovered', 'Aegean Airlines, Olympic Air, Aer Lingus', 3750, 'Dublin (DUB) → Athens (ATH) Rhodes (RHO) at 10:00 AM', 'Crete (HER) → Athens (ATH) → Dublin (DUB) at 7:00 PM', 'June 10 - June 24, 2025; August 10 - August 24, 2025', 'Bangkok, Chiang Mai, Hanoi, Halong Bay, Kuala Lumpur, Bali, Jakarta', 'Cultural Experiences', 'Mandarin Oriental Bangkok, Four Seasons Resort Chiang Mai, Sofitel Legend Metropole Hanoi, The St. Regis Kuala Lumpur, The Mulia, Nusa Dua, The Ritz-Carlton Jakarta, Taman Mini Indonesia Indah'),
	(12, 'Sun, Spice & Serenity: Southeast Asia Uncovered', 'Various Southeast Asian Carriers', 3750, 'Dublin (DUB) → Bangkok (BKK) at 10:00 AM', 'Jakarta (CGK) → Dublin (DUB) at 10:00 PM', 'July 5 - July 23, 2025; September 5 - September 23, 2025', 'Bangkok, Chiang Mai, Hanoi, Halong Bay, Kuala Lumpur, Bali, Jakarta', 'Cultural Experiences', 'Mandarin Oriental Bangkok, Four Seasons Resort Chiang Mai, Sofitel Legend Metropole Hanoi, The St. Regis Kuala Lumpur, The Mulia Nusa Dua, The Ritz-Carlton Jakarta'),
	(13, 'Legends of the East: Culture, Cities & Temples', 'Korean Air, Japan Airlines, China Eastern, Cathay Pacific, EVA Air, Philippine Airlines', 6500, 'Dublin (DUB) → Seoul (ICN) at 10:00 AM', 'Manila (MNL) → Dublin (DUB) at 08:00 AM', 'June 5 - June 26, 2025; August 10 - August 31, 2025', 'Seoul, Tokyo, Shanghai, Hong Kong, Taipei, Manila, Boracay', 'Cultural Experiences', 'Four Seasons Hotel Seoul, The Peninsula Tokyo, The Peninsula Shanghai, The Ritz-Carlton Hong Kong, W Taipei Taiwan, Solaire Resort & Casino Philippines, Shangri-La’s Boracay Resort & Spa'),
	(14, 'Central Cities Discovery: Culture, History & Skyline', 'Air Canada, Delta Airlines, United Airlines', 2800, 'Dublin (DUB) → Montreal (YUL) at 09:30 AM', 'Chicago (ORD) → Dublin (DUB) at 07:00 PM', 'April 10 - April 24, 2025; September 5 - September 19, 2025', 'Montreal, Toronto, Detroit, Chicago', 'City Breaks', 'Hotel Le Germain Montreal, Four Seasons Hotel Toronto, The Westin Book Cadillac Detroit, The Langham, Chicago'),
	(15, 'Southern Charm: A Deep South Road Trip Adventure', 'American Airlines, Delta Airlines, Southwest Airlines', 5200, 'Dublin (DUB) → Houston (IAH) - 9:00 AM', 'Atlanta (ATL) → Dublin (DUB) - 12:00 PM', 'June 1 - June 24, 2025; September 1 - September 24, 2025', 'Houston, Austin, Dallas, New Orleans, Atlanta, Nashville, Kentucky, Alabama, Savannah', 'Cultural Experiences', 'The Post Oak Hotel Texas, The Driskill Hotel Austin, The Ritz-Carlton Dallas, The Roosevelt New Orleans, The St. Regis Atlanta, The Hermitage Hotel Tennessee, 21c Museum Hotel Lexington Kentucky, The Elyton Hotel Alabama, Renaissance Mobile Riverview Plaza Hotel, The Gastonian Georgia'),
	(16, 'East Coast Explorer: From History to Beaches', 'Delta Airlines, JetBlue, American Airlines', 3500, 'Dublin (DUB) → Boston (BOS) at 7:45 AM', 'Miami (MIA) → Dublin (DUB) at 8:45 PM', 'June 5 - June 23, 2025; December 10 - December 28, 2025', 'Boston, New York, Philadelphia, Washington D.C., Charlotte, Miami', 'City Breaks', 'Fairmont Copley Plaza Boston, The Langham New York Fifth Avenue, The Rittenhouse Hotel Philadelphia, The Willard InterContinental Washington, The Ritz-Carlton Charlotte, The Ritz-Carlton Key Biscayne Miami'),
	(17, 'Aloha to Adventure: Exploring the Pacific Islands', 'Air Tahiti Nui, Hawaiian Airlines', 4350, 'Dublin (DUB) → Tahiti (PPT) at 8:00 AM', 'Maui (OGG) → Dublin (DUB) at 10:00 PM', 'July 10 - July 22, 2025; September 5 - September 17, 2025', 'Tahiti, Bora Bora, Oahu, Maui', 'Adventure Tours', 'InterContinental Tahiti Resort & Spa, Four Seasons Resort Bora Bora, The Royal Hawaiian, a Luxury Collection Resort, Hotel Wailea, Relais & Châteaux'),
	(18, 'Golden Coast Gateway: Beaches, Cities & Road Trip', 'Air Canada, Alaska Airlines, United Airlines', 3300, 'Dublin (DUB) → Vancouver (YVR) at 7:00 AM', 'Las Vegas (LAS) → Dublin (DUB) at 11:00 PM', 'July 1 - July 22, 2025; September 10 - October 1, 2025', 'Vancouver, Seattle, Phoenix, San Francisco, Los Angeles, Las Vegas', 'Beach Getaway', 'Fairmont Pacific Rim Vancouver, The Four Seasons Hotel Seattle, The Phoenician, The Ritz-Carlton San Francisco, The Beverly Hills Hotel Las Angeles, The Venetian Resort Las Vegas'),
	(19, 'Caribbean Bliss: Sun, Sand & Serenity', 'American Airlines, JetBlue, Caribbean Airlines', 4700, 'Dublin (DUB) → Miami (MIA) → Montego Bay (MBJ) at 9:00 AM', 'Port of Spain (POS) → Miami (MIA) → Dublin (DUB) at 6:00 PM', 'May 1 - May 28, 2025; August 1 - August 28, 2025', 'Jamaica, Cuba, Puerto Rico, Dominican Republic, Turks and Caicos, Bahamas, Saint Lucia, Barbados, Trinidad and Tobago', 'Beach Getaway', 'Half Moon Resort Jamaica, Gran Hotel Manzana Kempinski Cuba, Condado Vanderbilt Hotel Peurto Rico, Eden Roc Cap Cana Dominican Republic, Amanyara Resort Turks and Caicos, Atlantis Paradise Island Bahamas, Sugar Beach, A Viceroy Resort Saint Lucia, Sandy Lane Hotel Barbados, Hyatt Centric Leadenhall Trinidad and Tobago'),
	(20, 'La Dolce Vita', 'Aer Lingus', 1700, 'Dublin (DUB) → Milan (MXP) at 8:00 AM', 'Catania (CTA) → Dublin (DUB) at 5:00 AM', 'June 15 - July 8, 2025; August 20 - September 12, 2025', 'Milan, Lake Como, Venice, Florence, Tuscany, Pisa, Rome, Vatican City, Naples, Pompeii, Sicilly', 'City Breaks', 'Hotel Principe di Savoia Milan, Hotel Danieli Venice, Four Seasons Hotel Firenze Florence, Hotel Bologna Pisa, Hotel de Russie Rome, Grand Hotel Vesuvio Naples, Villa Igiea Sicily, Palace Catania, The Phoenicia Malta'),
	(21, 'Greek Island Odyssey', 'Aegean Airlines, Olympic Air, Aer Lingus', 1000, 'Dublin (DUB) → Athens (ATH) Rhodes (RHO) at 10:00 AM', 'Crete (HER) → Athens (ATH) → Dublin (DUB) at 7:00 PM', 'June 10 - June 24, 2025; August 10 - August 24, 2025', 'Rhodes, Kos, Crete', 'Beach Getaway', 'Kallithea Horizon Royal Lindos Rhodes, Kipriotis Village Resort Kos, Blue Palace Luxury Collection Resort Crete');

-- Dumping structure for table travelwizard1.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `bookingID` int NOT NULL,
  `amountPaid` float NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `transactionDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amountPending` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  PRIMARY KEY (`bookingID`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`bookingID`) REFERENCES `bookings` (`bookingID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.payments: ~3 rows (approximately)
INSERT INTO `payments` (`bookingID`, `amountPaid`, `payment_status`, `transactionDate`, `amountPending`, `notes`) VALUES
	(17, 0, 'pending', '2025-05-03 20:55:09', 3500.00, NULL),
	(18, 2000, 'pending', '2025-05-03 20:55:27', 0.00, NULL),
	(19, 1700, 'pending', '2025-05-04 16:17:26', 0.00, NULL);

-- Dumping structure for table travelwizard1.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `reviewID` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `rating` int NOT NULL,
  `service` varchar(100) NOT NULL,
  `reviewText` varchar(300) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reviewID`),
  KEY `userID` (`userID`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.reviews: ~4 rows (approximately)
INSERT INTO `reviews` (`reviewID`, `userID`, `email`, `rating`, `service`, `reviewText`, `created_at`) VALUES
	(8, NULL, 'dunworthsophie@gmail.com', 5, 'Holiday Purchased', 'Easy to Book', '2025-05-02 10:20:09'),
	(9, NULL, 'mike@gmail.com', 4, 'Customer Service', 'Very Helpful', '2025-05-02 10:26:39'),
	(10, NULL, 'dunworthsophie@gmail.com', 3, 'Customer Service', 'Want quicker responses', '2025-05-02 10:31:23'),
	(11, NULL, 'sean@gmail.com', 3, 'Customer Service', 'Slow Replies', '2025-05-04 16:10:23');

-- Dumping structure for table travelwizard1.subscribers
CREATE TABLE IF NOT EXISTS `subscribers` (
  `subscriberID` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `subscribeDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscriberID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.subscribers: ~5 rows (approximately)
INSERT INTO `subscribers` (`subscriberID`, `email`, `subscribeDate`) VALUES
	(1, 'dempseysean230@gmail.com', '2025-04-08 10:17:39'),
	(5, 'B00165974@mytudublin.ie', '2025-05-02 10:16:10'),
	(6, 'dunworthsophie@gmail.com', '2025-05-02 10:17:03'),
	(7, 'dave@gmail.com', '2025-05-04 16:11:14'),
	(8, 'rachel@gmail.com', '2025-05-04 16:16:01');

-- Dumping structure for table travelwizard1.users
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travelwizard1.users: ~5 rows (approximately)
INSERT INTO `users` (`userID`, `username`, `email`, `password`, `created_at`, `user_type`) VALUES
	(3, 'Hannah12', 'hannahjones@gmail.com', '$2y$10$Gdr/9dQmXSVQLfkd2wCghut3H9AnyfgTUnsD1mQGa3PkaFECbplHu', '2025-04-02 11:23:47', 'admin'),
	(28, 'soph123', 'dunworthsophie@gmail.com', '$2y$10$M18m1.z4wdD86UuuOO5b7OjayVwzv1ip4WNdVfu.b6aYzR1lpgNry', '2025-05-03 21:54:51', 'customer'),
	(29, 'Trevor', 'trevor@gmail.com', '$2y$10$FOFrzKIejfe7phvnTnHL7Og7rldM9jI8cGQdRcsHstFnSz.XuF/1u', '2025-05-03 23:49:30', 'admin'),
	(30, 'Rachel', 'rachel@gmail.com', '$2y$10$gbFcd69vc9mkz9NxzXj93uiPthhYAzApq8PCtbkRmtJrN19OgoYia', '2025-05-04 17:13:56', 'customer'),
	(31, 'John', 'John@gmail.com', '$2y$10$MiQiv9l8V/JnRNXrq0mThuzgXcKoIW36tHcUHdVU7ANemZk5nYQGO', '2025-05-04 17:21:15', 'admin');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
