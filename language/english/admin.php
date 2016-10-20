<?php
//
//%%%%%%    Admin Module Name  Articles     %%%%%
define('_AM_DBUPDATED', 'Database updated successfully!');
define('_AM_CONFIG', 'News configuration');
define('_AM_AUTOARTICLES', 'Automated articles');
define('_AM_STORYID', 'Story ID');
define('_AM_TITLE', 'Title');
define('_AM_TOPIC', 'Topic');
define('_AM_POSTER', 'Poster');
define('_AM_PROGRAMMED', 'Programmed Date/Time');
define('_AM_NEWS_ACTION', 'Action');
define('_AM_EDIT', 'Edit');
define('_AM_DELETE', 'Delete');
define('_AM_LAST10ARTS', 'Last %d articles');
define('_AM_PUBLISHED', 'Published'); // Published Date
define('_AM_GO', 'Go!');
define('_AM_EDITARTICLE', 'Edit article');
define('_AM_POSTNEWARTICLE', 'Post new article');
define('_AM_ARTPUBLISHED', 'Your article has been published!');
define('_AM_HELLO', 'Hello %s,');
define('_AM_YOURARTPUB', 'Your article submitted to our site has been published.');
define('_AM_TITLEC', 'Title: ');
define('_AM_URLC', 'URL: ');
define('_AM_PUBLISHEDC', 'Published: ');
define('_AM_RUSUREDEL', 'Are you sure you want to delete this article and all its comments?');
define('_AM_YES', 'Yes');
define('_AM_NO', 'No');
define('_AM_INTROTEXT', 'Intro text');
define('_AM_EXTEXT', 'Extended text');
define('_AM_ALLOWEDHTML', 'Allowed HTML:');
define('_AM_DISAMILEY', 'Disable smiley');
define('_AM_DISHTML', 'Disable HTML');
define('_AM_APPROVE', 'Approve');
define('_AM_MOVETOTOP', 'Move this story to top');
define('_AM_CHANGEDATETIME', 'Change the date/time of publication');
define('_AM_NOWSETTIME', 'It is now set at: %s'); // %s is datetime of publish
define('_AM_CURRENTTIME', 'Current time is: %s');  // %s is the current datetime
define('_AM_SETDATETIME', 'Set the date/time of publish');
define('_AM_MONTHC', 'Month:');
define('_AM_DAYC', 'Day:');
define('_AM_YEARC', 'Year:');
define('_AM_TIMEC', 'Time:');
define('_AM_PREVIEW', 'Preview');
//define('_AM_SAVE', 'Save');
define('_AM_PUBINHOME', 'Publish in home?');
define('_AM_ADD', 'Add');
//%%%%%%    Admin Module Name  Topics   %%%%%
define('_AM_ADDMTOPIC', 'Add a main topic');
define('_AM_TOPICNAME', 'Topic name');
// Warning, changed from 40 to 255 characters.
define('_AM_MAX40CHAR', '(max: 255 characters)');
define('_AM_TOPICIMG', 'Topic image');
define('_AM_IMGNAEXLOC', 'image name + extension located in %s');
define('_AM_FEXAMPLE', 'for example: games.gif');
define('_AM_ADDSUBTOPIC', 'Add a subtopic');
define('_AM_IN', 'in');
define('_AM_MODIFYTOPIC', 'Modify topic');
define('_AM_MODIFY', 'Modify');
define('_AM_PARENTTOPIC', 'Parent topic');
define('_AM_SAVECHANGE', 'Save changes');
define('_AM_DEL', 'Delete');
define('_AM_CANCEL', 'Cancel');
define('_AM_WAYSYWTDTTAL', 'Warning: Are you sure you want to delete this topic and all its stories and comments?');
// Added in Beta6
define('_AM_TOPICSMNGR', 'Topics manager');
define('_AM_PEARTICLES', 'Post/Edit articles');
define('_AM_NEWSUB', 'New submissions');
define('_AM_POSTED', 'Posted');
define('_AM_GENERALCONF', 'General configuration');
// Added in RC2
define('_AM_TOPICDISPLAY', 'Display topic image?');
define('_AM_TOPICALIGN', 'Position');
define('_AM_RIGHT', 'Right');
define('_AM_LEFT', 'Left');
define('_AM_EXPARTS', 'Expired Articles');
define('_AM_EXPIRED', 'Expired');
define('_AM_CHANGEEXPDATETIME', 'Change the date/time of expiration');
define('_AM_SETEXPDATETIME', 'Set the date/time of expiration');
define('_AM_NOWSETEXPTIME', 'It is now set at: %s');
// Added in RC3
define('_AM_ERRORTOPICNAME', 'You must enter a topic name!');
define('_AM_EMPTYNODELETE', 'Nothing to delete!');
// Added 240304 (Mithrandir)
define('_AM_GROUPPERM', 'Submit/Approve/View permissions');
define('_AM_SELFILE', 'Select file to upload');
// Added by Hervé
define('_AM_UPLOAD_DBERROR_SAVE', 'Error while attaching file to the story');
define('_AM_UPLOAD_ERROR', 'Error while uploading the file');
define('_AM_UPLOAD_ATTACHFILE', 'Attached file(s)');
define('_AM_APPROVEFORM', 'Approve permissions');
define('_AM_SUBMITFORM', 'Submit permissions');
define('_AM_VIEWFORM', 'View permissions');
define('_AM_APPROVEFORM_DESC', 'Select, who can approve news');
define('_AM_SUBMITFORM_DESC', 'Select, who can submit news');
define('_AM_VIEWFORM_DESC', 'Select, who can view which topics');
define('_AM_DELETE_SELFILES', 'Delete selected files');
define('_AM_TOPIC_PICTURE', 'Upload picture');
define('_AM_UPLOAD_WARNING', '<b>Warning, do not forget to apply write permissions to the following folder : %s</b>');
define('_AM_NEWS_UPGRADECOMPLETE', 'Upgrade complete');
define('_AM_NEWS_UPDATEMODULE', 'Update module templates and blocks');
define('_AM_NEWS_UPGRADEFAILED', 'Upgrade failed');
define('_AM_NEWS_UPGRADE', 'Upgrade');
define('_AM_ADD_TOPIC', 'Add a topic');
define('_AM_ADD_TOPIC_ERROR', 'Error, topic already exists!');
define('_AM_ADD_TOPIC_ERROR1', 'Error: Cannot select this topic for parent topic!');
define('_AM_SUB_MENU', 'Publish this topic as a sub menu');
define('_AM_SUB_MENU_YESNO', 'Submenu?');
define('_AM_HITS', 'Hits');
define('_AM_CREATED', 'Created');
define('_AM_TOPIC_DESCR', "Topic's description");
define('_AM_USERS_LIST', 'Users list');
define('_AM_PUBLISH_FRONTPAGE', 'Publish in front page?');
define('_AM_NEWS_UPGRADEFAILED1', 'Impossible to create the table stories_files');
define('_AM_NEWS_UPGRADEFAILED2', "Impossible to change the topic title's length");
define('_AM_NEWS_UPGRADEFAILED21', 'Impossible to add the new fields to the topics table');
define('_AM_NEWS_UPGRADEFAILED3', 'Impossible to create the table stories_votedata');
define('_AM_NEWS_UPGRADEFAILED4', "Impossible to create the two fields 'rating' and 'votes' for the 'story' table");
define('_AM_NEWS_UPGRADEFAILED0',
       "Please note the messages and try to correct the problems with phpMyadmin and the sql definition's file available in the 'sql' folder of the news module");
