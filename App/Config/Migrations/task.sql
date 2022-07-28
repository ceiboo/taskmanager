DROP TABLE IF EXISTS `jla-mvc-task`.`tasks`;
DROP TABLE IF EXISTS `jla-mvc-task`.`users`;

CREATE TABLE  `jla-mvc-task`.`users` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` enum('on','off') DEFAULT 'on',
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE  `jla-mvc-task`.`tasks` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `user_id` smallint(5) NOT NULL,
  `title` varchar(120) NOT NULL,
  `status` enum('pending','progress', 'done') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX user_id (id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
