-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 03:36 PM
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
-- Database: `petshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` varchar(10) NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `ward_id` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `customer_id`, `address_line`, `ward_id`, `is_default`, `created_at`) VALUES
('1', 'CUS11', '101 Đường Lê Duẩn', '1', 1, '2025-06-08 19:44:51'),
('2', 'CUS12', '123', '2', 1, '2025-06-08 19:46:28'),
('A01', 'CUS1', '123 Le Loi', 'W01', 1, '2024-06-01 10:00:00'),
('A02', 'CUS1', '45 Nguyen Hue', 'W02', 1, '2024-06-01 10:10:00'),
('A03', 'CUS2', '78 Tran Hung Dao', 'W03', 1, '2024-06-01 10:20:00'),
('A04', 'CUS3', '99 Pham Van Dong', 'W04', 1, '2024-06-01 10:30:00'),
('A05', 'CUS4', '12 Kim Ma', 'W05', 1, '2024-06-01 10:40:00'),
('A06', 'CUS5', '56 Hoang Hoa Tham', 'W06', 1, '2024-06-01 10:50:00'),
('A07', 'CUS6', '33 Ly Quoc Su', 'W07', 1, '2024-06-01 11:00:00'),
('A08', 'CUS6', '90 Dien Bien Phu', 'W01', 1, '2024-06-01 11:10:00'),
('A09', 'CUS7', '111 Phan Chu Trinh', 'W08', 1, '2024-06-01 11:20:00'),
('A11', 'CUS6', '33 Ly Quoc Su', 'W07', 0, '2024-06-01 11:00:00'),
('A12', 'CUS8', '90 Dien Bien Phu', 'W01', 0, '2024-06-01 11:10:00'),
('A17', 'CUS9', '33 Ly Quoc Su', 'W07', 0, '2024-06-01 11:00:00'),
('A18', 'CUS10', '90 Dien Bien Phu', 'W01', 0, '2024-06-01 11:10:00'),
('A19', 'CUS3', '33 Ly Quoc Su', 'W07', 0, '2024-06-01 11:00:00'),
('A20', 'CUS2', '90 Dien Bien Phu', 'W01', 0, '2024-06-01 11:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `admin_name`, `email`, `phone`, `date_of_birth`, `created_at`) VALUES
('AD1', 'UA1', 'Alice Nguyen', 'alice@shop.com', '901111111', '1985-01-15', '0000-00-00 00:00:00'),
('AD2', 'UA2', 'Bob Tran', 'bob@shop.com', '902222222', '1986-02-20', '0000-00-00 00:00:00'),
('AD3', 'UA3', 'Charlie Ho', 'charlie@shop.com', '903333333', '1987-03-25', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` varchar(10) NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`, `created_at`, `updated_at`) VALUES
('CART1', 'CUS1', '2025-06-01 08:00:00', '2025-06-04 10:00:00'),
('CART2', 'CUS2', '2025-06-02 09:00:00', '2025-06-04 11:00:00'),
('CART3', 'CUS3', '2025-06-02 10:00:00', '2025-06-04 09:30:00'),
('CART4', 'CUS4', '2025-06-03 08:30:00', '2025-06-03 09:30:00'),
('CART5', 'CUS5', '2025-06-03 12:00:00', '2025-06-03 12:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` varchar(10) NOT NULL,
  `cart_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_id`, `product_id`, `quantity`) VALUES
('CI01', 'CART1', 'PRD1', 2),
('CI02', 'CART1', 'PRD4', 1),
('CI05', 'CART3', 'PRD11', 1),
('CI06', 'CART3', 'PRD12', 2),
('CI07', 'CART4', 'PRD15', 1),
('CI08', 'CART4', 'PRD16', 1),
('CI09', 'CART5', 'PRD2', 1),
('CI10', 'CART5', 'PRD3', 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` varchar(10) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`) VALUES
('CAT1', 'Thuc an', 'Cac loai thuc an cho cho va meo.'),
('CAT2', 'Do choi', 'Cac loai do choi cho cho va meo.'),
('CAT3', 'Phu kien cham soc', 'Cac vat dung ho tro viec cham soc thu cung.'),
('CAT4', 'Vat dung ve sinh', 'Cac san pham giup duy tri ve sinh cho thu cung.'),
('CAT5', 'Chuong va giuong', 'Noi o thoai mai va an toan cho thu cung.'),
('CAT6', 'Snack/Banh thuong', 'Cac loai banh thuong dung de huan luyen hoac lam qua.'),
('CAT7', 'Thuc pham bo sung', 'Vitamin va cac chat bo sung ho tro suc khoe.'),
('CAT8', 'Quan ao/Thoi trang', 'Cac loai quan ao va phu kien thoi trang cho thu cung.');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `user_id`, `customer_name`, `email`, `phone`, `date_of_birth`, `gender`, `created_at`) VALUES
('CUS1', 'UA4', 'Minh Nguyen', 'minhnguyen@gmail.com', '0322547805', '1995-01-01', 'male', '2024-03-01 08:00:00'),
('CUS10', 'UA99', 'Ken Bui', 'kebuine@gmail.com', '0321456987', '1996-07-07', 'male', '2024-03-07 11:00:00'),
('CUS11', '1', 'Nguyễn Văn E', 'nguyenvane@example.com', '0987654325', '1995-01-01', 'Male', '2025-06-08 19:44:51'),
('CUS12', '2', 'trinh', 'nhungdoanne@gmail.com', '0123123123', '2025-05-29', 'Male', '2025-06-08 19:46:28'),
('CUS2', 'UA5', 'Lan Tran', 'tranthilan78@gmail.com', '0345698574', '1996-02-02', 'female', '2024-03-02 08:30:00'),
('CUS3', 'UA6', 'Huy Le', 'lehuygialai@gmail.com', '913333333', '1997-03-03', 'male', '2024-03-03 09:00:00'),
('CUS4', 'UA7', 'Hoa Pham', 'hoahau_hcm@gmail.com', '032598745', '1998-04-04', 'female', '2024-03-04 09:30:00'),
('CUS5', 'UA8', 'Tuan Hoang', 'hoangtuan.dev@gmail.com', '025896545', '1999-05-05', 'male', '2024-03-05 10:00:00'),
('CUS6', 'UA9', 'Mai Vo', 'vothimai@gmail.com', '025896747', '1995-06-06', 'female', '2024-03-06 10:30:00'),
('CUS7', 'UA10', 'Dung Bui', 'dungbuitran99@gmail.com', '917777777', '1996-07-07', 'male', '2024-03-07 11:00:00'),
('CUS8', 'UA97', 'Giang Trong', 'giangtrong@gmail.com', '915558555', '1999-05-05', 'male', '2024-03-05 10:00:00'),
('CUS9', 'UA98', 'Theodore Vo', 'theodorevo.ueh@gmail.com', '988666666', '1995-06-06', 'female', '2024-03-06 10:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` varchar(50) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `province_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `district_name`, `province_id`) VALUES
('1', 'Quận 1', '1'),
('2', '123', '2'),
('D01', 'Quan 1', 'P01'),
('D02', 'Quan Go Vap', 'P01'),
('D03', 'Quan Ba Dinh', 'P02'),
('D04', 'Quan Hoan Kiem', 'P02'),
('D05', 'Quan Hai Chau', 'P03');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` varchar(10) NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `address_id` varchar(10) NOT NULL,
  `promotion_id` varchar(10) DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `address_id`, `promotion_id`, `order_date`, `status`, `total_amount`, `payment_method`, `payment_status`) VALUES
('9F70B532D7', 'CUS2', 'A03', NULL, '2025-06-08 16:18:01', 'pending', 15000.00, 'COD', 'unpaid'),
('A3DBC92548', 'CUS2', 'A03', NULL, '2025-06-08 18:31:59', 'delivered', 60000.00, 'COD', 'paid'),
('C09E07E799', 'CUS2', 'A03', NULL, '2025-06-08 15:55:19', 'delivered', 15000.00, 'COD', 'paid'),
('D7626B27C1', 'CUS2', 'A03', NULL, '2025-06-08 17:18:10', 'processing', 1245000.00, 'COD', 'unpaid'),
('DD6EEEA632', 'CUS2', 'A03', NULL, '2025-06-08 17:25:20', 'pending', 250000.00, 'COD', 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` varchar(10) NOT NULL,
  `order_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
('0232B89E3B', '9F70B532D7', 'PRD20', 1, 15000.00),
('063BB9BB10', 'D7626B27C1', 'PRD20', 3, 15000.00),
('0F80788B30', 'D7626B27C1', 'PRD13', 8, 150000.00),
('11B0537BE7', 'C09E07E799', 'PRD20', 1, 15000.00),
('4D4B61886A', 'A3DBC92548', 'PRD20', 1, 15000.00),
('A46D0F58CA', 'A3DBC92548', 'PRD28', 1, 25000.00),
('A51DCBC9A3', 'DD6EEEA632', 'PRD1', 1, 50000.00),
('BD4B987F9F', 'DD6EEEA632', 'PRD10', 1, 200000.00),
('BE6A4E2563', 'A3DBC92548', 'PRD12', 1, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(10) NOT NULL,
  `category_id` varchar(10) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `pet_type` varchar(50) DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `product_name`, `description`, `pet_type`, `base_price`, `discount_price`, `stock`, `image_url`, `created_at`, `is_active`) VALUES
('PRD1', 'CAT1', 'Thuc an hat cho cho truong thanh vi gaaaaa', 'Thuc an kho can bang dinh duong cho cho lon, huong vi ga thom ngon.', 'Cho', 50000.00, 45000.00, 149, 'https://bizweb.dktcdn.net/100/091/443/products/ga.jpg?v=1655985149713', '2022-11-15 08:30:00', 1),
('PRD10', 'CAT5', 'Giuong nem em ai hinh tron', 'Tao khong gian ngu thoai mai va am ap.', 'Meo', 200000.00, 180000.00, 84, 'https://cdn.chiaki.vn/unsafe/0x960/left/top/smart/filters:quality(75)/https://chiaki.vn/upload/product/2022/01/giuong-dem-nam-em-ai-cao-cap-cho-cho-meo-61dbd135eee55-10012022132453.jpg', '2023-02-28 07:40:00', 1),
('PRD11', 'CAT6', 'Banh xuong canxi', 'Bo sung canxi giup xuong chac khoe.', 'Cho', 35000.00, 30000.00, 170, 'https://bizweb.dktcdn.net/thumb/1024x1024/100/487/588/products/1-1687245718798.jpg?v=1687245723067', '2022-10-08 15:25:00', 1),
('PRD12', 'CAT6', 'Snack thuong vi ca hoi', 'Banh thuong mem, kich thich vi giac.', 'Meo', 20000.00, 18000.00, 247, 'https://www.petmart.vn/wp-content/uploads/2023/04/snack-banh-thuong-cho-meo-vi-ca-hoi-gimcat-nutri-pockets-salmon-treats.jpg', '2023-06-05 11:15:00', 1),
('PRD13', 'CAT7', 'Vitamin tong hop dang vien', 'Bo sung cac vitamin va khoang chat can thiet.', 'Ca 2', 150000.00, 135000.00, 56, 'https://cdn.medigoapp.com/product/Agimvita_1_510x510_3e42009aaf.jpg', '2022-08-25 09:50:00', 1),
('PRD14', 'CAT8', 'Ao thun cotton hoa tiet', 'Chat lieu mem mai, thoang mat.', 'Cho', 100000.00, 95000.00, 100, 'https://product.hstatic.net/200000264739/product/ao_thun_ni_hoa_tiet_cho_cho_meo_4ce28f00bbe14de58804e23edebf61f9_master.jpg', '2023-01-25 18:00:00', 1),
('PRD15', 'CAT8', 'Vay cong chua co no', 'Thiet ke de thuong, dieu da.', 'Meo', 130000.00, 120000.00, 55, 'https://product.hstatic.net/200000263355/product/z5582607580113_f4cdce1f6854317b080bdae661811b06_bf98e4671c6d4f58b64a49af888d06bc_master.jpg', '2022-11-30 12:30:00', 1),
('PRD16', 'CAT1', 'Thuc an hat cho cho con', 'Danh cho cho con duoi 1 tuoi, kich thuoc hat nho de an.', 'Cho', 60000.00, 55000.00, 120, 'https://product.hstatic.net/1000104513/product/hat-ganador-puppy---thuc-an-hat-cho-cho-con-milk-with-dha__4__d7e68b77206f4bf1b1e6571ceb4acca5_1024x1024.jpg', '2023-03-10 10:45:00', 1),
('PRD17', 'CAT1', 'Pate tuoi ngon vi bo', 'Pate tuoi ngon, bo sung dinh duong.', 'Cho', 30000.00, 28000.00, 200, 'https://petnhasen.com/wp-content/uploads/2024/11/900-599x599.jpg', '2022-07-15 16:00:00', 1),
('PRD18', 'CAT1', 'Thuc an uot cho meo con vi ga', 'De tieu hoa, phu hop cho meo con dang phat trien.', 'Meo', 35000.00, 32000.00, 160, 'https://cdn.onemars.net/sites/whiskas_vn_282Hk_mwh5/image/mockup_wks_pouch_ad_tuna_ckm_new-look_-80g_f_1705679127949_1710836976798.png', '2023-01-05 09:20:00', 1),
('PRD19', 'CAT2', 'Xuong gam lam sach rang', 'Giup loai bo mang bam va cao rang.', 'Cho', 40000.00, 38000.00, 110, 'https://petcorner.vn/wp-content/uploads/2023/04/xuong-orgo.png', '2022-09-20 14:55:00', 1),
('PRD2', 'CAT1', 'Thuc an hat cho meo truong thanh vi ca ngu', 'Thuc an kho giup meo duy tri can nang va long bong muot.', 'Meo', 45000.00, 40000.00, 180, 'https://cdn.tgdd.vn/Products/Images/12398/273074/bhx/thuc-an-cho-meo-truong-thanh-me-o-vi-ca-ngu-tui-12kg-202203200115359774.jpg', '2023-03-20 14:45:00', 1),
('PRD20', 'CAT2', 'Bong len tron nhieu mau', 'Nhe nhang, an toan cho meo von va can.', 'Meo', 15000.00, 12000.00, 291, 'https://down-vn.img.susercontent.com/file/802bb64cd1501e2f31b1e401476c526c', '2023-05-01 11:10:00', 1),
('PRD21', 'CAT3', 'Day dat du ban rong', 'Chac chan, thoai mai khi dat di dao.', 'Cho', 120000.00, 110000.00, 70, 'https://vn-test-11.slatic.net/p/c13e7461b385b907d7529deeab8ea9a0.jpg', '2022-12-10 17:35:00', 1),
('PRD22', 'CAT3', 'Ro mom vai thoang khi', 'Giup kiem soat hanh vi khong mong muon.', 'Cho', 90000.00, 85000.00, 60, 'https://lovepetsshop.vn/wp-content/uploads/2023/10/Ro-mom-cho-bang-vai-luoi-thoang-khi-1.png', '2023-04-15 13:05:00', 1),
('PRD23', 'CAT4', 'Luoc chai long rung', 'Loai bo long chet hieu qua.', 'Ca 2', 120000.00, 110000.00, 80, 'https://www.petmart.vn/wp-content/uploads/2019/10/luoc-chai-long-rung-cho-cho-meo-furminator-edsheddingtool.jpg', '2022-06-25 19:00:00', 1),
('PRD24', 'CAT4', 'Binh xit khu mui', 'Loai bo mui kho chiu.', 'Ca 2', 80000.00, 75000.00, 95, 'https://bizweb.dktcdn.net/100/422/613/products/binh-xit-khu-mui-giay-ximo-khang-khuan-cong-nghe-nano-nhat-ban.jpg?v=1690626511970', '2023-02-10 07:40:00', 1),
('PRD25', 'CAT5', 'Nha go mini co mai che', 'Thiet ke dang yeu, bao ve khoi thoi tiet.', 'Cho', 450000.00, 420000.00, 40, 'https://hoangphucwood.vn/wp-content/uploads/2016/10/Chuong-cho-PH05-1-scaled.jpg', '2022-10-20 15:25:00', 1),
('PRD26', 'CAT5', 'To am mem mai', 'Tao khong gian ngu am ap.', 'Meo', 180000.00, 165000.00, 60, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lmefyx2we3mn96', '2023-06-15 11:15:00', 1),
('PRD27', 'CAT6', 'Snack da bo cuon', 'Giup lam sach rang va thoa man nhu cau gam nham.', 'Cho', 60000.00, 58000.00, 105, 'https://www.doggyman.com.vn/uploads/source/uploads/products/san-pham-cho-cho/thuc-an/que-da-bo-nhung-thit-ga-10-cay-81652-doggyman-00.jpg', '2022-08-10 09:50:00', 1),
('PRD28', 'CAT6', 'Pate thuong dang que', 'Tien loi khi cho an vat.', 'Meo', 25000.00, 20000.00, 209, 'https://product.hstatic.net/1000141988/product/pate_dong_que_pate_royal_dang_nhuyen_hu_180_g_b4e4643f4d064493a40e07253edfabaf_master.png', '2023-01-30 18:00:00', 1),
('PRD29', 'CAT7', 'Omega 3 & 6', 'Ho tro suc khoe da va long.', 'Meo', 120000.00, 115000.00, 75, 'https://www.hangngoainhap.com.vn/images/202008/goods_img/75-p2-1597661902.jpg', '2022-12-05 12:30:00', 1),
('PRD3', 'CAT2', 'Bong cao su do choi cho cho co lon', 'Ben bi, thich hop cho cho nang dong gam nham.', 'Cho', 70000.00, 65000.00, 90, 'https://down-vn.img.susercontent.com/file/sg-11134201-7qvec-lgse2lsjbnzj9f', '2022-07-01 10:00:00', 1),
('PRD30', 'CAT7', 'Men tieu hoa', 'Can bang he vi sinh duong ruot.', 'Ca 2', 90000.00, 80000.00, 90, 'https://emed.bvbnd.vn/media/uploads/2021/01/21/men-vi-sinh.jpg', '2023-03-25 10:45:00', 1),
('PRD4', 'CAT2', 'Can cau meo do choi co long vu', 'Kich thich ban nang san moi cua meo.', 'Meo', 30000.00, 25000.00, 220, 'https://paddy.vn/cdn/shop/files/can-cau-meo-do-choi-kem-chuong-long-vu.png?v=1704521218', '2023-01-10 16:20:00', 1),
('PRD5', 'CAT3', 'Vong co da co chuong', 'Thiet ke thoi trang, co chuong bao hieu vi tri.', 'Ca 2', 80000.00, 75000.00, 77, 'https://cunyeushop.vn/cdn/images/202111/goods_img/vong-co-da-lon-co-chuong-cho-meo-G5411-1636984530877.jpg', '2022-09-05 11:55:00', 1),
('PRD6', 'CAT4', 'Khay ve sinh cho meo co lon co xeng', 'Tien loi, de dang ve sinh.', 'Meo', 150000.00, 140000.00, 100, 'https://down-vn.img.susercontent.com/file/32da94fd623027c75874b8afad6bc2b7', '2023-05-12 09:10:00', 1),
('PRD7', 'CAT4', 'Cat ve sinh khu mui 5L', 'Khu mui tot, von cuc nhanh.', 'Meo', 70000.00, 68000.00, 140, 'https://iupets.vn/wp-content/uploads/2021/04/cat-ve-sinh-khu-mui-neko-5l-danh-cho-meo.jpg', '2022-12-22 17:35:00', 1),
('PRD8', 'CAT3', 'Sua tam diu nhe cho cho va meo', 'Giup long sach se va mem muot.', 'Ca 2', 110000.00, 100000.00, 95, 'https://product.hstatic.net/200000264739/product/sua_tam_doggyman_4in1_1_d4655af06b9d4012a2f431e5ef1eced3_master.jpg', '2023-04-03 13:05:00', 1),
('PRD9', 'CAT5', 'Chuong sat son tinh dien co trung', 'Chac chan, thong thoang, de dang di chuyen.', 'Cho', 350000.00, 320000.00, 50, 'https://cunyeushop.vn/cdn/images/202111/goods_img/chuong-sat-son-tinh-dien-co-tay-cam-lon-45x75cm-G5364-1636686172631.jpg', '2022-06-18 19:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` varchar(10) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percent` float NOT NULL,
  `total_voucher` int(11) NOT NULL,
  `used_voucher` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotion_id`, `code`, `description`, `discount_percent`, `total_voucher`, `used_voucher`, `start_date`, `end_date`, `is_active`) VALUES