define('_AM_NEWS_UPGR_ACCESS_ERROR', 'Error, to use the upgrade script, you must be an admin on this module');
define('_AM_NEWS_PRUNE_BEFORE', 'Prune stories that were published before');
define('_AM_NEWS_PRUNE_EXPIREDONLY', 'Only remove stories who have expired');
define('_AM_NEWS_PRUNE_CONFIRM',
       "Warning, you are going to permanently remove stories that were published before %s (this action can't be undone). It represents %s stories.<br>Are you sure?");
define('_AM_NEWS_PRUNE_TOPICS', 'Limit to the following topics');
define('_AM_NEWS_PRUNENEWS', 'Prune news');
define('_AM_NEWS_EXPORT_NEWS', 'News Export (in XML)');
define('_AM_NEWS_EXPORT_NOTHING', "Sorry, but there's nothing to export. Please, verify your criteria");
define('_AM_NEWS_PRUNE_DELETED', '%d news was deleted');
define('_AM_NEWS_PERM_WARNING', '<h2>Warning, you have 3 forms so you have 3 submit buttons</h2>');
define('_AM_NEWS_EXPORT_BETWEEN', 'Export news published between');
define('_AM_NEWS_EXPORT_AND', ' and ');
define('_AM_NEWS_EXPORT_PRUNE_DSC', "If you don't check anything then all the topics will be used<br> else only the selected topics will be used");
define('_AM_NEWS_EXPORT_INCTOPICS', 'Include topics definitions?');
define('_AM_NEWS_EXPORT_ERROR', 'Error while trying to create the file %s. Operation stopped.');
define('_AM_NEWS_EXPORT_READY',
       "Your xml export file is ready for download. <br><a href='%s'>Click on this link to download it</a>.<br>Don't forget <a href='%s'>to remove it</a> once you have finished.");
