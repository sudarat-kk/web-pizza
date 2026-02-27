-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 07:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartdetail`
--

CREATE TABLE `cartdetail` (
  `CartDetailID` int(11) NOT NULL,
  `PizzaID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `SizeID` int(11) DEFAULT NULL,
  `CrustID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combinedorder`
--

CREATE TABLE `combinedorder` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `OrderDate` date DEFAULT NULL,
  `Status` enum('ยังไม่จัดส่ง','จัดส่งแล้ว') NOT NULL,
  `Paystatus` enum('ยังไม่จ่ายเงิน','จ่ายเงินแล้ว') NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL,
  `phone` varchar(255) NOT NULL,
  `totalPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `combinedorder`
--

INSERT INTO `combinedorder` (`OrderID`, `UserID`, `OrderDate`, `Status`, `Paystatus`, `Address`, `phone`, `totalPrice`) VALUES
(183, 5, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', '', '', 130),
(184, 5, '2023-10-31', 'ยังไม่จัดส่ง', 'จ่ายเงินแล้ว', '', '', 130),
(185, 5, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', '', '', 150),
(186, 6, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'มมส', '', 10170),
(187, 6, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'MSUUUU', '', 900),
(191, 6, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'yyyyy', '', 570),
(192, 6, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'iiiii', '', 150),
(193, 4, '2023-10-31', 'จัดส่งแล้ว', 'จ่ายเงินแล้ว', 'โตเกียวจ้าาไปหาพี่โกโจที่ตัวขาดครึ่ง', '', 765),
(194, 4, '2023-10-31', 'จัดส่งแล้ว', 'ยังไม่จ่ายเงิน', 'โตเกียว โรงเรีนไสยเวทย์ สั่งซื้อโดย เกะโทสุงุรุ', '0956539327', 10265),
(195, 4, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'msu', '055555', 145),
(196, 4, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', '', '', 165),
(197, 4, '2023-10-31', 'จัดส่งแล้ว', 'ยังไม่จ่ายเงิน', 'msu', '0956539327', 575),
(198, 4, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'ที่อยู่: oh no', '0956539327', 165),
(199, 4, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'ที่อยู่: เนนนนน', 'เบอร์โทร: 055555', 205),
(200, 4, '2023-10-31', 'ยังไม่จัดส่ง', 'ยังไม่จ่ายเงิน', 'ที่อยู่: la', 'เบอร์โทร: 0956539327', 150015);

-- --------------------------------------------------------

--
-- Table structure for table `crust`
--

CREATE TABLE `crust` (
  `CrustID` int(11) NOT NULL,
  `CrustType` enum('บางกรอบ','หนานุ่ม','ขอบชีส') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crust`
--

INSERT INTO `crust` (`CrustID`, `CrustType`) VALUES
(1, 'บางกรอบ'),
(2, 'หนานุ่ม'),
(3, 'ขอบชีส');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `DetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PizzaID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `SizeID` int(11) NOT NULL,
  `CrustID` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`DetailID`, `OrderID`, `PizzaID`, `Quantity`, `SizeID`, `CrustID`, `UnitPrice`) VALUES
(48, 192, 3, 1, 1, 1, 150.00),
(49, 193, 2, 2, 1, 1, 300.00),
(50, 193, 3, 3, 1, 3, 450.00),
(51, 194, 9, 1, 1, 1, 10000.00),
(52, 194, 7, 1, 1, 1, 250.00),
(53, 195, 1, 1, 1, 1, 130.00),
(54, 196, 2, 1, 1, 1, 150.00),
(55, 197, 1, 2, 1, 1, 260.00),
(56, 197, 2, 1, 1, 1, 150.00),
(57, 197, 4, 1, 1, 1, 150.00),
(58, 198, 3, 1, 1, 1, 150.00),
(59, 199, 6, 1, 1, 1, 190.00),
(60, 200, 9, 6, 4, 3, 150000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pizza`
--

