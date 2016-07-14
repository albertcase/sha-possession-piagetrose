<?php

//Redis config info
define("REDIS_HOST", '127.0.0.1');
define("REDIS_PORT", '6379');

define("BASE_URL", 'http://xxx.com/');
define("TEMPLATE_ROOT", dirname(__FILE__) . '/../template');
//Wechat config info
define("TOKEN", 'xxxx');
define("APPID", 'xxxx');
define("APPSECRET", 'xxxx');
define("NOWTIME", time());
define("AHEADTIME", '100');

define("NONCESTR", 'xxxx');

//Wechat Authorize
define("CALLBACK", 'wechat/ws/callback');
define("SCOPE", 'snsapi_base');

//Account Access
define("OAUTH_ACCESS", '{"cce" : "xxx", "6edigital": "6edigital2016"}');
define("JSSDK_ACCESS", '{"cce" : "cce2015", "6edigital": "6edigital2016"}');

//Database config info
define("DBHOST", '127.0.0.1');
define("DBUSER", 'root');
define("DBPASS", '');
define("DBNAME", 'possession_piagetrose');

//Message Squeue Config
define("WECHAT_TOKEN_PREFIX", 'wechat:token:');
define("EXPIRE_AHEAD", '60');
define("MSG_SQUEUE_PREFIX", 'wechat:received:');
define("DATA_PREFIX", 'wechat:reply:');
define("LBS_DATA", 'wechat:lbs:data');
define("SAVE", TRUE);
define("MSG_TYPE_SAVE", 'text');
//text|image|voice|video|location|link
define("EVENT_TYPE_SAVE", 'subscribe|unsubscribe|scan');
//subscribe|unsubscribe|scan|location|click|view|templatesendjobfinish
define("MSG_TYPE_POP", 'text');
define("EVENT_TYPE_POP", 'subscribe|unsubscribe|scan');
