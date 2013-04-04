

-- --------------------------------------------------------

--
-- Table structure for table `Conferences`
--

CREATE TABLE IF NOT EXISTS `Conferences` (
  `ConferenceID` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL,
  PRIMARY KEY (`ConferenceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Divisions`
--

CREATE TABLE IF NOT EXISTS `Divisions` (
  `DivisionID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `ConferenceID` int(11) NOT NULL,
  PRIMARY KEY (`DivisionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `GameStatFields`
--

CREATE TABLE IF NOT EXISTS `GameStatFields` (
  `GameStatFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  PRIMARY KEY (`GameStatFieldID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `GameStats`
--

CREATE TABLE IF NOT EXISTS `GameStats` (
  `GameStatID` int(11) NOT NULL AUTO_INCREMENT,
  `GameID` int(11) NOT NULL,
  `Blocks` int(11) NOT NULL,
  `ShotMade` int(11) NOT NULL,
  `Shot3` int(11) NOT NULL,
  `Shots` int(11) NOT NULL,
  `Turnover` int(11) NOT NULL,
  `Steals` int(11) NOT NULL,
  `Assists` int(11) NOT NULL,
  `Fouls` int(11) NOT NULL,
  `ORebounding` int(11) NOT NULL,
  `DRebounding` int(11) NOT NULL,
  `Points` int(11) NOT NULL,
  PRIMARY KEY (`GameStatID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Players`
--

CREATE TABLE IF NOT EXISTS `Players` (
  `PlayerID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(128) NOT NULL DEFAULT '',
  `LastName` varchar(128) NOT NULL DEFAULT '',
  `Age` int(11) NOT NULL DEFAULT '0',
  `Nationality` varchar(128) NOT NULL,
  `Hand` int(11) NOT NULL DEFAULT '0',
  `Height` int(11) NOT NULL DEFAULT '0',
  `Weight` int(11) NOT NULL,
  `Shooting` int(11) NOT NULL DEFAULT '0',
  `Strength` int(11) NOT NULL DEFAULT '0',
  `Speed` int(11) NOT NULL DEFAULT '0',
  `Agility` int(11) NOT NULL DEFAULT '0',
  `Passing` int(11) NOT NULL DEFAULT '0',
  `Dunk` int(11) NOT NULL DEFAULT '0',
  `ORebounding` int(11) NOT NULL DEFAULT '0',
  `DRebounding` int(11) NOT NULL DEFAULT '0',
  `Blocking` int(11) NOT NULL DEFAULT '0',
  `SRange` int(11) NOT NULL DEFAULT '0',
  `OAwareness` int(11) NOT NULL DEFAULT '0',
  `DAwareness` int(11) NOT NULL DEFAULT '0',
  `Defence` int(11) NOT NULL DEFAULT '0',
  `Stealing` int(11) NOT NULL DEFAULT '0',
  `Health` int(11) NOT NULL DEFAULT '0',
  `Position` int(11) NOT NULL DEFAULT '0',
  `Peak` int(11) NOT NULL DEFAULT '0',
  `Active` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`PlayerID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1801 ;

-- --------------------------------------------------------

--
-- Table structure for table `PlayerStats`
--

CREATE TABLE IF NOT EXISTS `PlayerStats` (
  `PlayerStatID` int(11) NOT NULL AUTO_INCREMENT,
  `GameID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `Blocks` int(11) NOT NULL,
  `ShotMade` int(11) NOT NULL,
  `Shot3` int(11) NOT NULL,
  `Shots` int(11) NOT NULL,
  `Turnover` int(11) NOT NULL,
  `Steals` int(11) NOT NULL,
  `Assists` int(11) NOT NULL,
  `Fouls` int(11) NOT NULL,
  `ORebounding` int(11) NOT NULL,
  `DRebounding` int(11) NOT NULL,
  `Points` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  PRIMARY KEY (`PlayerStatID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `Plays`
--

CREATE TABLE IF NOT EXISTS `Plays` (
  `PlayID` int(11) NOT NULL AUTO_INCREMENT,
  `Shooters` varchar(32) NOT NULL,
  `Passers` varchar(32) NOT NULL,
  `OffRebounders` varchar(32) NOT NULL,
  `ChanceSteal` int(11) NOT NULL,
  `ChanceDefence` int(11) NOT NULL,
  `ChanceRebound` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Chance3Point` int(11) NOT NULL,
  `DefRebounders` varchar(32) NOT NULL,
  `Defenders` varchar(32) NOT NULL,
  `Stealers` varchar(32) NOT NULL,
  `Shooters3Point` varchar(32) NOT NULL,
  `ChanceAssist` int(11) NOT NULL,
  `Duration` varchar(32) NOT NULL DEFAULT '10,24',
  PRIMARY KEY (`PlayID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `Positions`
--

CREATE TABLE IF NOT EXISTS `Positions` (
  `PositionID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(16) NOT NULL,
  `ShortName` varchar(2) NOT NULL,
  PRIMARY KEY (`PositionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `Schedule`
--

CREATE TABLE IF NOT EXISTS `Schedule` (
  `ScheduleID` int(11) NOT NULL AUTO_INCREMENT,
  `HomeTeam` int(11) NOT NULL,
  `VisitorTeam` int(11) NOT NULL,
  `SeasonID` int(11) NOT NULL,
  `Week` int(11) NOT NULL,
  `GameDay` int(11) NOT NULL,
  `TimeSlot` int(11) NOT NULL,
  `Completed` int(11) NOT NULL,
  `CompletedDate` datetime NOT NULL,
  `HomeScore` int(11) NOT NULL,
  `VisitorScore` int(11) NOT NULL,
  PRIMARY KEY (`ScheduleID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=871 ;

-- --------------------------------------------------------

--
-- Table structure for table `Seasons`
--

CREATE TABLE IF NOT EXISTS `Seasons` (
  `SeasonID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `Year` int(11) NOT NULL,
  PRIMARY KEY (`SeasonID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Skills`
--

CREATE TABLE IF NOT EXISTS `Skills` (
  `SkillID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(16) NOT NULL,
  `ShortName` varchar(8) NOT NULL,
  PRIMARY KEY (`SkillID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `TeamPlayers`
--

CREATE TABLE IF NOT EXISTS `TeamPlayers` (
  `TeamPlayerID` int(11) NOT NULL AUTO_INCREMENT,
  `TeamID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `PositionID` int(11) NOT NULL,
  PRIMARY KEY (`TeamPlayerID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=451 ;

-- --------------------------------------------------------

--
-- Table structure for table `Teams`
--

CREATE TABLE IF NOT EXISTS `Teams` (
  `TeamID` int(11) NOT NULL AUTO_INCREMENT,
  `City` varchar(64) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Division` int(11) NOT NULL,
  PRIMARY KEY (`TeamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `TeamStats`
--

CREATE TABLE IF NOT EXISTS `TeamStats` (
  `TeamStatID` int(11) NOT NULL AUTO_INCREMENT,
  `GameID` int(11) NOT NULL,
  `Blocks` int(11) NOT NULL,
  `ShotMade` int(11) NOT NULL,
  `Shot3` int(11) NOT NULL,
  `Shots` int(11) NOT NULL,
  `Turnover` int(11) NOT NULL,
  `Steals` int(11) NOT NULL,
  `Assists` int(11) NOT NULL,
  `Fouls` int(11) NOT NULL,
  `ORebounding` int(11) NOT NULL,
  `DRebounding` int(11) NOT NULL,
  `Points` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  PRIMARY KEY (`TeamStatID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;
