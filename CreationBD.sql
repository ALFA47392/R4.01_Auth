CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Insérer des données
INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'bob', 'BobBatman74!'),
(2, 'prof', 'Karaba123');
