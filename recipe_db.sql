-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 06:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recipe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`user_id`, `recipe_id`) VALUES
(1, 1),
(1, 7),
(3, 1),
(3, 4),
(3, 6),
(4, 1),
(4, 2),
(4, 16),
(7, 6),
(7, 7),
(10, 5),
(11, 7);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `quantity`, `unit`) VALUES
(1, 'Spaghetti', 500.00, 'grams'),
(2, 'Eggs', 3.00, 'pieces'),
(3, 'Pancetta', 150.00, 'grams'),
(4, 'Parmesan Cheese', 50.00, 'grams'),
(5, 'Chicken Breast', 600.00, 'grams'),
(6, 'Yogurt', 200.00, 'grams'),
(7, 'Tomato Sauce', 400.00, 'ml'),
(8, 'Soy Sauce', 50.00, 'ml'),
(9, 'Bell Peppers', 2.00, 'pieces'),
(10, 'Ground Beef', 500.00, 'grams'),
(11, 'Taco Shells', 8.00, 'pieces'),
(12, 'Rice', 250.00, 'grams'),
(13, 'Mushrooms', 150.00, 'grams'),
(14, 'Romaine Lettuce', 1.00, 'head'),
(15, 'Chocolate Chips', 300.00, 'grams'),
(16, 'Salmon Fillet', 2.00, 'pieces'),
(17, 'chicken', 1.00, 'grams'),
(18, 'bread', 2.00, 'pcs'),
(19, 'rice', 500.00, 'grams'),
(20, 'tortilla', 5.00, 'pcs'),
(21, 'beef', 500.00, 'grams');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `list_no` int(11) NOT NULL,
  `item` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`list_no`, `item`) VALUES
(1, 'chicken'),
(4, 'chicken'),
(4, 'pepper'),
(4, 'salt'),
(5, 'chicken'),
(5, 'pepper'),
(5, 'vinegar'),
(7, 'milk');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `ingredient_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`ingredient_id`, `recipe_id`) VALUES
(1, 1),
(2, 1),
(2, 7),
(3, 1),
(4, 1),
(4, 5),
(5, 2),
(6, 2),
(7, 2),
(7, 3),
(8, 3),
(9, 3),
(10, 4),
(11, 4),
(12, 5),
(13, 5),
(14, 6),
(15, 7),
(16, 8),
(17, 16),
(18, 16),
(20, 18),
(21, 18);

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate`
--

INSERT INTO `rate` (`id`, `date`, `comment`, `rating`, `user_id`, `recipe_id`) VALUES
(1, '2025-01-06', 'yes', 1.00, 4, 16);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` text DEFAULT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `cook_time` int(11) DEFAULT NULL,
  `servings` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `nutrition_info` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `recipe_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `user_id`, `title`, `instructions`, `prep_time`, `cook_time`, `servings`, `category`, `rating`, `nutrition_info`, `description`, `recipe_image`) VALUES
