--
-- Table structure for table `covers`
--

CREATE TABLE IF NOT EXISTS `covers` (
  `isbn` varchar(20) NOT NULL,
  `sort` int(11) NOT NULL,
  `flyer_id` int(11) NOT NULL,
  KEY `flyer_id` (`flyer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `flyers`
--

CREATE TABLE IF NOT EXISTS `flyers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `has_url` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(20) NOT NULL,
  `header` varchar(255) DEFAULT NULL,
  `header_css` varchar(255) NOT NULL,
  `timestamp` int(12) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Table structure for table `flyer_html`
--

CREATE TABLE IF NOT EXISTS `flyer_html` (
  `id` int(10) NOT NULL,
  `html` text CHARACTER SET utf8 NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

