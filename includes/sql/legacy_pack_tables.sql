-- MultiCMS Phase 2: minimal legacy pack tables (structures only, no seed data)

CREATE TABLE IF NOT EXISTS `adposting` (
  `adpostingid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `adpostingurl` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`adpostingid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `blog` (
  `blogid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `metadesc` varchar(100) NOT NULL,
  `metakey` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  PRIMARY KEY (`blogid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `categories` (
  `cateid` int(100) NOT NULL AUTO_INCREMENT,
  `catename` varchar(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

CREATE TABLE IF NOT EXISTS `comments` (
  `commentid` int(100) NOT NULL AUTO_INCREMENT,
  `comment` text,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  `id` int(100) DEFAULT NULL,
  PRIMARY KEY (`commentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS `contacts` (
  `cid` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `custom` (
  `customid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `metadesc` varchar(100) NOT NULL,
  `metakey` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  PRIMARY KEY (`customid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `doctors` (
  `doctorsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`doctorsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `friendlinks` (
  `linkid` int(100) NOT NULL AUTO_INCREMENT,
  `linktitle` varchar(100) NOT NULL,
  `linkurl` varchar(100) NOT NULL,
  PRIMARY KEY (`linkid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `image` (
  `imageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`imageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

CREATE TABLE IF NOT EXISTS `market` (
  `marketid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `imageurl1` varchar(100) DEFAULT NULL,
  `imageurl2` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `editorpick` varchar(100) NOT NULL,
  `current` varchar(100) NOT NULL,
  PRIMARY KEY (`marketid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `marketmessage` (
  `marketmessageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `users` varchar(100) NOT NULL,
  `from` varchar(100) NOT NULL,
  `status1` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `marketid` int(100) NOT NULL,
  PRIMARY KEY (`marketmessageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `imageurl` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `members` (
  `memberid` int(100) NOT NULL AUTO_INCREMENT,
  `users` varchar(100) NOT NULL,
  `passs` varchar(255) NOT NULL,
  `level` varchar(15) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `zip` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `yahooid` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`memberid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `news` (
  `newsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`newsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `pages` (
  `pageid` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `selecttopic` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `parts` (
  `partsid` int(100) NOT NULL AUTO_INCREMENT,
  `part` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`partsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `portfolio` (
  `portfolioid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `metadesc` varchar(160) DEFAULT NULL,
  `metakey` varchar(250) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`portfolioid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `productpublisher` (
  `productpublisherid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `productpublisherurl` varchar(100) DEFAULT NULL,
  `owner` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`productpublisherid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `reports` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `users` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `catename` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `searchengine` (
  `searchengineid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `siteurl` varchar(100) NOT NULL,
  `dates` varchar(100) DEFAULT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`searchengineid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `themes` (
  `themeid` int(100) NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`themeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `tutorials` (
  `tutorialsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `tutorialsurl` varchar(100) NOT NULL,
  `tutorialpathurl` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tutorialsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `tutorialsmessage` (
  `tutorialsmessageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `users` varchar(100) NOT NULL,
  `from` varchar(100) NOT NULL,
  `status1` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `tutorialsid` int(100) NOT NULL,
  PRIMARY KEY (`tutorialsmessageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `video` (
  `videoid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `videourl` varchar(100) DEFAULT NULL,
  `embedcode` text,
  `date` varchar(100) NOT NULL,
  `videotype` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`videoid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

CREATE TABLE IF NOT EXISTS `widgets` (
  `wid` int(100) NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

