-- Partner Program applications
-- Safe to run on a new server: creates the table only if it does not already exist.

CREATE TABLE IF NOT EXISTS `partner_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) NOT NULL DEFAULT '',
  `email` varchar(191) NOT NULL DEFAULT '',
  `country` varchar(128) NOT NULL DEFAULT '',
  `phone` varchar(64) NOT NULL DEFAULT '',
  `program_type` varchar(64) NOT NULL DEFAULT 'Partner',
  `experience` text,
  `message` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