(1, 1, 'Spaghetti Carbonara', 'Cook spaghetti, fry pancetta, mix with eggs and cheese, combine with pasta.', 10, 20, 4, 'Pasta', 4.50, 'Calories: 400, Protein: 15g', 'Spaghetti Carbonara: Classic Italian pasta with pancetta, eggs, and Parmesan cheese.', 'path_or_url_to_image.jpg'),
(2, 1, 'Chicken Tikka Masala', 'Marinate chicken, grill, and simmer in a spiced tomato sauce.', 30, 45, 4, 'Curry', 4.80, 'Calories: 500, Protein: 30g', 'Chicken Tikka Masala: A flavorful curry made with marinated grilled chicken in a spiced tomato-based sauce.', NULL),
(3, 1, 'Vegetable Stir Fry', 'Stir fry vegetables in oil, add soy sauce and serve with rice.', 10, 15, 2, 'Vegetarian', 4.20, 'Calories: 250, Protein: 5g', 'Vegetable Stir Fry: Quick and easy stir-fried veggies tossed in a savory soy sauce glaze.', NULL),
(4, 1, 'Beef Tacos', 'Cook ground beef with spices, serve in taco shells with toppings.', 15, 10, 4, 'Mexican', 4.70, 'Calories: 300, Protein: 20g', 'Beef Tacos: Ground beef seasoned to perfection, served in crispy taco shells with fresh toppings.', NULL),
(5, 1, 'Mushroom Risotto', 'Slowly cook rice with broth, add mushrooms and cheese.', 10, 30, 4, 'Italian', 4.60, 'Calories: 350, Protein: 8g', 'Mushroom Risotto: Creamy risotto made with mushrooms, Parmesan, and a hint of white wine.', NULL),
(6, 1, 'Caesar Salad', 'Toss romaine lettuce with Caesar dressing, croutons, and parmesan.', 5, 0, 2, 'Salad', 4.30, 'Calories: 200, Protein: 6g', 'Caesar Salad: Crunchy romaine lettuce tossed with creamy Caesar dressing, croutons, and Parmesan.', NULL),
(7, 1, 'Chocolate Chip Cookies', 'Mix ingredients, shape into balls, and bake until golden.', 15, 10, 24, 'Dessert', 5.00, 'Calories: 150 per cookie', 'Chocolate Chip Cookies: Soft, chewy cookies loaded with melty chocolate chips, a perfect dessert.', NULL),
(8, 1, 'Grilled Salmon', 'Season salmon and grill for perfect doneness, serve with lemon.', 10, 15, 2, 'Seafood', 4.90, 'Calories: 350, Protein: 30g', 'Grilled Salmon: Fresh salmon fillets grilled to perfection with a squeeze of lemon for flavor.', NULL),
(9, NULL, 'Pasta Carbonara', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A classic Italian pasta dish.', 'https://carbonara.com/images/recipe.jpeg\r\n'),
(10, 1, 'chicken curry', '.aohojc', 2, 2, 2, 'breakfast', NULL, 'kmcjajdjaj;jac', NULL, NULL),
(11, 1, 'dfas', 'asdfsdf', 23, 23, 23, '23', NULL, 'asdfasdf', 'asdfasdf', NULL),
(12, 1, 'dfas', 'asdfsdf', 23, 23, 23, '23', NULL, 'asdfasdf', 'asdfasdf', NULL),
(13, 1, 'sdfasdf', 'asdf', 122, 2, 22, '2', NULL, 'adfsdf', 'sdf', 'Screenshot (2).png'),
(14, 1, 'butter chicken', 'add all thej', 10, 5, 4, 'lunch, indian', NULL, '', 'good', 'Screenshot (1).png'),
(15, 1, 'ss', 's', 4, 4, 1, 'breakfast', NULL, '', 's', 'Screenshot (5).png'),
(16, 1, 'sandwich', 'sww', 1, 1, 1, 'lunch, indian', NULL, '', 'aww', 'Screenshot (1).png'),
(18, 4, 'tacos', 'cook', 10, 20, 5, 'mexican', NULL, 'yes', 'd', 'Beef_Tacos.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `shoppinglist`
--

CREATE TABLE `shoppinglist` (
  `list_no` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoppinglist`
--

INSERT INTO `shoppinglist` (`list_no`, `user_id`) VALUES
(4, 1),
(5, 3),
(1, 4),
(2, 4),
(3, 4),
(6, 7),
(7, 11),
(8, 12);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `password`, `name`) VALUES
(1, '$2y$10$SFWN6/b64yNizcD7jEqK/O.KNZl6ZWqZ5D1b2vyGru8hDCOjLV1Hu', '123'),
(2, '$2y$10$HI640UwJy5QD9yT24hMO5ujXIvv5uh1VBClzEPAdgBOKtmSeSfFzu', '1234'),
(3, '$2y$10$kgGZj2suC9tcEVTxnEPRmO1/GajWPQ7ft4ODJ5lWG4OJpOhHHk1VC', 'zayeed'),
(4, '$2y$10$cWoylZRx3csO/y316v3iB.KiJjJhFL8C0n18.PnnA2W3cJKBi1gMa', '111'),
(5, '$2y$10$St2mjWP8Cb9RkeGKWiKS4Og57AvWk2j7/IsV3P.Zs.mIPUgUrNoWa', 'faiza'),
(6, '$2y$10$PVrkr8IGJGH4H7QEbCkBiupatYJ1A/G0YRHm6W8N3AEUwK4luTwBy', '1111'),
(7, '$2y$10$iY/9xsBZn16edRG.OL63ieuLqn9IxBpTbQBLXu3M46cKkL8zquQNG', 'Labiba'),
(8, '$2y$10$bqU6UGtnfdtmpTc5ro5bhe94LoVbdJSoi6VjZiAKNgEGuRi.YmQIS', 'badhon'),
(9, '$2y$10$003gOGW9r3H4mIwwW0tapudnb8ndBT8qwp8i26mxx25E8sU//5f0a', '333'),
(10, '$2y$10$UJjLekmQqnmQIdjzXJUY9uBfbWHomjBmvTcuNVJhsoAbUbwXSIDg.', 'tahmid'),
(11, '$2y$10$DRlxu623GV5cRy0uSiCxmOZrHc.kypo77vhfPlXyQkwhueke9Alta', 'mahir'),
(12, '$2y$10$fGJAqZqGu7eRpOdVFyA1puEfKTfD1cEcOpqHNaGStUCgvdhnXSCcC', 'jahed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`user_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`list_no`,`item`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ingredient_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
  ADD PRIMARY KEY (`list_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rate`
--
ALTER TABLE `rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
  MODIFY `list_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`list_no`) REFERENCES `shoppinglist` (`list_no`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`);

--
-- Constraints for table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `rate_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`);

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
  ADD CONSTRAINT `shoppinglist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
