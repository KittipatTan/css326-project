-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2023 at 03:22 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spytifor`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE `artist` (
  `artist_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `about` varchar(500) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `name`, `about`, `image_path`) VALUES
(9, 'Fuji Kaze ', 'Fujii Kaze is a Japanese singer-songwriter and musician under Universal Music Japan. Raised in Satoshō, Okayama in Japan, he began uploading covers to YouTube since the age of 12.', 'IMG-655b436e09d278.61545772.jpg'),
(10, 'LiSa ', 'Risa Oribe, better known by her stage name Lisa, is a Japanese singer and songwriter from Seki, Gifu, signed to Sacra Music under Sony Music Artists. After aspiring to become a musician early in life, she started her musical career as the vocalist of the indie band Chucky.', 'IMG-655b45485aec70.14474931.jpg'),
(11, 'Hiroshi Kitadani ', 'Hiroshi Kitadani is a Japanese singer, who primarily performs theme songs and other songs in anime. He also works behind the scenes of many songs. He currently works with JAM Project.', 'IMG-655b4789f24bd2.07023134.jpeg'),
(12, 'Le Sserafilm', 'eee', 'IMG-655e067bd79833.97632162.jpg'),
(19, 'Aj. Daeng', 'for Dhramma learner', 'IMG-655ec2d706c101.55315237.jpg'),
(21, 'YOASOBI', 'ikura-san & Ayase-san', 'IMG-655ed666357f45.72604864.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre_name` varchar(20) NOT NULL,
  `color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_name`, `color`) VALUES
('Blue', 'blue'),
('J-Pop', 'pink'),
('K-POP', 'black'),
('Rap', 'red'),
('Rock', 'green');

-- --------------------------------------------------------

--
-- Table structure for table `liked`
--

