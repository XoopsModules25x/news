#
# Table structure for table `mod_news_stories`
#

CREATE TABLE mod_news_stories (
  storyid int(8) unsigned NOT NULL auto_increment,
  uid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  published int(10) unsigned NOT NULL default '0',
  expired int(10) unsigned NOT NULL default '0',
  hostname varchar(20) NOT NULL default '',
  nohtml tinyint(1) NOT NULL default '0',
  nosmiley tinyint(1) NOT NULL default '0',
  hometext text NOT NULL,
  bodytext text NOT NULL,
  keywords varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  counter int(8) unsigned NOT NULL default '0',
  topicid smallint(4) unsigned NOT NULL default '1',
  ihome tinyint(1) NOT NULL default '0',
  notifypub tinyint(1) NOT NULL default '0',
  story_type varchar(5) NOT NULL default '',
  topicdisplay tinyint(1) NOT NULL default '0',
  topicalign char(1) NOT NULL default 'R',
  comments smallint(5) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  picture varchar(50) NOT NULL,
  pictureinfo varchar(255) NOT NULL default '',
  subtitle varchar(255) NOT NULL default '',
  PRIMARY KEY  (storyid),
  KEY idxstoriestopic (topicid),
  KEY ihome (ihome),
  KEY uid (uid),
  KEY published_ihome (published,ihome),
  KEY title (title(40)),
  KEY created (created),
  FULLTEXT KEY search (title,hometext,bodytext)
) ENGINE=MyISAM;

#
# Table structure for table `mod_news_stories_files`
#

CREATE TABLE mod_news_stories_files (
  fileid int(8) unsigned NOT NULL auto_increment,
  filerealname varchar(255) NOT NULL default '',
  storyid int(8) unsigned NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  mimetype varchar(64) NOT NULL default '',
  downloadname varchar(255) NOT NULL default '',
  counter int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (fileid),
  KEY storyid (storyid)
) ENGINE=MyISAM;

#
# Table structure for table `mod_news_topics`
#

CREATE TABLE mod_news_topics (
  topic_id smallint(4) unsigned NOT NULL auto_increment,
  topic_pid smallint(4) unsigned NOT NULL default '0',
  topic_imgurl varchar(20) NOT NULL default '',
  topic_title varchar(255) NOT NULL default '',
  menu tinyint(1) NOT NULL default '0',
  topic_frontpage tinyint(1) NOT NULL default '1',
  topic_rssurl varchar(255) NOT NULL default '',
  topic_description text NOT NULL,
  topic_color varchar(6) NOT NULL default '000000',
  PRIMARY KEY  (topic_id),
  KEY pid (topic_pid),
  KEY topic_title (topic_title),
  KEY menu (menu)
) ENGINE=MyISAM;


#
# Table structure for table `mod_news_stories_votedata`
#

CREATE TABLE mod_news_stories_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  storyid int(8) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY storyid (storyid)
) ENGINE=MyISAM;