CREATE TABLE `pizza` (
  `PizzaID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pizza`
--

INSERT INTO `pizza` (`PizzaID`, `Name`, `Description`, `Price`, `ImageURL`) VALUES
(1, 'Meat Lover\'s Pizza', 'ซอสมะเชือเทศ, มอสซาเรลลา ชีส\r\nเปปเปอโรนี, ไส้กรอกอิตาเลี่ยน, แฮม', 130.00, 'https://www.thespruceeats.com/thmb/xuxwh4RIGcZMgaJE8u3SueM0SoA=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/aqIMG_4568fhor-0b89dc5c8c494ee9828ed29805791c5a.jpg'),
(2, 'Margherita Pizza', 'พิซซ่ามาร์การิต้าเป็นพิซซ่าพื้นฐานที่มีหน้า 3 สี ซึ่งเป็นสีของธงชาติอิตาลี ได้แก่ ขาว เขียว และแดง สีขาวก็มาจากชีส สีแดงมาจากส่วนประกอบมะเขือเทศ และสีเขียวก็มาจากใบโหระพาอิตาเลี่ยนนั่นเอง', 150.00, 'https://images.getrecipekit.com/20220225134810-recipes-2x1_pizza-2.jpeg?aspect_ratio=16:9&quality=90&'),
(3, 'Hawaiian Pizza', 'พิซซ่าหน้าฮาวายเอี้ยน สูตรของไอแอมพิซซ่า สูตรที่มีรสชาติเปรี้ยว หวาน มันในคำเดียว เมื่อรับประทานเข้าไปให้ความเปรี้ยวหวานจากสับปะรดและซอส ให้กลิ่นหอมของชีสที่เข้ากันอย่างลงตัว ', 150.00, 'https://www.allrecipes.com/thmb/v1Xi2wtebK1sZwSJitdV4MGKl1c=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/hawaiian-pizza-ddmfs-3x2-132-450eff04ad924d9a9eae98ca44e3f988.jpg'),
(4, 'BBQ Chicken Pizza', 'บาร์บีคิวไก่พิซซ่าในรูปแบบประยุกต์บนขนมปังโฮลวีท', 150.00, 'https://www.tasteandtellblog.com/wp-content/uploads/2021/01/BBQ-Chicken-Pizza-3.jpg'),
(5, 'Veggie Lover\'s Pizza', 'ผักโขม, เห็ด, หอมใหญ่, มะเขือเทศ, พริกแดง พริกเขียว, มอสซาเรลล่าชีส และซอสพิซซ่า', 130.00, 'https://www.simplyrecipes.com/thmb/wyfKZ6r4an5GdL19fFiAFlgr19c=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Simply-Recipes-Vegetarian-Pizza-LEAD-5-03d81aaf35f24e5b99de36d2c29c15eb.jpg'),
(6, 'Bacon Pizza', 'เบคอนรมควัน, อเมริกันชีส, เอมเมนทอลชีส, แดรี่ วอลเลย์ พาเมซานชีส, มอสซาเรลล่าชีส และซอสซาวครีม', 190.00, 'https://www.thespruceeats.com/thmb/23NTwSODwjvMBSuc87GILHxD8t4=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/bacon-pizza-482053-hero-01-2f6c8ac218e54d16968e0dd8378cc1d4.jpg'),
(7, 'Buffalo Chicken Pizza', 'เปปเปอโรนีเห็ด มะกอกหัวหอม พริกไส้กรอกซาลามิเนื้อบด เบคอน แฮม ไก่ แอนโชวีมะเขือเทศ ผักโขม และ สับปะรด', 250.00, 'https://bc.pizza/wp-content/uploads/2019/03/022-BUFFALO-CHICKEN-PIZZA.jpg'),
(8, 'Seafood Pizza', 'ปลา (รวมทั้งปลาแซลมอน ทูน่า แอนโชวี่) หอย หอยกาบ หอยเชลล์  หอยแมลงภู่ กุ้ง  ปลาหมึก  กุ้งล็อบสเตอร์ และอาหารทะเลอื่นๆอีกมากมาย', 200.00, 'https://staticcookist.akamaized.net/wp-content/uploads/sites/22/2021/11/shrimp-pizza.jpg'),
(9, 'Golden Pizza', 'พิซซ่าสุดพิเศษจาก Mr.Beast', 10000.00, 'https://i.ytimg.com/vi/F4Y3Pkn95GI/maxresdefault.jpg'),
(10, 'BBQ Chicken', 'พิซซ่าบาร์บีคิวชิกเก้น (BBQ Chicken)\r\n\r\nมีส่วนประกอบจาก ไก่, ซอสบาร์บีคิว, และอื่น ๆ', 170.00, 'https://recipe-graphics.grocerywebsite.com/0_GraphicsRecipes/8164_4k.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `SizeID` int(11) NOT NULL,
  `SizeName` enum('S','M','L','XL') NOT NULL,
  `Multiplier` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`SizeID`, `SizeName`, `Multiplier`) VALUES
(1, 'S', 1.00),
(2, 'M', 1.50),
(3, 'L', 2.00),
(4, 'XL', 2.50);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('Customer','ShopOwner') NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `url`, `Username`, `PasswordHash`, `Role`, `name`) VALUES
(1, 'https://media.tenor.com/AXhu_2lIQX4AAAAC/gojo-gojo-satoru.gif', 'satoru@gmail.com', '$2y$10$yJXhhW2Xh0BHOHEQKn5Zf.Ht08AJ2XD20MBstMw0NWAO7kHw9a652', 'ShopOwner', 'Gojo Satoru'),
(4, 'https://ovicio.com.br/wp-content/uploads/2023/07/20230712-ovicio-jujutsu-kaisen-data-crunchyroll-555x555.jpg', 'suguru@gmail.com', '$2y$10$Fs/7SRol4ZuHBTF9ODWkdermVJFhh.tAu80nwNl6VFShwlHUxu3PC', 'Customer', 'Geto Suguru'),
(5, 'https://i.pinimg.com/1200x/27/e0/0f/27e00fc37aab4b64803ee1201fb9ee85.jpg', 'Maki@gmail.com', '$2y$10$Fs/7SRol4ZuHBTF9ODWkdermVJFhh.tAu80nwNl6VFShwlHUxu3PC', 'Customer', 'Maki'),
(6, 'https://i.pinimg.com/736x/d3/f1/7c/d3f17c1c02f8ee3ae768e11e25601ea2.jpg', 'Yuta@gmail.com', '$2y$10$yJXhhW2Xh0BHOHEQKn5Zf.Ht08AJ2XD20MBstMw0NWAO7kHw9a652', 'Customer', 'Yuta'),
(7, '', 'Panda', '123', 'Customer', NULL),
(8, '', 'Magumi', '123', 'Customer', NULL),
(9, '', 'Nobata', '123', 'Customer', NULL),
(10, '', 'Meimei', '123', 'Customer', NULL),
(11, '', 'Toge', '123', 'Customer', NULL),
(12, '', 'Yuji', '123', 'Customer', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD PRIMARY KEY (`CartDetailID`),
  ADD KEY `PizzaID` (`PizzaID`),
  ADD KEY `SizeID` (`SizeID`),
  ADD KEY `CrustID` (`CrustID`),
  ADD KEY `FK_User_cartdetail` (`UserID`);

--
-- Indexes for table `combinedorder`
--
ALTER TABLE `combinedorder`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `crust`
--
ALTER TABLE `crust`
  ADD PRIMARY KEY (`CrustID`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `PizzaID` (`PizzaID`),
  ADD KEY `SizeID` (`SizeID`),
  ADD KEY `CrustID` (`CrustID`);

--
-- Indexes for table `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`PizzaID`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`SizeID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartdetail`
--
ALTER TABLE `cartdetail`
  MODIFY `CartDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `combinedorder`
--
ALTER TABLE `combinedorder`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `crust`
--
ALTER TABLE `crust`
  MODIFY `CrustID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `pizza`
--
ALTER TABLE `pizza`
  MODIFY `PizzaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `SizeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD CONSTRAINT `FK_User_cartdetail` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `cartdetail_ibfk_2` FOREIGN KEY (`PizzaID`) REFERENCES `pizza` (`PizzaID`),
  ADD CONSTRAINT `cartdetail_ibfk_3` FOREIGN KEY (`SizeID`) REFERENCES `size` (`SizeID`),
  ADD CONSTRAINT `cartdetail_ibfk_4` FOREIGN KEY (`CrustID`) REFERENCES `crust` (`CrustID`);

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `combinedorder` (`OrderID`),
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`PizzaID`) REFERENCES `pizza` (`PizzaID`),
  ADD CONSTRAINT `orderdetail_ibfk_3` FOREIGN KEY (`SizeID`) REFERENCES `size` (`SizeID`),
  ADD CONSTRAINT `orderdetail_ibfk_4` FOREIGN KEY (`CrustID`) REFERENCES `crust` (`CrustID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