('PR01', 'PET10', 'Giam 10% don hang bat ky', 10, 100, 25, '2025-06-01', '2025-06-30', 1),
('PR02', 'CATFOOD15', 'Giam 15% cho thuc an meo', 15, 50, 50, '2025-05-01', '2025-06-01', 1),
('PR03', 'DOGLOVE20', 'Giam 20% phu kien cho', 20, 30, 10, '2025-05-20', '2025-06-03', 1),
('PR04', 'NEWCUST5', 'Giam 5% cho khach hang moi', 5, 100, 99, '2025-01-01', '2025-12-31', 1),
('PR05', 'WELCOME50', 'Giam 50% don dau tien', 50, 10, 10, '2024-12-01', '2025-01-31', 1),
('PR06', 'JULYSALE30', 'Giam 30% thang 7', 30, 200, 0, '2025-07-01', '2025-07-31', 1),
('PR07', 'SUMMER25', 'Uu dai he toan danh muc', 25, 150, 75, '2025-06-01', '2025-06-10', 1),
('PR08', 'DUPLICATE', 'Ma test trung A', 10, 10, 1, '2025-06-01', '2025-06-10', 1),
('PR09', 'DUPLICATE', 'Ma test trung B', 15, 5, 0, '2025-06-05', '2025-06-12', 1),
('PR10', 'FLASH40', 'Giam soc 40% trong 24h', 40, 20, 5, '2025-06-03', '2025-06-03', 1),
('PR11', 'ADD12', 'Giam soc 10 %', 10, 5, 0, '2025-06-03', '2025-06-03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` varchar(50) NOT NULL,
  `province_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `province_name`) VALUES
('1', 'TP. Hồ Chí Minh'),
('2', '123'),
('P01', 'Ho Chi Minh'),
('P02', 'Ha Noi'),
('P03', 'Da Nang');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` varchar(10) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `user_name`, `password_hash`, `role`, `status`, `created_at`, `last_login`) VALUES
('1', 'testuser6', 'password123', 'user', 'active', '2025-06-08 19:44:51', NULL),
('2', 'trinh', '12345678', 'user', 'active', '2025-06-08 19:46:28', NULL),
('UA1', 'nhungdoanne', 'hash_pw1', 'admin', 'active', '2024-01-01 09:00:00', '2024-06-01 08:00:00'),
('UA10', 'jennifer_vo', 'hash_pw10', 'user', 'active', '2024-01-10 13:30:00', '2024-06-01 10:00:00'),
('UA2', 'namlovespet', 'hash_pw2', 'admin', 'active', '2024-01-02 09:30:00', '2024-06-01 08:10:00'),
('UA3', 'meocuamos', 'hash_pw3', 'admin', 'inactive', '2024-01-03 10:00:00', '2024-06-01 08:20:00'),
('UA4', 'luvmypet', 'hash_pw4', 'user', 'active', '2024-01-04 10:30:00', '2024-06-01 09:00:00'),
('UA5', 'nhatran', 'hash_pw1233', 'user', 'active', '2024-01-05 11:00:00', '2024-06-01 09:10:00'),
('UA6', 'thinhtran123', 'hash_pw6', 'user', 'active', '2024-01-06 11:30:00', '2024-06-01 09:20:00'),
('UA7', 'nhunghong52', 'hash_pw7', 'user', 'active', '2024-01-07 12:00:00', '2024-06-01 09:30:00'),
('UA8', 'giangtruong322', 'hash_pw8', 'user', 'active', '2024-01-08 12:30:00', '2024-06-01 09:40:00'),
('UA9', 'charlie_dath', 'hash_pw9', 'user', 'inactive', '2024-01-09 13:00:00', '2024-06-01 09:50:00'),
('UA97', 'giangtrong<3', 'hash_pw8', 'user', 'active', '2024-01-08 12:30:00', '2024-06-01 09:40:00'),
('UA98', 'nobita', 'hash_pw9', 'user', 'inactive', '2024-01-09 13:00:00', '2024-06-01 09:50:00'),
('UA99', 'doraemon', 'hash_pw10', 'user', 'active', '2024-01-10 13:30:00', '2024-06-01 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `ward_id` varchar(50) NOT NULL,
  `ward_name` varchar(100) NOT NULL,
  `district_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`ward_id`, `ward_name`, `district_id`) VALUES
('1', 'Phường Bến Thành', '1'),
('2', '123', '2'),
('W01', 'Phuong Ben Nghe', 'D01'),
('W02', 'Phuong Ben Thanh', 'D01'),
('W03', 'Phuong 10', 'D02'),
('W04', 'Phuong 14', 'D02'),
('W05', 'Phuong Dien Bien', 'D03'),
('W06', 'Phuong Truc Bach', 'D03'),
('W07', 'Phuong Hang Trong', 'D04'),
('W08', 'Phuong Phuoc Ninh', 'D05');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` varchar(10) NOT NULL,
  `customer_id` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `customer_id`, `created_at`) VALUES
