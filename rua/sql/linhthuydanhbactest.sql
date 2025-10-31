-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 21, 2024 lúc 02:22 AM
-- Phiên bản máy phục vụ: 10.4.33-MariaDB
-- Phiên bản PHP: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `linhthuydanhbactest`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `create_time` timestamp NULL DEFAULT current_timestamp(),
  `update_time` timestamp NULL DEFAULT current_timestamp(),
  `ban` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `last_time_login` timestamp NOT NULL DEFAULT '2002-07-30 17:00:00',
  `last_time_logout` timestamp NOT NULL DEFAULT '2002-07-30 17:00:00',
  `ip_address` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `thoi_vang` int(11) NOT NULL DEFAULT 0,
  `server_login` int(11) NOT NULL DEFAULT -1,
  `bd_player` double DEFAULT 1,
  `is_gift_box` tinyint(1) DEFAULT 0,
  `gift_time` varchar(255) DEFAULT '0',
  `reward` longtext DEFAULT NULL,
  `cash` int(11) NOT NULL DEFAULT 0,
  `danap` int(11) NOT NULL DEFAULT 0,
  `token` text NOT NULL DEFAULT '',
  `xsrf_token` text NOT NULL DEFAULT '',
  `newpass` text NOT NULL DEFAULT '',
  `luotquay` int(11) NOT NULL DEFAULT 0,
  `vang` bigint(20) NOT NULL DEFAULT 0,
  `event_point` int(11) NOT NULL DEFAULT 0,
  `vip` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `email`, `create_time`, `update_time`, `ban`, `is_admin`, `last_time_login`, `last_time_logout`, `ip_address`, `active`, `thoi_vang`, `server_login`, `bd_player`, `is_gift_box`, `gift_time`, `reward`, `cash`, `danap`, `token`, `xsrf_token`, `newpass`, `luotquay`, `vang`, `event_point`, `vip`) VALUES
