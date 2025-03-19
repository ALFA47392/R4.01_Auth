CREATE DATABASE IF NOT EXISTS `projetr401_Auth`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) 

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'bob', 'BobBatman74!'),
(2, 'prof', 'Karaba123');