create database shop;
use shop;
SET SQL_SAFE_UPDATES = 0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- laravel tao

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- laravel tao

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE categories 
ADD COLUMN created_at DATETIME NULL DEFAULT NULL,
ADD COLUMN updated_at DATETIME NULL DEFAULT NULL;
INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Manga', 'Các loại truyện tranh Nhật Bản'),
(2, 'Comic', 'Các loại truyện tranh phương Tây'),
(3, 'Anime', 'Phim hoạt hình Nhật Bản'),
(4, 'Cartoon', 'Phim hoạt hình phương Tây'),
(5, 'Light Novel', 'Tiểu thuyết Nhật Bản'),
(6, 'Novel', 'Tiểu thuyết phương Tây'),
(7, 'Artbook', 'Sách ảnh'),
(8, 'Tutorial Book', 'Sách hướng dẫn'),
(9, 'Movies', 'Phim điện ảnh'),
(10, 'Magazine', 'Tạp Chí'),
(11, 'Boardgame', 'Các loại trò chơi thú vị');
select * from categories;
delete from categories;

CREATE TABLE `comment` (
  `cmt_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
select * from comment;

CREATE TABLE `favorite` (
  `user_id` int(11) NOT NULL,
  `category_id` int(11),
  `score` int(10) default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `favorite` (`user_id`, `category_id`, `score`, `product_id`)
VALUES (35, 4, 1, 1);
select * from favorite;
delete from favorite;

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `order_status` varchar(20) DEFAULT 'new',
  `created_at` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE invoice CHANGE `invoice_id` `invoice_id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `invoice` ADD PRIMARY KEY (`invoice_id`);
ALTER TABLE `invoice` ADD COLUMN `invoice_name` VARCHAR(255) DEFAULT NULL AFTER `invoice_id`;
select * from invoice;
SHOW CREATE TABLE invoice;
delete from invoice;

CREATE TABLE `invoice_detail` (
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

select * from invoice_detail;
delete from invoice_detail;

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- laravel tao
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_05_21_061113_create_personal_access_tokens_table', 1),
(2, '2025_05_21_101936_create_cache_table', 2);

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- laravel tao

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
select * from products;
ALTER TABLE products
MODIFY product_id INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE products ADD COLUMN rating FLOAT DEFAULT 0;

INSERT INTO `products` (`product_id`, `staff_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `image_url`, `status`, `created_at`) VALUES
(1, 1, 1, 'Trọn bộ 34 tập Manga Attack on Titan', 'Kiệt tác để đời của tác giả Isayama Hajime', 1000000.00, 10, 'images/AoT_manga.jpg', 1, '2025-05-21 16:50:19'),
(2, 1, 7, 'Zootopia Artbook', 'Sách mở rộng về thế giới của Zootopia', 200000.00, 10, 'images/ZAB.jpg', 1, '2025-05-21 16:50:19'),
(3, 1, 2, 'Zootopia Comic', 'Comic của Zootopia', 90000.00, 10, 'images/ZC.jpg', 1, '2025-05-21 16:50:19'),
(4, 1, 3, 'Attack on Titan: The Last Attack', 'Final movie của siêu phẩm Attack on Titan', 100000.00, 10, 'images/TLA.jpg', 1, '2025-05-21 16:50:19'),
(5, 1, 1, 'Trọn bộ 22 tập Manga Beastars', 'Manga tuyệt vời của Paru Itagaki', 800000.00, 10, 'images/BM.jpg', 1, '2025-05-21 16:50:19'),
(6, 1, 1, 'The Promised Neverland Complete Box', 'Complete Box Set của 20 tập truyện Miền Đất Hứa', 900000.00, 10, 'images/TPN.jpg', 1, '2025-05-21 16:50:19'),
(7, 1, 3, 'The Seven Deadly Sins', 'Full Seasons Anime Thất Hình Đại Tội của tác giả Suzuki Nakaba', 80000.00, 10, 'images/TSDS.jpg', 1, '2025-05-21 16:50:19'),
(8, 1, 8, 'How to draw Manga Furries', 'Sách hướng dẫn vẽ Furry', 300000.00, 10, 'images/HTDFM.jpg', 1, '2025-05-21 16:50:19'),
(9, 1, 6, 'Ready Player One', 'Tiểu thuyết khoa học viễn tưởng của Ernest Cline', 130000.00, 10, 'images/RPO.jpg', 1, '2025-05-21 16:50:19'),
(10, 1, 9, 'The Kingsman', '3 phần phim đặc vụ Kingsman', 300000.00, 10, 'images/KM.jpg', 1, '2025-05-21 16:50:19'),
(11, 1, 9, 'The Bad Guys', '2 phần phim băng quái kiệt', 200000.00, 10, 'images/TBGM.jpg', 1, '2025-05-21 16:50:19'),
(12, 1, 6, 'Chronicles of Narnia', '7 phần tiểu thuyết Narnia của C.S.Lewis', 700000.00, 10, 'images/Narnia.png', 1, '2025-05-21 16:50:19'),
(13, 1, 9, 'Harry Potter', '8 phần phim điện ảnh Harry Potter của J.K.Rowling', 800000.00, 10, 'images/HPM.jpg', 1, '2025-05-21 16:50:19'),
(14, 1, 8, 'Kawaii Sensei Manga Draw Tutorial', 'Sách hướng dẫn vẽ Manga', 99000.00, 10, 'images/MD.jpg', 1, '2025-05-21 16:50:19'),
(15, 1, 3, 'Beastars', '3 phần Anime Beastars', 60000.00, 10, 'images/Beastars_2.jpg', 1, '2025-05-21 16:50:19'),
(16, 1, 5, 'Shinkai Makoto combo', '3 quyển tiểu thuyết điện ảnh của Shinkai Makoto', 330000.00, 10, 'images/SM.jpg', 1, '2025-05-21 16:50:19'),
(17, 1, 7, 'Elden Ring Official Artbook', 'Sách ảnh của GOTY Elden Ring', 175000.00, 10, 'images/ERA.jpg', 1, '2025-05-21 16:50:19'),
(18, 1, 7, 'Ori Series Artbook', 'Sách ảnh của Ori and the Blind Forest + The Will of The Wips', 420000.00, 10, 'images/OATBF.jpg', 1, '2025-05-21 16:50:19'),
(19, 1, 9, 'Sing', '2 phần phim Sing - Đấu trường âm nhạc', 1000000.00, 10, 'images/Sing.jpg', 1, '2025-05-21 16:50:19'),
(20, 1, 4, 'The Amazing World of Gumball', 'Full Seasons của Gumball', 500000.00, 10, 'images/TAWOG.jpg', 1, '2025-05-21 16:50:19'),
(21, 1, 4, 'Gravity Falls', 'Full Seasons của Gravity Falls', 440000.00, 10, 'images/GF.jpg', 1, '2025-05-21 16:50:19'),
(22, 1, 10, 'Manga & Anime Magazine', 'Tạp chí Anime Manga', 50000.00, 10, 'images/TUG.jpg', 1, '2025-05-21 16:50:19'),
(23, 1, 10, 'Game Magazine', 'Tạp chí Game', 45000.00, 10, 'images/GM.jpg', 1, '2025-05-21 16:50:19'),
(24, 1, 4, 'Phineas & Ferb', 'Full Seasons của Phineas và Ferb', 685000.00, 10, 'images/PAF.jpg', 1, '2025-05-21 16:50:19'),
(25, 1, 3, 'Souma: Food Wars', 'Full Seasons của Shokugeki no Souma', 550000.00, 10, 'images/Souma.jpg', 1, '2025-05-21 16:50:19');
select * from products;
delete from products;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- laravel tao khi xai SESSION_DRIVER=database
select * from sessions;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('47WI6ZscaHqdOTEIIlon13dnKF5rX0cNBj5ZBY2o', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2VtUlZCV3JrREw3NE85NHF3eDlWd1BzYzlxNXFmSFBPUW91M1lwVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1748524360),
('RNLr6jPc8KoFO1ppwehWhanCll44nV2T1vbvOdFb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWhjV2U0Rkwxb2t2NjZHeUdURkNHTVZnSXhjbWJjR1g1aG8yQ1g5aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1748522781);

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE staff AUTO_INCREMENT = 1;
ALTER TABLE staff MODIFY staff_id INT NOT NULL AUTO_INCREMENT;
select * from staff;
delete from staff;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;	
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE users MODIFY user_id INT NOT NULL AUTO_INCREMENT;
select * from users;
delete from users;

ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

ALTER TABLE `comment`
  ADD PRIMARY KEY (`cmt_id`),
  ADD KEY `FK_COMMENT_RELATIONS_PRODUCTS` (`product_id`),
  ADD KEY `FK_COMMENT_RELATIONS_USER` (`user_id`);

ALTER TABLE `favorite`
  ADD PRIMARY KEY (`user_id`,`category_id`),
  ADD KEY `FK_FAVORITE_RELATIONS_CATEGORI` (`category_id`);

ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `FK_INVOICE_RELATIONS_USER` (`user_id`);

ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`invoice_id`,`product_id`),
  ADD KEY `FK_INVOICE__RELATIONS_PRODUCTS` (`product_id`);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `FK_PRODUCTS_RELATIONS_CATEGORI` (`category_id`),
  ADD KEY `FK_PRODUCTS_RELATIONS_STAFF` (`staff_id`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `Email` (`email`);

ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `comment`
  MODIFY `cmt_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoice`
  MODIFY `Invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

ALTER TABLE `invoice_detail`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

ALTER TABLE `comment`
  ADD CONSTRAINT `FK_COMMENT_RELATIONS_PRODUCTS` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `FK_COMMENT_RELATIONS_USER` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `favorite`
  ADD CONSTRAINT `FK_FAVORITE_RELATIONS_CATEGORI` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `FK_FAVORITE_RELATIONS_USER` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `invoice`
  ADD CONSTRAINT `FK_INVOICE_RELATIONS_USER` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `invoice_detail`
  ADD CONSTRAINT `FK_INVOICE__RELATIONS_INVOICE` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`),
  ADD CONSTRAINT `FK_INVOICE__RELATIONS_PRODUCTS` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

ALTER TABLE `products`
  ADD CONSTRAINT `FK_PRODUCTS_RELATIONS_CATEGORI` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `FK_PRODUCTS_RELATIONS_STAFF` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;