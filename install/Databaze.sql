--
-- Struktura tabulky `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `level` int(2) NOT NULL,
  `cdate` date NOT NULL,
  `active` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `activation_code`
--

CREATE TABLE IF NOT EXISTS `activation_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `code` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `used` int(2) NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `lost_password`
--

CREATE TABLE IF NOT EXISTS `lost_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `pass_string` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `cdate` datetime NOT NULL,
  `exp_date` datetime NOT NULL,
  `used` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `data_type` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `feature_type` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `data_feature`
--

CREATE TABLE IF NOT EXISTS `data_feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `geometry` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`properties`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `layer`
--

CREATE TABLE IF NOT EXISTS `layer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layer_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `feature_type` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `style` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `layer_feature`
--

CREATE TABLE IF NOT EXISTS `layer_feature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layer_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `geometry` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`geometry`)),
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`properties`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `map`
--

CREATE TABLE IF NOT EXISTS `map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` varchar(8) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `template` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `user_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `cdate` date NOT NULL,
  `shared` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `map_layers`
--

CREATE TABLE IF NOT EXISTS `map_layers` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `map_id` varchar(8) COLLATE utf8_czech_ci NOT NULL,
  `layer_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