('WL1', 'CUS1', '2025-05-20 10:00:00'),
('WL2', 'CUS5', '2025-05-21 11:00:00'),
('WL3', 'CUS2', '2025-05-22 12:00:00'),
('WL4', 'CUS3', '2025-05-23 13:00:00'),
('WL5', 'CUS4', '2025-05-24 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_item`
--

CREATE TABLE `wishlist_item` (
  `wishlist_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist_item`
--

INSERT INTO `wishlist_item` (`wishlist_id`, `product_id`) VALUES
('WL1', 'PRD4'),
('WL1', 'PRD5'),
('WL1', 'PRD6'),
('WL2', 'PRD1'),
('WL2', 'PRD2'),
('WL3', 'PRD13'),
('WL3', 'PRD20'),
('WL3', 'PRD28'),
('WL3', 'PRD8'),
('WL4', 'PRD11'),
('WL4', 'PRD12'),
('WL5', 'PRD4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `fk_address_customer` (`customer_id`),
  ADD KEY `fk_address_ward` (`ward_id`) USING BTREE;

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_admin_user` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_cart_customer` (`customer_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `fk_ci_cart` (`cart_id`),
  ADD KEY `fk_ci_product` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `fk_customer_user` (`user_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `fk_district_province` (`province_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_ord_customer` (`customer_id`),
  ADD KEY `fk_ord_address` (`address_id`),
  ADD KEY `fk_ord_promotion` (`promotion_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `fk_od_order` (`order_id`),
  ADD KEY `fk_od_product` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`ward_id`),
  ADD KEY `fk_ward_district` (`district_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `fk_wishlist_customer` (`customer_id`);

--
-- Indexes for table `wishlist_item`
--
ALTER TABLE `wishlist_item`
  ADD PRIMARY KEY (`wishlist_id`,`product_id`),
  ADD KEY `fk_wi_product` (`product_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `fk_address_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_address_ward` FOREIGN KEY (`ward_id`) REFERENCES `ward` (`ward_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `fk_ci_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ci_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `fk_district_province` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_ord_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ord_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_od_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_od_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `ward`
--
ALTER TABLE `ward`
  ADD CONSTRAINT `fk_ward_district` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
