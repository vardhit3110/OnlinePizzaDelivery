-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 07:20 AM
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
-- Database: `opd`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(21) NOT NULL,
  `adminName` varchar(21) NOT NULL,
  `profilePic` varchar(255) DEFAULT NULL,
  `email` varchar(35) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adminName`, `profilePic`, `email`, `phone`, `password`, `joinDate`, `status`) VALUES
(1, 'vardhit', './adminProfile/20241027_112813_906.jpg', 'vardhit@gmail.com', 9913568078, '$2y$10$ra3Q5FOevFqoFUH94Y4ov.T9pFhokz1xrtgmgK1Qzlq05rvH4LGMq', '2025-07-03 18:06:53', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categorieId` int(12) NOT NULL,
  `categorieName` varchar(255) NOT NULL,
  `categorieImage` varchar(255) DEFAULT NULL,
  `categorieDesc` text NOT NULL,
  `categorieCreateDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categorieId`, `categorieName`, `categorieImage`, `categorieDesc`, `categorieCreateDate`, `status`) VALUES
(22, 'VEG ITEMS', 'img/card-22.jpg', 'A delight for veggie lovers! ', '2025-03-22 18:34:49', 'active'),
(23, 'NON-VEG ITEM', 'img/card-23.jpg', 'Choose your favourite non-veg pizzas from the  Pizza world menu. Get fresh non-veg pizza with your choice of crusts & topping', '2025-03-22 18:36:21', 'active'),
(26, 'BEVERAGES', 'img/card-26.jpg', 'Complement your pizza with wide range of beverages available at Pizza World India', '2025-03-22 18:37:24', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contactId` int(21) NOT NULL,
  `userId` int(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phoneNo` bigint(21) NOT NULL,
  `orderId` int(21) NOT NULL DEFAULT 0 COMMENT 'If problem is not related to the order then order id = 0',
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','resolved') NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contactId`, `userId`, `email`, `phoneNo`, `orderId`, `message`, `time`, `status`) VALUES
(6, 6, 'poojazanzmera31@gmail.com', 9879719578, 17, 'Where My Order Reached???', '2025-03-25 20:01:04', 'resolved'),
(7, 6, 'poojazanzmera31@gmail.com', 9879719578, 25, 'where is my order', '2025-03-30 10:47:17', 'resolved'),
(8, 6, 'poojazanzmera31@gmail.com', 9879719578, 0, 'hgfcfc', '2025-04-01 09:20:49', 'resolved'),
(9, 16, 'vamjahet36@gmail.com', 9898616844, 41, 'Where is my item', '2025-04-10 12:37:19', 'resolved'),
(10, 12, 'vardhit21@gmail.com', 8888888888, 0, 'why are late my pizza\r\n', '2025-06-17 12:04:50', 'new'),
(11, 12, 'vardhit21@gmail.com', 8888888888, 0, 'onlinepizzadelivery', '2025-06-17 12:05:47', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `contactreply`
--

CREATE TABLE `contactreply` (
  `id` int(21) NOT NULL,
  `contactId` int(21) NOT NULL,
  `userId` int(21) NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactreply`
--

INSERT INTO `contactreply` (`id`, `contactId`, `userId`, `message`, `datetime`) VALUES
(4, 9, 16, 'on the way', '2025-04-10 12:38:28'),
(6, 9, 16, 'hyyyyy', '2025-06-17 15:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `delivery_boy_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `vehicle_type` varchar(3) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `delivery_boy_name`, `first_name`, `last_name`, `email`, `phone`, `password`, `vehicle_type`, `status`, `created_at`) VALUES
(4, 'ujas', 'Ujas', 'zanzmera', 'ujaszanzmera06@gmail.com', '1111111111', '$2y$10$Ha6ElsBAD5MKZpW14lcvT.O5x9zy/1UKTb07AH7U2HTVSSncijysq', 'no', 'approved', '2025-03-22 17:19:45'),
(8, 'deliveryboy', 'delivery', 'boy', 'deliveryboy@gmail.com', '8888888888', '$2y$10$IbWgggFz5QD7xm5YoLPpkeLPlT7shrEDw7lzYpdYqbZnDTJR9d6By', 'yes', 'approved', '2025-05-29 05:15:24'),
(9, 'abhay12', 'vora', 'abhay', 'abhay@gmail.com', '1234567890', '$2y$10$yh0B/pUHlez50VbPJQCnbeaoGmmwuZoKNJgGtN3DuIoq99Rthtw.u', 'yes', 'pending', '2025-06-16 05:27:26'),
(10, 'hemal', 'kotadiya', 'hemal', 'hemal@gmail.com', '7098448848', '$2y$10$fosqB.awAbPR/aVwBGrrBOzlMQuOjioFJfwKcfYhlV1SQdCwIr1ma', 'yes', 'pending', '2025-06-19 07:08:43'),
(11, 'hetal', 'mehta', 'hetal', 'mehtahetal@gmail.com', '9987878718', '$2y$10$7tXcIGj57aOfNBPOniTHqOFZwA4T0v2fG9QsoO2NHEuC1OzSHXSyO', 'no', 'rejected', '2025-06-19 07:10:41'),
(12, 'dev', 'vora', 'dev', 'voradev@gmail.com', '8787852365', '$2y$10$m1MYE/pJvYDibFkVlldF9.WpOi4OQLi3YIrfZ7thXLDg8z./BR.6u', 'yes', 'rejected', '2025-06-19 07:13:27'),
(13, 'hemanshu', 'wel', 'hemanshu', 'hemanshu@gmail.com', '7922626262', '$2y$10$/0t5588cIpafbm4oBh16AeVB8lcyHvDtEE4SzzERu.0k08LWBxGW6', 'yes', 'approved', '2025-06-19 07:17:16'),
(14, 'yogesh', 'ashavik', 'yogesh', 'yogesh@gmail.com', '8787887787', '$2y$10$vO91DlHFmykjf.U.PWxYE.JbulKIAxp5NJRgJtsUK.BsJ8CBwHqB.', 'no', 'rejected', '2025-06-19 07:19:52'),
(15, 'santosh', 'shah', 'santosh', 'santosh@gmail.com', '9554848488', '$2y$10$96tc3Nt45sE0uSaxVwSGuOqCmGRIBVeGUJ8uNKom0zefaqCVO8RRu', 'yes', 'pending', '2025-06-19 07:22:06'),
(16, 'sunny', 'croz', 'sunny', 'sunny@gmail.com', '8787787788', '$2y$10$NwetZhzEesC/Q6YRRXq8iOnnIFZHj8vQ68lOywoBn00LsVDju7wLO', 'no', 'rejected', '2025-06-19 07:23:00'),
(17, 'dhruv', 'borad', 'dhruv', 'dhruv@gmail.com', '9999898798', '$2y$10$4snYccBUg.SwNZP1OEW15O2dnxHdYNtJmmeRj9YKgD6IP59bbtRRK', 'yes', 'pending', '2025-06-19 09:26:08'),
(18, 'neel', 'butani', 'neel', 'neel@gmail.com', '7988787879', '$2y$10$DfvE5r8jfjIXAsWwWdvkAOiUiqO2.U/sX.1yPeU.tnssC876tYV4i', 'yes', 'pending', '2025-06-19 09:27:54'),
(19, 'harsh', 'kakalotar', 'harsh', 'harsh@gmail.com', '8944489498', '$2y$10$4KIKzpOCInrIetsfFRZUQOPy29U6UtFlvSHYagh/o6V4sQ34Av0Jy', 'yes', 'pending', '2025-06-19 09:29:17'),
(20, 'mayur', 'jadeja', 'mayur', 'mayur@gmail.com', '7887874811', '$2y$10$X/VIgzTn5/brB2Xed1Oa6uYV2JxKJjBhjFa0WR3ydJM/ybsXIHQ.2', 'yes', 'pending', '2025-06-19 09:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` enum('1','2','3','4','5') NOT NULL COMMENT '1 = Worst, 5 = Best',
  `comment` text NOT NULL,
  `submission_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `rating`, `comment`, `submission_date`, `status`) VALUES
(2, 6, '4', 'nice services', '2025-03-23 11:28:15', 'pending'),
(4, 16, '3', 'nice', '2025-04-10 12:33:18', 'pending'),
(6, 12, '4', 'good\r\n', '2025-06-16 18:29:22', 'pending'),
(7, 12, '3', 'nice service\r\n', '2025-06-16 18:30:03', 'pending'),
(8, 19, '1', 'Delivery boy location find nahi kar pa raha tha, isliye late hua. Support ne help ki. Average experience.', '2025-06-16 18:32:47', 'pending'),
(9, 20, '3', 'Cheese pizza bilkul fresh aur tasty tha. Proper toppings thi. Worth it!', '2025-06-16 18:33:31', 'pending'),
(10, 18, '2', 'Cold pizza deliver hua. Expected hot and fresh. Disappointed this time.', '2025-06-16 18:34:45', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemId` int(12) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `itemImage` varchar(255) DEFAULT NULL,
  `itemPrice` int(12) NOT NULL,
  `itemDesc` text NOT NULL,
  `itemCategorieId` int(12) NOT NULL,
  `itemPubDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','unavailable') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemId`, `itemName`, `itemImage`, `itemPrice`, `itemDesc`, `itemCategorieId`, `itemPubDate`, `status`) VALUES
(69, 'Margherita', 'img/pizza-69.jpg', 99, 'A hugely popular margherita, with a deliciously tangy single cheese topping', 22, '2023-03-21 22:43:44', 'available'),
(70, 'Double Cheese Margherita', 'img/pizza-70.jpg', 150, 'The ever-popular Margherita - loaded with extra cheese... oodies of it', 22, '2023-03-21 22:48:52', 'available'),
(71, 'Farm House', 'img/pizza-71.jpg', 149, 'A pizza that goes ballistic on veggies! Check out this mouth watering overload of crunchy, crisp capsicum, succulent mushrooms and fresh tomatoes', 22, '2023-03-21 22:49:38', 'available'),
(72, 'Peppy Paneer', 'img/pizza-72.jpg', 99, 'Chunky paneer with crisp capsicum and spicy red pepper - quite a mouthful!', 22, '2023-03-21 22:50:17', 'available'),
(73, 'Mexican Green Wave', 'img/pizza-73.jpg\r\n', 149, 'A pizza loaded with crunchy onions, crisp capsicum, juicy tomatoes and jalapeno with a liberal sprinkling of exotic Mexican herbs.\r\n', 22, '2023-03-21 22:50:51', 'available'),
(74, 'Deluxe Veggie', 'img/pizza-74.jpg\r\n', 240, 'For a vegetarian looking for a BIG treat that goes easy on the spices, this one got it all..', 22, '2023-03-21 22:53:07', 'available'),
(75, 'Cheese N Corn', 'img/pizza-75.jpg\r\n', 199, 'Cheese I Golden Corn', 22, '2023-03-21 23:21:28', 'available'),
(76, 'PANEER MAKHANI', 'img/pizza-76.jpg\r\n', 199, ' Paneer and Capsicum on Makhani Sauce', 22, '2023-03-21 23:22:13', 'available'),
(77, 'Indi Tandoori Paneer', 'img/pizza-77.jpg\r\n', 240, 'It is hot. It is spicy. It is oh-so-Indian. Tandoori paneer with capsicum I red paprika I mint mayo\r\n', 22, '2023-03-21 23:23:00', 'available'),
(78, 'PEPPER BARBECUE CHICKEN', 'img/pizza-78.jpg\r\n', 199, 'Pepper Barbecue Chicken I Cheese', 23, '2023-03-21 23:50:46', 'available'),
(79, 'CHICKEN SAUSAGE', 'img/pizza-79.jpg\r\n', 249, 'Chicken Sausage I Cheese', 23, '2023-03-21 23:54:06', 'available'),
(80, 'Chicken Golden Delight', 'img/pizza-80.jpg\r\n', 249, 'Mmm! Barbeque chicken with a topping of golden corn loaded with extra cheese. Worth its weight in gold!', 23, '2023-03-21 23:56:35', 'available'),
(81, 'Chicken Dominator', 'img/pizza-81.jpg\r\n', 160, 'Treat your taste buds with Double Pepper Barbecue Chicken, Peri-Peri Chicken, Chicken Tikka & Grilled Chicken Rashers', 23, '2023-03-21 23:58:16', 'available'),
(82, 'INDI CHICKEN TIKKA', 'img/pizza-82.jpg\r\n', 319, 'The wholesome flavour of tandoori masala with Chicken tikka I onion I red paprika I mint mayo', 23, '2023-03-22 00:00:29', 'available'),
(83, 'CHICKEN FIESTA', 'img/pizza-83.jpg', 199, 'Grilled Chicken Rashers I Peri-Peri Chicken I Onion I Capsicum\r\n', 23, '2023-03-22 00:03:09', 'available'),
(85, 'CHEESY', 'img/pizza-85.jpg', 99, 'Orange Cheddar Cheese I Mozzarella', 22, '2023-03-22 12:34:14', 'available'),
(86, 'VEG LOADED', 'img/pizza-86.jpg', 149, 'Tomato | Grilled Mushroom |Jalapeno |Golden Corn | Beans in a fresh pan crust', 22, '2023-03-22 12:35:26', 'available'),
(87, 'CHEESE N TOMATO', 'img/pizza-87.jpg', 149, ' A delectable combination of cheese and juicy tomato', 22, '2023-03-22 12:36:14', 'available'),
(88, 'GOLDEN CORN', 'img/pizza-88.jpg', 139, 'Golden Corn', 22, '2023-03-22 12:37:07', 'available'),
(89, 'TOMATO', 'img/pizza-89.jpg', 99, 'Juicy tomato in a flavourful combination with cheese I tangy sauce\r\n', 22, '2023-03-22 12:38:57', 'available'),
(90, 'Garlic Breadsticks', 'img/pizza-90.jpg', 99, 'The endearing tang of garlic in breadstics baked to perfection.', 22, '2023-03-22 12:45:50', 'available'),
(91, 'Cheesy Garlic Bread', 'img/pizza-91.jpg', 149, ' Freshly Baked Garlic Bread stuffed with mozzarella cheese, sweet corns & tangy and spicy jalapeños', 22, '2023-03-22 12:46:47', 'available'),
(92, 'Stuffed Garlic Bread', 'img/pizza-92.jpg', 109, 'The endearing tang of garlic in breadstics baked to perfection.', 22, '2023-03-22 12:48:13', 'available'),
(93, 'Chocolate Garlic Bread', 'img/pizza-93.jpg', 159, 'tossed with extra virgin olive oil, exotic herbs & a generous helping of new flavoured sauce.', 22, '2023-03-22 12:51:01', 'available'),
(94, 'Cheese Jalapeno Bread', 'img/pizza-94.jpg', 159, 'A soft creamy cheese dip spiced with jalapeno.', 22, '2023-03-22 12:52:32', 'available'),
(95, 'COCA COLA CAN', 'img/pizza-95.jpg', 129, 'Coca cola tin..', 26, '2023-03-22 12:58:18', 'available'),
(96, 'PEPSI', 'img/pizza-96.jpg', 29, 'Colddrinks its pepsi..', 26, '2023-03-22 13:00:02', 'available'),
(97, 'COMBO DRINKS', 'img/pizza-97.jpg', 145, 'Combo Speacial offer..', 26, '2023-03-22 13:01:44', 'available'),
(98, 'COFFE', 'img/pizza-98.jpg', 99, 'Chocolaty hot coffe', 26, '2023-03-22 13:02:39', 'available'),
(99, 'CHOCO THIKSHAK', 'img/pizza-99.jpg', 89, 'Cold choco thiksahak...250 ml', 26, '2023-03-22 13:04:01', 'available'),
(100, 'LEMON DRINK', 'img/pizza-100.jpg', 99, 'Lemon water drink', 26, '2023-03-22 13:05:10', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `userId`, `itemId`, `created_at`) VALUES
(2, 6, 78, '2025-03-22 13:54:47'),
(4, 6, 70, '2025-03-30 05:14:43'),
(6, 6, 96, '2025-04-01 03:57:02'),
(8, 6, 72, '2025-04-01 07:00:31'),
(9, 12, 70, '2025-04-06 09:21:21'),
(10, 12, 71, '2025-06-10 15:02:15'),
(11, 12, 72, '2025-06-10 15:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(21) NOT NULL,
  `orderId` int(21) NOT NULL,
  `itemId` int(21) NOT NULL,
  `itemQuantity` int(100) NOT NULL,
  `size` varchar(255) NOT NULL DEFAULT 'M',
  `status` enum('available','out_of_stock') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `orderId`, `itemId`, `itemQuantity`, `size`, `status`) VALUES
(15, 15, 80, 1, 'S', 'available'),
(16, 16, 97, 1, 'L', 'available'),
(17, 17, 86, 1, 'M', 'available'),
(18, 18, 79, 1, 'S', 'available'),
(19, 19, 96, 1, 'S', 'available'),
(20, 20, 97, 1, 'S', 'available'),
(21, 21, 99, 1, 'M', 'available'),
(22, 22, 86, 1, 'L', 'available'),
(23, 23, 97, 1, 'S', 'available'),
(24, 24, 77, 1, 'L', 'available'),
(25, 25, 69, 1, 'S', 'available'),
(26, 25, 92, 1, 'M', 'available'),
(27, 25, 79, 1, 'L', 'available'),
(28, 26, 79, 1, 'S', 'available'),
(29, 27, 97, 3, 'S', 'available'),
(30, 27, 96, 1, 'L', 'available'),
(32, 28, 70, 1, 'S', 'available'),
(33, 29, 80, 1, 'M', 'available'),
(34, 30, 73, 7, 'M', 'available'),
(35, 31, 80, 1, 'L', 'available'),
(36, 32, 79, 1, 'L', 'available'),
(37, 33, 97, 1, 'M', 'available'),
(38, 34, 83, 1, 'M', 'available'),
(39, 35, 97, 1, 'M', 'available'),
(40, 36, 96, 1, 'L', 'available'),
(41, 37, 81, 1, 'M', 'available'),
(42, 38, 97, 1, 'S', 'available'),
(43, 39, 98, 1, 'M', 'available'),
(44, 40, 92, 1, 'S', 'available'),
(45, 41, 97, 1, 'S', 'available'),
(46, 42, 95, 1, 'S', 'available'),
(47, 43, 97, 1, 'L', 'available'),
(48, 44, 98, 1, 'M', 'available'),
(49, 45, 96, 1, 'M', 'available'),
(50, 45, 69, 1, 'S', 'available'),
(51, 46, 72, 1, 'S', 'available'),
(52, 46, 95, 1, 'M', 'available'),
(53, 47, 95, 1, 'S', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `userId` int(21) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipCode` int(21) NOT NULL,
  `phoneNo` bigint(21) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `paymentMode` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=cod,1=online',
  `orderStatus` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0',
  `size` enum('S','M','L') NOT NULL DEFAULT 'M',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `delivery_boy_id` int(11) DEFAULT NULL,
  `deliveryTime` int(11) DEFAULT NULL COMMENT 'Delivery time in minutes',
  `finalDeliveryTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `userId`, `address`, `zipCode`, `phoneNo`, `amount`, `payment_id`, `paymentMode`, `orderStatus`, `size`, `createdAt`, `status`, `delivery_boy_id`, `deliveryTime`, `finalDeliveryTime`) VALUES
(32, 6, '30, dangugev society, behind anath aashram, katargam road, surat', 563456, 9879719578, 804.00, NULL, '0', '4', 'M', '2025-04-01 18:48:57', 'completed', 3, 40, NULL),
(33, 6, 'katargam', 563456, 9879719578, 324.00, 'pay_QDo7b92SDKsuGH', '1', '1', 'M', '2025-04-01 18:49:20', 'completed', 8, 30, NULL),
(34, 6, 'katargam', 395006, 9879719578, 437.00, 'pay_QDp1oLGWqNWrah', '1', '1', 'M', '2025-04-01 19:42:50', 'pending', 8, NULL, NULL),
(36, 6, '30, dangugev society, behind anath aashram', 563456, 9879719578, 111.00, 'pay_QFHQvWiTedAFmD', '1', '1', 'M', '2025-04-05 12:08:05', 'pending', 8, NULL, NULL),
(37, 16, 'hirabaugh,surat', 395003, 9898616844, 356.00, 'pay_QG7RVNJWg8AIVh', '1', '4', 'M', '2025-04-07 15:01:39', 'completed', 6, 30, NULL),
(38, 16, 'Ahemdabad, hirabag', 395006, 9898616844, 172.00, NULL, '0', '4', 'M', '2025-04-07 15:10:53', 'completed', 5, 20, 30),
(39, 16, 'surat, hirabag', 395003, 9898616844, 227.00, NULL, '0', '4', 'M', '2025-04-08 13:06:00', 'completed', 5, 26, NULL),
(40, 16, 'Ahemdabad, bapunagar', 395003, 9898616844, 134.00, NULL, '0', '4', 'M', '2025-04-08 13:36:19', 'completed', 5, 30, NULL),
(41, 16, 'hirabaugh,surat', 395003, 9898616844, 172.00, 'pay_QGUZy9na7I2kYh', '1', '4', 'M', '2025-04-08 13:39:42', 'completed', 4, NULL, 20),
(42, 16, 'Ahemdabad, bapunagar', 395003, 9898616844, 155.00, NULL, '0', '4', 'M', '2025-04-10 12:25:54', 'completed', 5, 25, 25),
(43, 16, 'surat', 395009, 9898616844, 476.00, NULL, '1', '0', 'M', '2025-04-11 09:51:21', 'pending', 8, NULL, NULL),
(44, 17, '32, sarita darshan soc., hirabaugh, surat., ', 395006, 7096762604, 227.00, NULL, '0', '4', 'M', '2025-06-03 14:44:44', 'completed', 8, 24, 24),
(45, 12, 'durdarshan', 395006, 8888888888, 184.00, 'pay_QduD3FjC51AryI', '1', '4', 'M', '2025-06-06 17:40:58', 'completed', 8, 25, 25),
(46, 12, 'kanpur', 395006, 8888888888, 490.00, 'pay_QfXC6Y6QYBqhG4', '1', '4', 'M', '2025-06-10 20:27:53', 'completed', 8, 23, 25),
(47, 12, '32, sarita darshan soc., hirabaugh, surat., ', 395006, 9409601795, 155.00, NULL, '0', '4', 'M', '2025-06-16 17:08:50', 'completed', 8, 19, 15);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewId` int(11) NOT NULL,
  `userId` int(21) NOT NULL,
  `orderId` int(21) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `complain` text DEFAULT NULL COMMENT 'User complaint or feedback',
  `reviewDate` datetime NOT NULL DEFAULT current_timestamp(),
  `delivery_boy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewId`, `userId`, `orderId`, `rating`, `complain`, `reviewDate`, `delivery_boy_id`) VALUES
(5, 6, 17, 4, 'Nice Service and kind nature...', '2025-03-25 20:38:22', 4),
(6, 6, 32, 5, 'Excellent service, very fast delivery!', '2025-04-01 21:45:48', 4),
(7, 7, 33, 4, 'Good service but could be quicker.', '2025-04-01 21:45:48', 4),
(8, 8, 34, 3, 'Average experience, late delivery.', '2025-04-01 21:45:48', 4),
(9, 9, 33, 2, 'Poor packaging, item was damaged.', '2025-04-01 21:45:48', 4),
(10, 10, 32, 1, 'Very bad experience, not satisfied.', '2025-04-01 21:45:48', 4),
(11, 16, 37, 4, 'Very bad experience, not satisfied.', '2025-04-07 15:04:29', 4),
(13, 16, 39, 1, 'baddddddd', '2025-04-08 13:30:36', 4),
(14, 16, 42, 4, 'good', '2025-04-10 12:30:07', 4),
(15, 16, 40, 4, 'Very Good', '2025-04-11 09:17:14', 4),
(16, 17, 44, 5, 'Thankyou \r\n', '2025-06-03 15:01:42', 8),
(17, 12, 46, 4, 'excellent, good', '2025-06-16 15:50:15', 8),
(18, 12, 45, 2, 'poor\r\n', '2025-06-16 15:50:51', 8),
(19, 12, 47, 3, 'okkkkkkk', '2025-06-16 17:34:25', 8);

-- --------------------------------------------------------

--
-- Table structure for table `sitedetail`
--

CREATE TABLE `sitedetail` (
  `tempId` int(11) NOT NULL,
  `systemName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `contact1` bigint(21) NOT NULL,
  `contact2` bigint(21) DEFAULT NULL COMMENT 'Optional',
  `address` text NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sitedetail`
--

INSERT INTO `sitedetail` (`tempId`, `systemName`, `email`, `contact1`, `contact2`, `address`, `dateTime`) VALUES
(1, 'Pizza World', 'vardhit@gmail.com', 9123456789, 9876543210, 'C.K. Pithawalla <br>College of Commerce Management <br>and Computer Application<br> Surat', '2021-03-23 19:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(21) NOT NULL,
  `username` varchar(21) NOT NULL,
  `firstName` varchar(21) NOT NULL,
  `lastName` varchar(21) NOT NULL,
  `profilePic` varchar(255) DEFAULT NULL,
  `email` varchar(35) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','blocked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstName`, `lastName`, `profilePic`, `email`, `phone`, `password`, `joinDate`, `status`) VALUES
(12, 'vardhit', 'Vardhit', 'Vamja', 'image/vardhit.jpg', 'vardhit21@gmail.com', 8888888888, '$2y$10$7cyWxuBQRw17oTbKq1aGPecdA2KV1gyIXlZy8yrN9COukaidDJyzu', '2025-03-26 18:35:29', 'active'),
(16, 'het', 'patel', 'het', NULL, 'patelhet@gmail.com', 7777777777, '$2y$10$6xCHyAWEBgHvhFzhi56pgeLHEg8NdLK/8m0SO4/tCrjeaWB.sK4I.', '2025-04-05 20:29:59', 'active'),
(17, 'hiten12', 'vatiya', 'hiten', NULL, 'hitenvatiya@gmail.com', 7096762604, '$2y$10$lhy3SRksMCUnGx5.7x8Ju.PUBQIFkiMh8tSoX2u41BziZQjEkXe1K', '2025-06-03 14:39:36', 'active'),
(18, 'abhi', 'sharma ', 'abhi', NULL, 'abhisharma@gmail.com', 7086954866, '$2y$10$7b3Y/OFdozIKAUdnZBTKpubhiACboh685of/hSdQSq/mXm3cFUQJe', '2025-06-13 17:38:39', 'active'),
(19, 'ravi2811', 'sakariya', 'ravin', NULL, 'ravisakariya@gmail.com', 8932178645, '$2y$10$Vx0z3NFrRdujCouzxo5d2uMH9breQpsWTLRrpCkQpK7Z2/EE552Km', '2025-06-13 17:40:05', 'active'),
(20, 'dhruv711', 'kotadiya', 'dhruv', NULL, 'dhruvkotadiya@gmail.com', 9659595959, '$2y$10$t5RTuos67JYcP9lV.OD7UOIMih1eHFYr2wWcXY7nADOztvXpkLiua', '2025-06-13 17:42:15', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `viewcart`
--

CREATE TABLE `viewcart` (
  `cartItemId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `itemQuantity` int(100) NOT NULL,
  `userId` int(11) NOT NULL,
  `addedDate` datetime NOT NULL DEFAULT current_timestamp(),
  `size` varchar(250) NOT NULL DEFAULT 'S M L'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categorieId`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contactId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `contactreply`
--
ALTER TABLE `contactreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contactId` (`contactId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `pizzaCategorieId` (`itemCategorieId`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `pizzaId` (`itemId`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `pizzaId` (`itemId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `delivery_boy_id` (`delivery_boy_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `reviews_ibfk_3` (`delivery_boy_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `viewcart`
--
ALTER TABLE `viewcart`
  ADD PRIMARY KEY (`cartItemId`),
  ADD KEY `pizzaId` (`itemId`),
  ADD KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categorieId` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contactId` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contactreply`
--
ALTER TABLE `contactreply`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `itemId` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `viewcart`
--
ALTER TABLE `viewcart`
  MODIFY `cartItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contactreply`
--
ALTER TABLE `contactreply`
  ADD CONSTRAINT `contactreply_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE,
  ADD CONSTRAINT `contactreply_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