CREATE TABLE `liked` (
  `music_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `liked_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `liked`
--

INSERT INTO `liked` (`music_id`, `uid`, `liked_date`) VALUES
(12, 2, '2023-11-20'),
(12, 5, '2023-11-23'),
(12, 6, '2023-11-23'),
(13, 2, '2023-11-20'),
(13, 4, '2023-11-22'),
(13, 6, '2023-11-20'),
(14, 1, '2023-11-20'),
(14, 2, '2023-11-20'),
(14, 5, '2023-11-23'),
(14, 6, '2023-11-20'),
(16, 5, '2023-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `music_id` int(11) NOT NULL,
  `credit` varchar(300) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `genre_name` varchar(20) DEFAULT NULL,
  `artist_id` int(11) DEFAULT NULL,
  `music_name` varchar(200) NOT NULL,
  `path` varchar(300) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `music`
--

INSERT INTO `music` (`music_id`, `credit`, `duration`, `genre_name`, `artist_id`, `music_name`, `path`, `image_path`) VALUES
(12, 'Garden', '3.50', 'J-Pop', 9, 'Garden', 'audio-655b44690bf076.90222563.mp3', 'IMG-655b44690c2653.62222652.jpg'),
(13, 'Shirushi', '4.51', 'J-Pop', 10, 'Shirushi', 'audio-655b45d684ac35.20229586.mp3', 'IMG-655b45d684e0c3.05109029.jpg'),
(14, 'Artist: Hiroshi Kitadani\r\nAlbum: One Piece Music ＆Song Collection 1\r\nReleased: 2000', '4.00', 'J-Pop', 11, 'We Are!', 'audio-655b47ca661e02.55178867.mp3', 'IMG-655b47ca665dc8.64942990.jpg'),
(15, 'Creative Director : NU KIM\r\n\r\nVisual Creative Coordinating : Yujoo Kim\r\nStyle Directing : Yoon Cho, Soo Lee\r\nBrand Experience Design : Yoovin Baek, Hyemin Yoo\r\nContent Production : Yurok Jang, Jisoo Min', '2.42', 'K-POP', 12, 'Perfect Night', 'audio-655e072b695171.02498210.mp3', 'IMG-655e072b699292.64459037.jpg'),
(16, 'Associated  Performer, Recording  Arranger, Producer: Yaffle\r\nAssociated  Performer, Vocals, Piano: Fujii Kaze\r\nAssociated  Performer, Electric  Guitar: DURAN', '3.09', 'J-Pop', 9, 'Hedemo Ne-Yo (LASA edit)', 'audio-655e0cdf72f895.19133032.mp3', 'IMG-655e0cdf732c63.61693270.jpeg'),
(21, ' Biri-Biri', '3.11', 'J-Pop', 21, ' Biri-Biri', 'audio-655ed789364cb5.86937435.mp3', 'IMG-655ed7893692c4.90607795.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `playlist_id` int(11) NOT NULL,
  `playlist_name` varchar(20) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `ispublic` tinyint(1) DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`playlist_id`, `playlist_name`, `description`, `ispublic`, `u_id`, `created_date`, `image_path`) VALUES
(1, 'James Playlist', 'my playlist', 0, 1, '2023-11-15', 'IMG-655b5d313b65d5.12492748.jpg'),
(23, 'EL gato', 'dump cat', 0, NULL, '2023-11-20', 'IMG-655b0e9fa04b79.47148656.jpg'),
(28, 'LOVE ALL SERVE ALL', 'Love All Serve All is the second studio album by Japanese singer-songwriter Fujii Kaze. It was released on March 23, 2022, through Hehn Records and Universal Sigma. Six singles were released', 0, NULL, '2023-11-20', 'IMG-655b43e69e6ab7.02170443.jpg'),
(30, 'My playlist', 'eiei', 0, 1, '2023-11-20', 'IMG-655b5b610ee056.32012956.jpeg'),
(55, 'John Cena\'s Playlist', '', 0, 4, '2023-11-20', NULL),
(57, 'Mike', '', 0, 5, '2023-11-20', 'IMG-655b906b87ae04.13131716.jpg'),
(60, 'El gato', '', 0, 5, '2023-11-21', 'IMG-655b9ce30705c8.77818962.jpg'),
(63, 'Aj. daeng', 'Kratuk jit Krachack jai', 0, NULL, '2023-11-23', 'IMG-655ec22e5d5079.83726774.png'),
(67, 'My Playlist #1', '', 1, 6, '2023-11-23', NULL),
(68, 'My Playlist #2', '', 1, 6, '2023-11-23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_music`
--

CREATE TABLE `playlist_music` (
  `playlist_id` int(11) NOT NULL,
  `music_id` int(11) NOT NULL,
  `added_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `playlist_music`
--

INSERT INTO `playlist_music` (`playlist_id`, `music_id`, `added_date`) VALUES
(28, 12, '2023-11-23'),
(28, 16, '2023-11-23'),
(30, 12, '2023-11-20'),
(63, 14, '2023-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Superadmin'),
(2, 'Admin'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_password` char(100) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `name`, `email`, `user_password`, `role_id`) VALUES
(1, 'Perapat Taytad', 'jame@gmail.com', '123', 3),
(2, 'thummy', 'th@com', '888', 3),
(3, 'poi', 't7rdd@s', '555', 3),
(4, 'John Cena', 'johncena@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$ak1YRHVFZ2tFMHpiVEpzTw$nreqAqFLSh13GlhgDq2ZpfyjD+vS3RU6FNICrHb4WTM', 2),
(5, 'James', 'j@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cUh0dWM4bW0yNTgyZkExVw$lgp2CCk8D9kWlegyCpJoNilpVD2v8mqQqpwL2gvTAB0', 2),
(6, 'thummy', 'dd@dd', '$argon2id$v=19$m=65536,t=4,p=1$OTJtVkpFbmo0YjJZUTZvcA$sYV1HVHy/3CApgrA4ELAAAFClLRtrDX8ALkk1KJabgQ', 3),
(7, 'Spytifor', 'superduper@spytifor.com', '$argon2id$v=19$m=65536,t=4,p=1$dWVHUUVlLi4uUnZ4dlFERw$wf35piqO7tPzoCM7YNtRHdy4nFVTR43xpmF0SlK4yPs', 1),
(9, 'CSS326TEST', 'css326@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Z0dVRHByZ1RvUmppaDg1cw$/l0bb91BI6l2CpGr+mjp72gs4v3AOWiAOpKzG+BSNs4', 3),
(10, 'UserTest', 'usertest@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$M1ppSXJHOEZNQjBMZzVRTg$RAI89bWjaADZq20ggNm+LfnHQ+7273uFO5iAKDQ7Fi4', 3),
(11, 'Test', 'test@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WUoxS3dkdFlqUEpPT05Hcg$tMALAmnzfALcwnVvoG3uklqcSS7BYzd5YpX79UEXwvU', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_name`);

--
-- Indexes for table `liked`
--
ALTER TABLE `liked`
  ADD PRIMARY KEY (`music_id`,`uid`),
  ADD KEY `fk_liked_user` (`uid`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`music_id`),
  ADD KEY `fk_genre_music` (`genre_name`),
  ADD KEY `fk_artist_music` (`artist_id`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`playlist_id`),
  ADD KEY `fk_playlist_user` (`u_id`);

--
-- Indexes for table `playlist_music`
--
ALTER TABLE `playlist_music`
  ADD PRIMARY KEY (`playlist_id`,`music_id`),
  ADD KEY `fk_playlistmusic_music` (`music_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artist`
--
ALTER TABLE `artist`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `music_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `playlist_music`
--
ALTER TABLE `playlist_music`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `liked`
--
ALTER TABLE `liked`
  ADD CONSTRAINT `fk_liked_music` FOREIGN KEY (`music_id`) REFERENCES `music` (`music_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_liked_user` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `fk_artist_music` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_genre_music` FOREIGN KEY (`genre_name`) REFERENCES `genre` (`genre_name`) ON DELETE CASCADE;

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `fk_playlist_user` FOREIGN KEY (`u_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `playlist_music`
--
ALTER TABLE `playlist_music`
  ADD CONSTRAINT `fk_playlistmusic_music` FOREIGN KEY (`music_id`) REFERENCES `music` (`music_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_playlistmusic_playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`playlist_id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
