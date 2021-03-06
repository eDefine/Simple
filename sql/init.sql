CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(255) NOT NULL,
  `lastName` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(32) NOT NULL,
  `session` VARCHAR(255) NULL,
  `roles` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL,
  `lastActivity` DATETIME NULL,
  `pictureName` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;