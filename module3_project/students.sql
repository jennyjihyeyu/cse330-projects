| students | CREATE TABLE `students` (
  `id` mediumint(8) unsigned NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |
+----------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------