define('_AM_NEWS_RSS_URL', 'URL of RSS feed');
define('_AM_NEWS_NEWSLETTER', 'Newsletter');
define('_AM_NEWS_NEWSLETTER_BETWEEN', 'Select news published between');
define('_AM_NEWS_NEWSLETTER_READY',
       "Your newsletter file is ready for download. <br><a href='%s'>Click on this link to download it</a>.<br>Don't forget to <a href='%s'>remove it</a> once you have finished.");
define('_AM_NEWS_DELETED_OK', 'File deleted successfully');
define('_AM_NEWS_DELETED_PB', 'There was a problem while deleting the file');
define('_AM_NEWS_STATS0', 'Topics statistics');
define('_AM_NEWS_STATS', 'Statistics');
define('_AM_NEWS_STATS1', 'Unique authors');
define('_AM_NEWS_STATS2', 'Totals');
define('_AM_NEWS_STATS3', 'Articles statistics');
define('_AM_NEWS_STATS4', 'Most read articles');
define('_AM_NEWS_STATS5', 'Less read articles');
define('_AM_NEWS_STATS6', 'Best rated articles');
define('_AM_NEWS_STATS7', 'Most read authors');
define('_AM_NEWS_STATS8', 'Best rated authors');
define('_AM_NEWS_STATS9', 'Biggest contributors');
define('_AM_NEWS_STATS10', 'Authors statistics');
define('_AM_NEWS_STATS11', 'Articles count');
define('_AM_NEWS_HELP', 'Help');
define('_AM_NEWS_MODULEADMIN', 'Module admin');
define('_AM_NEWS_GENERALSET', 'Module settings');
define('_AM_NEWS_GOTOMOD', 'Go to module');
define('_AM_NEWS_NOTHING', "Sorry, but there's nothing to download. Verify your criteria!");
define('_AM_NEWS_NOTHING_PRUNE', "Sorry, but there's no news to prune. Verify your criteria!");
define('_AM_NEWS_TOPIC_COLOR', "Topics's color");
define('_AM_NEWS_COLOR', 'Color');
define('_AM_NEWS_REMOVE_BR', 'Convert the html &lt;br&gt; tag to a new line?');
// Added in 1.3 RC2
define('_AM_NEWS_PLEASE_UPGRADE', "<a href='upgrade.php'><span style='color:#FF0000;'>Please upgrade the module!</span></a>");
// Added in version 1.50
define('_AM_NEWS_NEWSLETTER_HEADER', 'Header');
define('_AM_NEWS_NEWSLETTER_FOOTER', 'Footer');
define('_AM_NEWS_NEWSLETTER_HTML_TAGS', 'Remove html tags?');
define('_AM_NEWS_VERIFY_TABLES', 'Maintain tables');
define('_AM_NEWS_METAGEN', 'Metagen');
define('_AM_NEWS_METAGEN_DESC',
       'Metagen is a system that can help you have your page best indexed by search engines.<br>Except if you type meta keywords and meta descriptions yourself, the module will automatically create them.');
