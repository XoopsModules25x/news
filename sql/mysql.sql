#
# Table structure for table `news_stories`
#

CREATE TABLE news_stories (
  storyid      INT(8) UNSIGNED      NOT NULL AUTO_INCREMENT,
  uid          INT(5) UNSIGNED      NOT NULL DEFAULT '0',
  title        VARCHAR(255)         NOT NULL DEFAULT '',
  created      INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  published    INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  expired      INT(10) UNSIGNED     NOT NULL DEFAULT '0',
  hostname     VARCHAR(20)          NOT NULL DEFAULT '',
  nohtml       TINYINT(1)           NOT NULL DEFAULT '0',
  nosmiley     TINYINT(1)           NOT NULL DEFAULT '0',
  hometext     TEXT                 NOT NULL,
  bodytext     TEXT                 NOT NULL,
  keywords     VARCHAR(255)         NOT NULL,
  description  VARCHAR(255)         NOT NULL,
  counter      INT(8) UNSIGNED      NOT NULL DEFAULT '0',
  topicid      SMALLINT(4) UNSIGNED NOT NULL DEFAULT '1',
  ihome        TINYINT(1)           NOT NULL DEFAULT '0',
  notifypub    TINYINT(1)           NOT NULL DEFAULT '0',
  story_type   VARCHAR(5)           NOT NULL DEFAULT '',
  topicdisplay TINYINT(1)           NOT NULL DEFAULT '0',
  topicalign   CHAR(1)              NOT NULL DEFAULT 'R',
  comments     SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  rating       DOUBLE(6, 4)         NOT NULL DEFAULT '0.0000',
  votes        INT(11) UNSIGNED     NOT NULL DEFAULT '0',
  picture      VARCHAR(50)          NOT NULL,
  pictureinfo  VARCHAR(255)         NOT NULL DEFAULT '',
  subtitle     VARCHAR(255)         NOT NULL DEFAULT '',
  PRIMARY KEY (storyid),
  KEY idxstoriestopic (topicid),
  KEY ihome (ihome),
  KEY uid (uid),
  KEY published_ihome (published, ihome),
  KEY title (title(40)),
  KEY created (created),
  FULLTEXT KEY search (title, hometext, bodytext)
)
  ENGINE = MyISAM;

#
# Table structure for table `news_stories_files`
#

CREATE TABLE news_stories_files (
  fileid       INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  filerealname VARCHAR(255)    NOT NULL DEFAULT '',
  storyid      INT(8) UNSIGNED NOT NULL DEFAULT '0',
  `date`       INT(10)         NOT NULL DEFAULT '0',
  mimetype     VARCHAR(64)     NOT NULL DEFAULT '',
  downloadname VARCHAR(255)    NOT NULL DEFAULT '',
  counter      INT(8) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (fileid),
  KEY storyid (storyid)
)
  ENGINE = MyISAM;

#
# Table structure for table `news_topics`
#

CREATE TABLE news_topics (
  topic_id          SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  topic_pid         SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0',
  topic_imgurl      VARCHAR(20)          NOT NULL DEFAULT '',
  topic_title       VARCHAR(255)         NOT NULL DEFAULT '',
  menu              TINYINT(1)           NOT NULL DEFAULT '0',
  topic_frontpage   TINYINT(1)           NOT NULL DEFAULT '1',
  topic_rssurl      VARCHAR(255)         NOT NULL DEFAULT '',
  topic_description TEXT                 NOT NULL,
  topic_color       VARCHAR(6)           NOT NULL DEFAULT '000000',
  PRIMARY KEY (topic_id),
  KEY pid (topic_pid),
  KEY topic_title (topic_title(100)),
  KEY menu (menu)
)
  ENGINE = MyISAM;

#
# Table structure for table `news_stories_votedata`
#

CREATE TABLE news_stories_votedata (
  ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  storyid         INT(8) UNSIGNED     NOT NULL DEFAULT '0',
  ratinguser      INT(11)             NOT NULL DEFAULT '0',
  rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
  ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY storyid (storyid)
)
  ENGINE = MyISAM;
