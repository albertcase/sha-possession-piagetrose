<?php
namespace Lib;

class WechatAPI {

	private $_token;
	private $_appid;
	private $_appsecret;

	public $xmlReplyText = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
</xml>
XML;
  	public $xmlReplyNewsArticle = <<<XML
<item>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <PicUrl><![CDATA[%s]]></PicUrl>
  <Url><![CDATA[%s]]></Url>
</item>
XML;
  	public $xmlReplyNews = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <ArticleCount>%s</ArticleCount>
  <Articles>
  %s
  </Articles>
</xml>
XML;
  	public $xmlReplyMusic = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Music>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <MusicUrl><![CDATA[%s]]></MusicUrl>
  <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
  </Music>
</xml>
XML;
  	public $xmlReplyImage = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Image>
  <MediaId><![CDATA[%s]]></MediaId>
  </Image>
</xml>
XML;
  	public $xmlReplyService = <<<XML
<xml>
     <ToUserName><![CDATA[%s]]></ToUserName>
     <FromUserName><![CDATA[%s]]></FromUserName>
     <CreateTime>%s</CreateTime>
     <MsgType><![CDATA[%s]]></MsgType>
 </xml>
XML;
  	public $xmlReplyServiceTo = <<<XML
<xml>
     <ToUserName><![CDATA[%s]]></ToUserName>
     <FromUserName><![CDATA[%s]]></FromUserName>
     <CreateTime>%s</CreateTime>
     <MsgType><![CDATA[%s]]></MsgType>
     <TransInfo>
         <KfAccount><![CDATA[%s]]></KfAccount>
     </TransInfo>
 </xml>
XML;

	public function __construct($token = '', $appid = '', $appsecret = '') {
		$this->_token = $token;
		$this->_appid = $appid;
		$this->_appsecret = $appsecret;
	}

	public function valid() {
		$echoStr = $_GET["echostr"];

		//valid signature , option
		if($this->checkSignature()){
		  echo $echoStr;
		  exit;
		}
	}

	private function checkSignature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		$tmpArr = array($this->_token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
		  return true;
		}else{
		  return false;
		}
	}

	public function responseMsg() {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//extract post data
		if (!empty($postStr)){
		  $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		  return $postObj;
		} else {
		  echo "";
		  exit;
		}
	}

	public function getAccessToken() {
		$applink = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
		$url = sprintf($applink, $this->_appid, $this->_appsecret);
		$data = file_get_contents($url);
		$data = json_decode($data);
		return $data;
	}

	public function replyText(wechatReplyTextObject $reply) {
		$output = sprintf($this->xmlReplyText, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType, $reply->content);
		return $output;
	}

	public function replyNews(wechatReplyNewsObject $reply) {
		$articles = '';
		foreach ($reply->articles as $article) {
		  $articles .= sprintf($this->xmlReplyNewsArticle, $article->title, $article->description, $article->picUrl, $article->url);
		}

		$reply->articleCount = count($reply->articles);
		$output = sprintf($this->xmlReplyNews, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType, $reply->articleCount, $articles);
		return $output;
	}

	public function replyMusic(wechatReplyMusicObject $reply) {
		$output = sprintf($this->xmlReplyMusic, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType, $reply->title, $reply->description, $reply->musicUrl, $reply->hdMusicUrl);
		return $output;
	}

	public function replyImage(wechatReplyImageObject $reply) {
		$output = sprintf($this->xmlReplyImage, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType, $reply->MediaId);
		return $output;
	}

	public function replyService(wechatReplyServiceObject $reply) {
		if($reply->kfaccount) 
		  $output = sprintf($this->xmlReplyServiceTo, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType, $reply->kfaccount);
		else
		  $output = sprintf($this->xmlReplyService, $reply->toUserName, $reply->fromUserName, $reply->createTime, $reply->msgType);
		return $output;
	}

	public function replXml($replyObject) {
		if($replyObject) {
			$class_name = get_class($replyObject);
			$method = lcfirst(preg_replace(array('/wechat/', '/Object/'), array('', ''), $class_name));
			return $this->$method($replyObject);
		} else {
			return '';
		}
	}

	public function prepareReplyObject($replyObject) {
		return new $replyObject(); 
	}
}

class wechatReplyObject {
	public $toUserName;
	public $fromUserName;
	public $createTime;
	public $msgType;

	public function __construct() {
		$this->createTime = time();
	}
}

class wechatReplyArticleObject {
	public $title;
	public $description;
	public $picUrl;
	public $url;
}

class wechatReplyTextObject extends wechatReplyObject {
	public $content;

	public function __construct() {
	parent::__construct();
		$this->msgType = 'text';
	}
}

class wechatReplyNewsObject extends wechatReplyObject {
	public $articleCount;
	public $articles = array();

	public function __construct() {
		parent::__construct();
		$this->msgType = 'news';
	}
}

class wechatReplyMusicObject extends wechatReplyObject {
	public $title;
	public $description;
	public $musicUrl;
	public $hdMusicUrl;

	public function __construct() {
		parent::__construct();
		$this->msgType = 'music';
	}
}

class wechatReplyImageObject extends wechatReplyObject {
	public $MediaId;

	public function __construct() {
		parent::__construct();
		$this->msgType = 'image';
	}
}

class wechatReplyServiceObject extends wechatReplyObject {
	public $kfaccount;

	public function __construct($kfaccount = '') {
	parent::__construct();
		$this->kfaccount = $kfaccount;
		$this->msgType = 'transfer_customer_service';
	}
}
