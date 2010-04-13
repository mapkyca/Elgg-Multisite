--
-- Elgg multisite db schema.
--
-- @package ElggMultisite
-- @author Marcus Povey <marcus@marcus-povey.co.uk>
-- @copyright Marcus Povey / UnofficialElgg.com 2010
-- @link http://www.unofficialelgg.com/
-- @link http://www.marcus-povey.co.uk/
--

-- Domains.
--
-- Domain class information.
CREATE TABLE domains (

	id int(11) unsigned  NOT NULL auto_increment,
	domain varchar(255) NOT NULL,
	class varchar(50) NOT NULL,

	primary key (`id`),
	unique key (`domain`),
	key (`class`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Domains metadata
CREATE TABLE domains_metadata (
	`id` int(11) unsigned NOT NULL auto_increment,
	`domain_id` int(11) unsigned  NOT NULL,

	`name` varchar(128) NOT NULL,
	`value` blob NOT NULL,

	primary key (`id`),
	key (`domain_id`),
	key (`name`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Activated plugins
CREATE TABLE domains_activated_plugins (
	`id` int(11) unsigned NOT NULL auto_increment,
	`domain_id` int(11) unsigned  NOT NULL,
	plugin varchar(50) NOT NULL,

	primary key (`id`),
	key (`domain_id`),
	unique key (`domain_id`,`plugin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Users table
CREATE TABLE users (
	`id` int(11) unsigned NOT NULL auto_increment,

	username varchar(50) NOT NULL,
	password varchar(128) NOT NULL,
	salt varchar(8) NOT NULL,

	primary key (`id`),
	unique key (`username`),
	key (`password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
