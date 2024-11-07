SET NAMES utf8;
SET
    time_zone = '+00:00';
SET
    foreign_key_checks = 0;
SET
    sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE
    `app`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`             int                                   NOT NULL AUTO_INCREMENT,
    `username`       varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
    `email`          varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
    `password`       varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
    `remember_token` varchar(255) COLLATE utf8mb4_czech_ci          DEFAULT NULL,
    `created_at`     timestamp                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `admin`          tinyint                                        DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email_UNIQUE` (`email`),
    UNIQUE KEY `username_UNIQUE` (`username`),
    UNIQUE KEY `remember_token_UNIQUE` (`remember_token`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_czech_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `created_at`, `admin`)
VALUES (1, 'admin', 'admin@admin.cz', '$2y$10$L.dVJcR8uYqlwyiL.g5J1OJgjUPWvr0JNT0Ge6FZjeVyzGzETdKSW', '641599ed2078b', '2023-03-18 11:01:01', 1),
       (2, 'Uzivatel', 'user@user.cz', '$2y$10$1PTqokVjAo0/cKKZPqjVzu2D.EXRLFT2Ih558gCzfDdfiaHm54fpu', '64159a52c42d2', '2023-03-18 11:02:42', 0),
       (3, 'user2', 'user@user.com', '$2y$10$k9qqraqVYobCdgs3tv9MreZNUpjA19I49LPT47uMLKgyrwmPoFlYy', '672d0ded080a0', '2024-11-07 18:58:53', 0);

-- 2024-11-07 20:00:26
