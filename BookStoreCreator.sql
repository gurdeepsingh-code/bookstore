CREATE DATABASE If NOT EXISTS bookstorecreator;

CREATE TABLE `bookinventory` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `price` int(4) NOT NULL,
  `instock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `checkout` (
  `id` int(11) NOT NULL,
  `bookid` int(10) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `card_type` varchar(50) NOT NULL,
  `card_holder_name` varchar(200) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `card_expiry` varchar(5) NOT NULL,
  `card_cvv` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;