(1, 'admin', '123', '', '2023-03-07 10:10:43', '2023-03-07 10:10:43', 0, 1, '2024-11-21 02:20:47', '2024-11-21 02:20:46', '127.0.0.1', 1, 8799000, -1, 1, 0, '0', '', 2146884647, 0, '', '', '', 0, 9957485903344, 286612, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `clan`
--

CREATE TABLE `clan` (
  `id` int(11) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `NAME_2` varchar(4) NOT NULL,
  `slogan` varchar(255) NOT NULL DEFAULT '',
  `img_id` int(11) NOT NULL DEFAULT 0,
  `power_point` bigint(20) NOT NULL DEFAULT 0,
  `max_member` smallint(6) NOT NULL DEFAULT 10,
  `clan_point` int(11) NOT NULL DEFAULT 0,
  `LEVEL` int(11) NOT NULL DEFAULT 1,
  `members` text NOT NULL,
  `tops` text NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `clan`
--

INSERT INTO `clan` (`id`, `NAME`, `NAME_2`, `slogan`, `img_id`, `power_point`, `max_member`, `clan_point`, `LEVEL`, `members`, `tops`, `create_time`) VALUES
(0, 'cc', 'okk', '', 0, 0, 10, 0, 1, '[\"{\\\"role\\\":0,\\\"receive_donate\\\":0,\\\"member_point\\\":0,\\\"body\\\":16,\\\"join_time\\\":1730432617,\\\"leg\\\":17,\\\"head\\\":28,\\\"ask_pea_time\\\":0,\\\"name\\\":\\\"test12345\\\",\\\"clan_point\\\":0,\\\"id\\\":1001729,\\\"donate\\\":0,\\\"power\\\":4492}\"]', 'cc', '2024-11-01 03:43:37'),
(1, 'cccc', 'ccc', '', 0, 0, 10, 0, 1, '[\"{\\\"role\\\":0,\\\"receive_donate\\\":0,\\\"member_point\\\":0,\\\"body\\\":16,\\\"join_time\\\":1730432979,\\\"leg\\\":17,\\\"head\\\":28,\\\"ask_pea_time\\\":0,\\\"name\\\":\\\"test12345\\\",\\\"clan_point\\\":0,\\\"id\\\":1001729,\\\"donate\\\":0,\\\"power\\\":4492}\"]', 'cc', '2024-11-01 03:49:39'),
(2, 'cccc', 'ssss', '', 0, 0, 10, 0, 1, '[\"{\\\"role\\\":0,\\\"receive_donate\\\":0,\\\"member_point\\\":0,\\\"body\\\":16,\\\"join_time\\\":1730434440,\\\"leg\\\":17,\\\"head\\\":28,\\\"ask_pea_time\\\":0,\\\"name\\\":\\\"test12345\\\",\\\"clan_point\\\":0,\\\"id\\\":1001729,\\\"donate\\\":0,\\\"power\\\":4492}\"]', 'cc', '2024-11-01 04:14:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giftcode`
--

CREATE TABLE `giftcode` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `count_left` int(11) NOT NULL,
  `detail` text NOT NULL,
  `datecreate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history_transaction`
--

CREATE TABLE `history_transaction` (
  `id` int(11) NOT NULL,
  `player_1` varchar(255) NOT NULL,
  `player_2` varchar(255) NOT NULL,
  `item_player_1` text NOT NULL,
  `item_player_2` text NOT NULL,
  `bag_1_before_tran` text NOT NULL,
  `bag_2_before_tran` text NOT NULL,
  `bag_1_after_tran` text NOT NULL,
  `bag_2_after_tran` text NOT NULL,
  `time_tran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `head` int(11) NOT NULL DEFAULT 102,
  `gender` int(11) NOT NULL,
  `have_tennis_space_ship` tinyint(1) DEFAULT 0,
  `clan_id` int(11) NOT NULL DEFAULT -1,
  `data_inventory` text NOT NULL,
  `data_location` text NOT NULL,
  `data_point` text NOT NULL,
  `data_magic_tree` text NOT NULL,
  `items_body` text NOT NULL,
  `items_bag` text NOT NULL,
  `items_box` text NOT NULL,
  `items_box_lucky_round` text NOT NULL,
  `items_daban` text NOT NULL,
  `friends` text NOT NULL,
  `enemies` text NOT NULL,
  `data_intrinsic` text NOT NULL,
  `data_item_time` text NOT NULL,
  `data_task` text NOT NULL,
  `data_mabu_egg` text NOT NULL,
  `data_charm` text NOT NULL,
  `skills` text NOT NULL,
  `skills_shortcut` text NOT NULL,
  `pet` text NOT NULL,
  `data_black_ball` text NOT NULL,
  `data_side_task` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `notify` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `baovetaikhoan` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '[]',
  `captcha` varchar(1000) NOT NULL DEFAULT '[]',
  `data_card` varchar(10000) NOT NULL DEFAULT '[]',
  `lasttimepkcommeson` bigint(20) NOT NULL DEFAULT 0,
  `bandokhobau` varchar(250) NOT NULL DEFAULT '[]',
  `doanhtrai` bigint(11) NOT NULL DEFAULT 0,
  `conduongrandoc` varchar(255) NOT NULL DEFAULT '[]',
  `masterDoesNotAttack` text NOT NULL DEFAULT 'false',
  `nhanthoivang` varchar(200) NOT NULL DEFAULT '[]',
  `ruonggo` varchar(255) NOT NULL DEFAULT '[]',
  `sieuthanthuy` varchar(255) NOT NULL DEFAULT '[]',
  `vodaisinhtu` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `rongxuong` bigint(20) NOT NULL DEFAULT 0,
  `data_item_event` varchar(1000) NOT NULL DEFAULT '[]',
  `data_luyentap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `data_clan_task` varchar(255) NOT NULL DEFAULT '[]',
  `data_vip` text DEFAULT '[]',
  `rank` int(11) NOT NULL DEFAULT 0,
  `data_achievement` text NOT NULL DEFAULT '[]',
  `giftcode` text NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `player`
--

INSERT INTO `player` (`id`, `account_id`, `name`, `head`, `gender`, `have_tennis_space_ship`, `clan_id`, `data_inventory`, `data_location`, `data_point`, `data_magic_tree`, `items_body`, `items_bag`, `items_box`, `items_box_lucky_round`, `items_daban`, `friends`, `enemies`, `data_intrinsic`, `data_item_time`, `data_task`, `data_mabu_egg`, `data_charm`, `skills`, `skills_shortcut`, `pet`, `data_black_ball`, `data_side_task`, `create_time`, `notify`, `baovetaikhoan`, `captcha`, `data_card`, `lasttimepkcommeson`, `bandokhobau`, `doanhtrai`, `conduongrandoc`, `masterDoesNotAttack`, `nhanthoivang`, `ruonggo`, `sieuthanthuy`, `vodaisinhtu`, `rongxuong`, `data_item_event`, `data_luyentap`, `data_clan_task`, `data_vip`, `rank`, `data_achievement`, `giftcode`) VALUES
(1001729, 1, 'test12345', 28, 2, 0, -1, '[2000000000,100000,100,0,0]', '[113,453,360]', '[0,4672,4672,1000,1000,100,100,15,0,0,0,108,64]', '[1,5,0,1716888404910,1716885636516]', '[\"[2,1,\\\"[\\\\\\\"[47,3]\\\\\\\"]\\\",1716885636516]\",\"[8,1,\\\"[\\\\\\\"[6,20]\\\\\\\"]\\\",1716885636516]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[12,1,\\\"[\\\\\\\"[14,1]\\\\\\\"]\\\",1716885636517]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\"]', '[\"[457,10,\\\"[\\\\\\\"[73,1]\\\\\\\"]\\\",1716885636517]\",\"[13,5,\\\"[\\\\\\\"[48,100]\\\\\\\"]\\\",1716886400072]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\"]', '[\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\",\"[-1,0,\\\"[]\\\",1732155640894]\"]', '[]', '[]', '[]', '[]', '[0,0,0,0,false,0,0,0]', '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]', '[20,0,3,0]', '[]', '[1716885636518,1716885636518,1716885636518,1716885636518,1716885636518,1716885636518,1716885636518,1716885636518,1716885636518,1716885636518]', '[\"[4,1,1732154966266,0]\",\"[5,0,0,0]\",\"[8,0,0,0]\",\"[13,0,0,0]\",\"[14,0,0,0]\",\"[21,0,0,0]\",\"[23,0,0,0]\",\"[19,0,0,0]\"]', '[4,-1,-1,-1,-1,-1,-1,-1,-1,-1]', '[]', '[\"[0,0,0]\",\"[0,0,0]\",\"[0,0,0]\",\"[0,0,0]\",\"[0,0,0]\",\"[0,0,0]\",\"[0,0,0]\"]', '[-1,0,0,0,20,0]', '2024-05-28 08:40:36', 'null', '[0,false,1716886381547]', '[]', '[]', 0, '[0,1716886381547]', 0, '[false,0,false,false]', '0', '[false,0]', '[0,50000000,100,1716886381547,0]', '[false,0,false]', '[false,0,0,0]', 0, '[0,0,0,0,0,0]', '[0,false,-1,2900,1732155646521,0,0,0,0,0]', '[-1,0,0,0,5,0]', '[0,0,false,false,false]', 11, '[\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[3,false]\",\"[87000,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\",\"[0,false]\"]', '[]');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shop_ky_gui`
--

CREATE TABLE `shop_ky_gui` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `player_name` text NOT NULL DEFAULT '',
  `tab` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `gem` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `itemOption` varchar(10000) NOT NULL DEFAULT '[]',
  `lastTime` bigint(11) NOT NULL,
  `isBuy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `super_rank`
--

CREATE TABLE `super_rank` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `rank` int(11) NOT NULL,
  `last_pk_time` bigint(20) NOT NULL,
  `last_reward_time` bigint(20) NOT NULL,
  `ticket` int(11) NOT NULL,
  `win` int(11) NOT NULL,
  `lose` int(11) NOT NULL,
  `history` text NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `super_rank`
--

INSERT INTO `super_rank` (`id`, `player_id`, `name`, `rank`, `last_pk_time`, `last_reward_time`, `ticket`, `win`, `lose`, `history`, `info`) VALUES
(1, 1001746, 'ccuuuuuu', 2, 1724993355977, 1724993355995, 3, 0, 12, '[{\"event\":\"Thua alyra[1]\",\"timestamp\":1724994474064},{\"event\":\"Thua alyra[1]\",\"timestamp\":1724994715140},{\"event\":\"Thua alyra[1]\",\"timestamp\":1724995096415},{\"event\":\"Thua alyra[1]\",\"timestamp\":1724995389420},{\"event\":\"Thua alyra[1]\",\"timestamp\":1724995423277}]', '{\"head\":28,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(2, 100000, 'alyra', 1, 1731823375867, 1731823376419, 3, 12, 0, '[{\"event\":\"Hạ ccuuuuuu[2]\",\"timestamp\":1724994474064},{\"event\":\"Hạ ccuuuuuu[2]\",\"timestamp\":1724994715140},{\"event\":\"Hạ ccuuuuuu[2]\",\"timestamp\":1724995096415},{\"event\":\"Hạ ccuuuuuu[2]\",\"timestamp\":1724995389420},{\"event\":\"Hạ ccuuuuuu[2]\",\"timestamp\":1724995423277}]', '{\"head\":867,\"body\":868,\"leg\":869,\"hp\":633452,\"dame\":29132,\"def\":1800}'),
(3, 1001747, 'fffffffff', 3, 1725088320047, 1725088320055, 3, 0, 0, '[]', '{\"head\":29,\"body\":10,\"leg\":11,\"hp\":120,\"dame\":10,\"def\":2}'),
(4, 1001748, '12345677', 4, 1725089815445, 1725089815464, 3, 0, 0, '[]', '{\"head\":28,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(5, 1001749, '123456666', 5, 1725090055451, 1725090055490, 3, 0, 0, '[]', '{\"head\":27,\"body\":16,\"leg\":17,\"hp\":320,\"dame\":15,\"def\":3}'),
(6, 1001750, 'cccccccc', 6, 1725725843008, 1725725843030, 3, 0, 0, '[]', '{\"head\":28,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(7, 1001751, 'ccccc', 7, 1730875467176, 1730875467694, 3, 0, 0, '[]', '{\"head\":30,\"body\":14,\"leg\":15,\"hp\":230,\"dame\":10,\"def\":2}'),
(8, 1001752, 'okkkkk', 8, 1731477759304, 1731477759365, 3, 0, 0, '[]', '{\"head\":28,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(9, 1001753, 'aaaaaaa', 9, 1731483971811, 1731483971831, 3, 0, 0, '[]', '{\"head\":27,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(10, 1001754, '12345678', 10, 1731484572998, 1731484573022, 3, 0, 0, '[]', '{\"head\":6,\"body\":16,\"leg\":17,\"hp\":120,\"dame\":15,\"def\":3}'),
(11, 1001729, 'test12345', 11, 1732155640899, 1732155640927, 3, 0, 0, '[]', '{\"head\":28,\"def\":3,\"hp\":120,\"dame\":15,\"body\":16,\"leg\":17}');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `clan`
--
ALTER TABLE `clan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `giftcode`
--
ALTER TABLE `giftcode`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `history_transaction`
--
ALTER TABLE `history_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`);

--
-- Chỉ mục cho bảng `shop_ky_gui`
--
ALTER TABLE `shop_ky_gui`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `super_rank`
--
ALTER TABLE `super_rank`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000005;

--
-- AUTO_INCREMENT cho bảng `giftcode`
--
ALTER TABLE `giftcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `history_transaction`
--
ALTER TABLE `history_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `player`
--
ALTER TABLE `player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001730;

--
-- AUTO_INCREMENT cho bảng `shop_ky_gui`
--
ALTER TABLE `shop_ky_gui`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `super_rank`
--
ALTER TABLE `super_rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