define('_AM_NEWS_BLACKLIST', 'Blacklist');
define('_AM_NEWS_BLACKLIST_DESC', 'The words in this list will not be used to create meta keywords');
define('_AM_NEWS_BLACKLIST_ADD', 'Add');
define('_AM_NEWS_BLACKLIST_ADD_DSC', 'Enter words to add in the list<br>(one word byline)');
define('_AM_NEWS_META_KEYWORDS_CNT', 'Maximum count of meta keywords to auto-generate');
define('_AM_NEWS_META_KEYWORDS_ORDER', 'Keywords order');
define('_AM_NEWS_META_KEYWORDS_INTEXT', 'Create them in the order they appear in the text');
define('_AM_NEWS_META_KEYWORDS_FREQ1', "Words frequency's order");
define('_AM_NEWS_META_KEYWORDS_FREQ2', 'Reverse order of words frequency');
// Added in version 1.67
// About.php
define('_AM_NEWS_ABOUT_RELEASEDATE', 'Released: ');
define('_AM_NEWS_ABOUT_UPDATEDATE', 'Updated: ');
define('_AM_NEWS_ABOUT_AUTHOR', 'Author: ');
define('_AM_NEWS_ABOUT_CREDITS', 'Credits: ');
define('_AM_NEWS_ABOUT_LICENSE', 'License: ');
define('_AM_NEWS_ABOUT_MODULE_STATUS', 'Status: ');
define('_AM_NEWS_ABOUT_WEBSITE', 'Website: ');
define('_AM_NEWS_ABOUT_AUTHOR_NAME', 'Author name: ');
define('_AM_NEWS_ABOUT_CHANGELOG', 'Change Log');
define('_AM_NEWS_ABOUT_MODULE_INFO', 'Module Infos');
define('_AM_NEWS_ABOUT_AUTHOR_INFO', 'Author Infos');
define('_AM_NEWS_ABOUT_DESCRIPTION', 'Description: ');
// Configuration check
define('_AM_NEWS_CONFIG_CHECK', 'Configuration Check');
define('_AM_NEWS_CONFIG_PHP', 'Minimum PHP required: %s (your version is %s)');
define('_AM_NEWS_CONFIG_XOOPS', 'Minimum XOOPS required:  %s (your version is %s)');
define('_AM_NEWS_STATISTICS', 'News Statistics');
define('_AM_NEWS_THEREARE_STORIES', "There are <span class='red bold'>%s</span> News in the database");
define('_AM_NEWS_THEREARE_STORIES_ONLINE', "There are <span class='red bold'>%s</span> News published in Home");
define('_AM_NEWS_THEREARE_STORIES_FILES', "There are <span class='red bold'>%s</span> Stories_files in the database");
define('_AM_NEWS_THEREARE_STORIES_FILES_ONLINE', "There are <span class='red bold'>%s</span> Stories_files online");
define('_AM_NEWS_THEREARE_TOPICS', "There are <span class='red bold'>%s</span> Categories in the database");
define('_AM_NEWS_THEREARE_TOPICS_ONLINE', "There are <span class='red bold'>%s</span> Categories visible in Menu");
define('_AM_NEWS_THEREARE_STORIES_VOTEDATA', "There are <span class='red bold'>%s</span> Stories Viewed");
define('_AM_NEWS_THEREARE_STORIES_IMPORTED', "There are <span class='red bold'>%s</span> Imported Stories");
define('_AM_NEWS_THEREARE_STORIES_EXPORTED', "There are <span class='red bold'>%s</span> Stories exported");
define('_AM_NEWS_THEREARE_STORIES_EXPIRED', "There are <span class='red bold'>%s</span> Expired News");
define('_AM_NEWS_THEREARE_STORIES_EXPIRED_SOON', "There are <span class='red bold'>%s</span> News to Expire soon");
define('_AM_NEWS_THEREARE_STORIES_APPROVED', "There are <span class='red bold'>%s</span> Approved News");
define('_AM_NEWS_THEREARE_STORIES_NEED_APPROVAL', "There are <span class='red bold'>%s</span> News that need Approval");
