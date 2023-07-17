-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 17, 2023 at 10:16 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_category`
--

CREATE TABLE `tb_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_category`
--

INSERT INTO `tb_category` (`category_id`, `category_name`) VALUES
(3, 'โทรศัพท์มือถือ'),
(4, 'เสื้อผ้า'),
(5, 'รองเท้า'),
(6, 'อาหาร'),
(7, 'เครื่องดื่ม');

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE `tb_customer` (
  `customer_id` int(11) NOT NULL,
  `card_number` varchar(100) NOT NULL,
  `customer_fname` varchar(200) NOT NULL,
  `customer_lname` varchar(200) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_line` varchar(100) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_customer`
--

INSERT INTO `tb_customer` (`customer_id`, `card_number`, `customer_fname`, `customer_lname`, `customer_phone`, `customer_line`, `customer_address`, `group_id`, `created_at`) VALUES
(8, '4200-15-03-23 06:35', 'user2', '22', 'test', 'stst', '', 10, '15-03-23 06:35'),
(9, '2017-15-03-23 06:37', 'user1', '11', '0987654321', 'test', '', 11, '15-03-23 06:37'),
(10, '2500-17-03-23 03:58', 'user3', '33', '098765', 'jlfsdf', '', 11, '17-03-23 03:58'),
(11, '6978-17-03-23 03:59', 'user4', '44', '32423', '324234', '', 11, '17-03-23 03:59'),
(12, '3024-18-03-23 10:16', 'วีระพงษ์', 'สุราโพธิ์', '0925562767', '1', 'fdsfsdf', 10, '18-03-23 10:16');

-- --------------------------------------------------------

--
-- Table structure for table `tb_employee`
--

CREATE TABLE `tb_employee` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(200) DEFAULT NULL,
  `lname` varchar(200) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `line` varchar(100) DEFAULT NULL,
  `user_img` varchar(255) NOT NULL DEFAULT 'manager.png',
  `position_id` int(11) NOT NULL,
  `type` int(3) NOT NULL,
  `created_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_employee`
--

INSERT INTO `tb_employee` (`employee_id`, `username`, `password`, `fname`, `lname`, `address`, `phone`, `line`, `user_img`, `position_id`, `type`, `created_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin', 'ยโสธร', 'admin', 'admin', 'manager.png', 1, 999, '2023-03-19 14:16:35'),
(13, 'test', 'c698def38600c6d82e00302b1ee85632', 'test', 'test', 'test', '0925562767', 'test', '32078327-profile.jpeg', 1, 1, '19/03/2023 20:22:39'),
(22, 'emp', 'e10adc3949ba59abbe56e057f20f883e', 'emp', 'emp', 'test', '0987654321', 'test', 'manager.png', 2, 1, '04/04/2023 10:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `tb_group_customer`
--

CREATE TABLE `tb_group_customer` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_group_customer`
--

INSERT INTO `tb_group_customer` (`group_id`, `group_name`) VALUES
(10, 'VIP'),
(11, 'ซื้อขายบ่อย');

-- --------------------------------------------------------

--
-- Table structure for table `tb_order`
--

CREATE TABLE `tb_order` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_qty` int(10) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_total_price` decimal(10,2) NOT NULL,
  `product_vat` decimal(10,2) NOT NULL,
  `total_discount` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `total_sum_vat` decimal(10,2) NOT NULL,
  `day` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_order`
--

INSERT INTO `tb_order` (`order_id`, `product_id`, `sale_id`, `product_qty`, `product_price`, `product_total_price`, `product_vat`, `total_discount`, `total_cost`, `total_sum_vat`, `day`) VALUES
(1, 5, 1, 1, '3999.00', '3999.00', '279.93', '0.00', '3000.00', '4278.93', '16'),
(2, 8, 2, 1, '50.00', '50.00', '0.00', '0.00', '20.00', '50.00', '16'),
(3, 13, 3, 1, '12000.00', '12000.00', '0.00', '0.00', '10000.00', '12000.00', '16');

-- --------------------------------------------------------

--
-- Table structure for table `tb_position`
--

CREATE TABLE `tb_position` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_position`
--

INSERT INTO `tb_position` (`position_id`, `position_name`) VALUES
(1, 'ผู้ดูแลร้าน'),
(3, 'พนักงาน');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product`
--

CREATE TABLE `tb_product` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` int(11) DEFAULT NULL,
  `product_sub` int(11) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_img` varchar(255) DEFAULT 'product.png',
  `product_discount` decimal(10,2) DEFAULT NULL,
  `product_cost` decimal(10,2) DEFAULT NULL,
  `product_wholesale` decimal(10,2) NOT NULL,
  `product_retail` decimal(10,2) NOT NULL,
  `product_qty` int(10) NOT NULL,
  `product_stock` varchar(255) DEFAULT NULL,
  `product_exp` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_product`
--

INSERT INTO `tb_product` (`product_id`, `product_code`, `product_name`, `product_category`, `product_sub`, `product_price`, `product_img`, `product_discount`, `product_cost`, `product_wholesale`, `product_retail`, `product_qty`, `product_stock`, `product_exp`, `status`) VALUES
(5, 'B100', 'Nike Air', 5, NULL, '3999.00', '11676297-nike.webp', '0.00', '3000.00', '3200.00', '3100.00', 20, 'shop', '', 1),
(6, 'B101', 'Adidas', 5, NULL, '4200.00', '46322971-adidas.webp', NULL, '3500.00', '3700.00', '3800.00', 4, 'SHOP', '', 1),
(8, 'FOOD101', 'กระเพรา', 6, 10, '50.00', '85336921-photo.jpeg', NULL, '20.00', '30.00', '40.00', 100, '', '', 1),
(9, 'W100', 'ชานมใต้หวัน', 7, 11, '40.00', '22899349-2.jpeg', NULL, '20.00', '35.00', '30.00', 100, '', '', 1),
(10, 'W101', 'แป๊บซี่', 7, 12, '30.00', '47076791-ec888a20dd777255276d61dbbe9a63c4.jpg_2200x2200q80.jpg_.webp', NULL, '20.00', '27.00', '25.00', 100, '', '', 1),
(11, 'A201', 'iPhone 14', NULL, NULL, '29000.00', 'product.png', NULL, '19000.00', '27000.00', '20000.00', 4, NULL, NULL, 1),
(12, 'A202', 'Macbook Pro', NULL, NULL, '34000.00', 'product.png', NULL, '30000.00', '30000.00', '28000.00', 3, NULL, NULL, 1),
(13, 'A203', 'Apple Watch', NULL, NULL, '12000.00', 'product.png', NULL, '10000.00', '11000.00', '13000.00', 3, NULL, NULL, 1),
(14, 'A204', 'iPad Pro', NULL, NULL, '18000.00', 'product.png', NULL, '13000.00', '15000.00', '16000.00', 10, NULL, NULL, 1),
(15, 'A205', 'Airpod Pro', NULL, NULL, '5800.00', 'product.png', NULL, '2000.00', '40000.00', '50000.00', 30, NULL, NULL, 1),
(16, 'A206', 'แก้วน้ำ', NULL, NULL, '100.00', 'product.png', NULL, '10.00', '80.00', '70.00', 30, NULL, NULL, 1),
(17, 'A207', 'จอคอม', NULL, NULL, '3900.00', 'product.png', NULL, '1000.00', '3000.00', '2700.00', 30, NULL, NULL, 1),
(18, 'A208', 'สายไฟ', NULL, NULL, '200.00', 'product.png', NULL, '19.00', '100.00', '50.00', 30, NULL, NULL, 1),
(19, 'A209', 'หูฟัง', NULL, NULL, '490.00', 'product.png', NULL, '50.00', '200.00', '100.00', 30, NULL, NULL, 1),
(20, 'A210', 'สายชาจ', NULL, NULL, '400.00', 'product.png', NULL, '100.00', '300.00', '200.00', 30, NULL, NULL, 1),
(21, 'A211', 'เคสมือถือ', NULL, NULL, '100.00', 'product.png', NULL, '3.00', '30.00', '10.00', 30, NULL, NULL, 1),
(22, 'A212', 'แป้นพิมพ์', NULL, NULL, '380.00', 'product.png', NULL, '100.00', '200.00', '140.00', 30, NULL, NULL, 1),
(23, 'A213', 'ครีมอาบน้ำ', NULL, NULL, '400.00', 'product.png', NULL, '100.00', '300.00', '150.00', 30, NULL, NULL, 1),
(24, 'A214', 'โฟมล้างหน้า', NULL, NULL, '380.00', 'product.png', NULL, '20.00', '100.00', '30.00', 30, NULL, NULL, 1),
(25, 'A215', 'พัดลม', NULL, NULL, '400.00', 'product.png', NULL, '100.00', '300.00', '200.00', 30, NULL, NULL, 1),
(26, 'A216', 'ปลั๊กไฟ', NULL, NULL, '280.00', 'product.png', NULL, '60.00', '200.00', '100.00', 30, NULL, NULL, 1),
(27, 'A217', 'ผ้าม่าน', NULL, NULL, '100.00', 'product.png', NULL, '10.00', '40.00', '20.00', 30, NULL, NULL, 1),
(28, 'A218', 'ถ้วย', NULL, NULL, '40.00', 'product.png', NULL, '1.00', '10.00', '5.00', 30, NULL, NULL, 1),
(29, '8850779559955', 'น้ำปล่าว AURA', 7, 12, '17.00', '10716440-181017184709P9Nu.png', NULL, '11.00', '15.00', '13.00', 100, 'โกดัง', '2023-07-18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sale`
--

CREATE TABLE `tb_sale` (
  `sale_id` int(11) NOT NULL,
  `sale_code` varchar(100) NOT NULL,
  `customer_id` varchar(100) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `count_cart` int(100) NOT NULL,
  `get_money` decimal(10,2) NOT NULL,
  `change_money` decimal(10,2) NOT NULL,
  `product_total_price` decimal(10,2) NOT NULL,
  `product_tatal_vat` decimal(10,2) NOT NULL,
  `price_sum_vat` decimal(10,2) NOT NULL,
  `by_date` varchar(100) NOT NULL,
  `by_month` int(10) NOT NULL,
  `by_year` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_sale`
--

INSERT INTO `tb_sale` (`sale_id`, `sale_code`, `customer_id`, `employee_id`, `count_cart`, `get_money`, `change_money`, `product_total_price`, `product_tatal_vat`, `price_sum_vat`, `by_date`, `by_month`, `by_year`) VALUES
(1, '#746365', '', 1, 1, '4278.93', '0.00', '3999.00', '279.93', '4278.93', '16-07-23 22:05:24', 7, 2566),
(2, '#437788', '', 1, 1, '80.00', '30.00', '50.00', '0.00', '50.00', '16-07-23 22:14:01', 7, 2566),
(3, '#226448', '', 1, 1, '12100.00', '100.00', '12000.00', '0.00', '12000.00', '16-07-23 22:15:04', 7, 2566);

-- --------------------------------------------------------

--
-- Table structure for table `tb_shop`
--

CREATE TABLE `tb_shop` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_address` varchar(255) NOT NULL,
  `shop_img` varchar(255) NOT NULL,
  `shop_phone` varchar(100) NOT NULL,
  `line_notify` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_shop`
--

INSERT INTO `tb_shop` (`id`, `shop_name`, `shop_address`, `shop_img`, `shop_phone`, `line_notify`) VALUES
(1, 'บริษัทบิ้กออโต้ จำกัด (นามสมมุติ)', 'ยโสธร', '18439297-BIG_AUTO-removebg-preview.png', '0925562767', 'EGUQButlBysapnuGO44jKpx3biPIHvJKBy2HlLksnw3');

-- --------------------------------------------------------

--
-- Table structure for table `tb_size_page`
--

CREATE TABLE `tb_size_page` (
  `id` int(11) NOT NULL,
  `width` varchar(20) NOT NULL,
  `height` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_size_page`
--

INSERT INTO `tb_size_page` (`id`, `width`, `height`) VALUES
(1, '42', '245');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sub_category`
--

CREATE TABLE `tb_sub_category` (
  `sub_id` int(11) NOT NULL,
  `category_id` int(100) NOT NULL,
  `sub_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_sub_category`
--

INSERT INTO `tb_sub_category` (`sub_id`, `category_id`, `sub_name`) VALUES
(1, 3, 'Vivo'),
(2, 3, 'Apple'),
(3, 3, 'Oppo'),
(4, 4, 'เสื้อเด็ก'),
(5, 4, 'เสื้อผู้ใหญ่'),
(6, 5, 'Nike'),
(7, 5, 'adidas'),
(9, 6, 'เส้น'),
(10, 6, 'ข้าว'),
(11, 7, 'ชานม'),
(12, 7, 'น้ำอัดลม');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_category`
--
ALTER TABLE `tb_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tb_employee`
--
ALTER TABLE `tb_employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `tb_group_customer`
--
ALTER TABLE `tb_group_customer`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `tb_order`
--
ALTER TABLE `tb_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tb_position`
--
ALTER TABLE `tb_position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tb_sale`
--
ALTER TABLE `tb_sale`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `tb_shop`
--
ALTER TABLE `tb_shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_size_page`
--
ALTER TABLE `tb_size_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_sub_category`
--
ALTER TABLE `tb_sub_category`
  ADD PRIMARY KEY (`sub_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_category`
--
ALTER TABLE `tb_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_customer`
--
ALTER TABLE `tb_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_employee`
--
ALTER TABLE `tb_employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_group_customer`
--
ALTER TABLE `tb_group_customer`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_order`
--
ALTER TABLE `tb_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_position`
--
ALTER TABLE `tb_position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_product`
--
ALTER TABLE `tb_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tb_sale`
--
ALTER TABLE `tb_sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_shop`
--
ALTER TABLE `tb_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_size_page`
--
ALTER TABLE `tb_size_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_sub_category`
--
ALTER TABLE `tb_sub_category`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
