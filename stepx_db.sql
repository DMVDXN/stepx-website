-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2025 at 06:01 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stepx_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_path`) VALUES
(1, 'Air Jordan 1 Retro High OG \'Black Toe Reimagined\'', 'Familiar but always fresh, the iconic Air Jordan 1 is remastered for today\'s sneakerhead culture. This Retro High OG version goes all in with premium leather, comfortable cushioning and classic design details.', '180.00', 'images/Air Jordan 1 Retro High OG \'Black Toe Reimagined\'.png'),
(2, '9060 \'Cherry Blossom Pack - Mushroom\'', 'The New Balance 9060 Mushroom Aluminum was released as part of the New Balance’s brand catalog in the spring season featuring aluminum and mushroom tones.', '150.00', 'images/9060 \'Cherry Blossom Pack - Mushroom\'.png'),
(3, 'Air Max 95 \'Icons - Yellow Strike\'', 'The Nike Air Max 95 Icons Yellow Strike arrives sporting a color scheme of white, Yellow Strike, Wolf Gray, and Cool Gray, crafted using a combination of materials, including mesh, suede, and nubuck', '170.00', 'images/Air Max 95 \'Icons - Yellow Strike\'.png'),
(4, 'Yeezy Desert Boot \'Oil\'', 'Compliment the Coachella fit with the adidas Yeezy Desert Boot Oil. This Yeezy Boot comes with an oil upper, oil midsole, and an oil sole. These sneakers released in April 2019 and retailed for $200.', '210.00', 'images/Yeezy Desert Boot \'Oil\'.png'),
(5, '6 Inch Premium Waterproof Boot Wide \'Wheat\'', 'Originally designed in 1973 with New England construction workers in mind, Timberland’s 6 inch boot gained popularity as a streetwear staple in the 90s.', '198.00', 'images/6 Inch Premium Waterproof Boot Wide \'Wheat\'.png'),
(6, 'Air Jordan 5 Retro OG \'Black Metallic Reimagined\'', 'The Air Jordan 5 Retro OG Black Metallic Reimagined pays homage to one of Jordan Brand’s most iconic designs, first debuted in 1990. This updated version reimagines the timeless sneaker with subtle yet impactful modern tweaks.', '210.00', 'images/Air Jordan 5 Retro OG \'Black Metallic Reimagined\'.png'),
(7, '1906R \'Metallic Silver Metallic Gold\'', 'The New Balance 1906R White Metallic Gold comes in a metallic silver and metallic gold color scheme.', '155.00', 'images/1906R \'Metallic Silver Metallic Gold\'.png'),
(8, 'Air Max Plus 3 \'Black White\'', 'The Nike Air Max Plus 3 Black White is a special iteration of the Air Max Plus line, which was first released in 1998. It features a black color scheme with white accents to provide a touch of contrast.', '170.00', 'images/Air Max Plus 3 \'Black White\'.png'),
(9, 'Yeezy Boost 700 \'Wave Runner\' 2023', 'The Yeezy 700 Boost Wave Runner is the shoe that led Yeezy\'s pivot from a sleek, minimal aesthetic to a chunky, 90s inspired one. This was the gateway sneaker between knit runners like the 350 and the grungy skate look of the 550.', '300.00', 'images/Yeezy Boost 700 \'Wave Runner\' 2023.png'),
(10, 'Gel Kayano 14 \'Pink Glow\'', 'The ASICS Gel Kayano 14 Pink Glo is a retro-styled running shoe that was designed to have a late 2000s aesthetic. The athletic brand released it as a nod to its original Gel Kayano series,', '150.00', 'images/Gel Kayano 14 \'Pink Glow\'.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(1, 'Daniel', 'Onyejiekwe', 'daniel@example.com', '$2y$10$syK7.UHZTDrZac.mbYf/8u.EbKyrkuAeVEAyQbP5F88cojzwIxHES', '2025-04-22 02:49:14'),
(2, 'Nina', 'Lopez', 'nina.lopez@example.com', '$2y$10$3M9iX9AHHxgUudWO7UlxE.yz3B5CqU.87ea2PmFF5BlIOJobfyWt2', '2025-04-22 03:18:33'),
(3, 'Linda', 'Carter', 'linda.carter@example.com', '$2y$10$QB0O1UWZnzu0OFYER7Vxf.K9I4YA0no9zuPn5O6k9RdmUMOXBqVFK', '2025-04-22 03:20:13'),
(4, 'John', 'Adams', 'john.adams@example.com', '$2y$10$BcUTjfofBsH9PTSG.meO/OOI0fKSqdZKEo.emTnhHULgxxlUVxRde', '2025-04-22 03:20:40'),
(5, 'Sarah', 'Lee', 'sarah.lee@example.com', '$2y$10$VgPyDkSusUZP3hrzK2OY3eP2imJqRxNR1vEuX9qC2hTQdFqApksBy', '2025-04-22 03:21:11'),
(6, 'Mike', 'Smith', 'mike.smith@example.com', '$2y$10$zDAjREFR1muO1V.LCmqIA.AW3m0.ybmPec91KJiRQVFx23HfX7V9W', '2025-04-22 03:21:39'),
(7, 'Emily', 'Johnson', 'emilyj@example.com', '$2y$10$/fGhp1h313k/djY6Z0JItOPCEKIrTGi4hcEgWoBiFmiMgvd.7vZvK', '2025-04-22 03:22:09'),
(8, 'James', 'Brown', 'jamesb@example.com', '$2y$10$S1SVEYyQZ4Aq2Jf0qpZX6ubP.1Fk30OpsnVVVpJNNXmZcsB8L/Zp6', '2025-04-22 03:22:40'),
(9, 'Robert', 'Wilson', 'robertw@example.com', '$2y$10$BpGEh4nAic71LHN20TsjQeDn4ARGssSQUPm7bA1oG3iiSKmvg7htu', '2025-04-22 03:23:06'),
(10, 'Jessica', 'Garcia', 'jessg@example.com', '$2y$10$vhhq2ZG.EgQglRFsPJ9GDOLTuarYN2vC2f80E7/rrVQPKkcdr3ZTC', '2025-04-22 03:23:51'),
(11, 'David', 'Martinez', 'davidm@example.com', '$2y$10$J5s.tKmMZ0yLyyzrbMU4Hel7OVcwGN60GVG51.ykdZxzqeDl.9.Bi', '2025-04-22 03:24:19'),
(12, 'John', 'Doe', 'johndoe@example.com', '$2y$10$HEh.Isedd9VvDhTrETuAHOYB8jPfWesOAYrn9wWRffD0bo5wFdKLa', '2025-04-22 03:25:49'),
(13, 'Jane', 'Doe', 'janedoe@example.com', '$2y$10$MsanIpDqHNU5ZRHA7uqbeOY0cHUVORLB8DRATkzuHWoP6YTGa.0qi', '2025-04-22 03:26:18'),
(14, 'test', 'ing', 'testing@example.com', '$2y$10$u.PQym5f1Xps/8Sm7B/I9Okk6PQNziI43lUkBee42D7RGsC98eq22', '2025-04-22 03:47:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
