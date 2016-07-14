<?php
namespace Lib;

class RedisAPI {

	private $_redis;

	public function __construct() {
		$redis = new Redis();
   		$redis->connect(REDIS_HOST, REDIS_PORT);
   		$this->_redis = $redis;
	}

	public function retrieveReplyObject($received) {
		$key = $this->generateRedisKey($received);
		$replyObject = $this->replyTriggerQuery($key);
		if($replyObject) {
			$replyObject->fromUserName = $received->ToUserName;
	  		$replyObject->toUserName = $received->FromUserName;
	  		return $replyObject;
		} 
		return NULL;
	}

	public function saveReceivedObject($received) {
		if(SAVE) {
			$msg_type_save = explode('|', MSG_TYPE_SAVE);
			$event_type_save = explode('|', EVENT_TYPE_SAVE);
			if($received->MsgType == 'event') {
				if(in_array($received->Event, $event_type_save)) {
					$key = MSG_SQUEUE_PREFIX . 'event:' . $received->Event;
					$this->_redis->lPush($key, serialize($received));
				}
			} else {
				if(in_array($received->MsgType, $msg_type_save)) {
					$key = MSG_SQUEUE_PREFIX . 'msg:' . $received->MsgType;
					$this->_redis->lPush($key, serialize($received));
				}
			}		
		}
	}

	public function replyTriggerQuery($key) {
	  return unserialize($this->_redis->get($key));
	}

	public function generateRedisKey($received) {
		$key = array();
		if($received->MsgType == 'event') {
			$key['type'] = 'event';
			switch ($received->Event) {
			  case 'subscribe':
			    $key['trigger_word'] = $received->Event;
			    break;
			  case 'unsubscribe':
			    $key['trigger_word'] = $received->Event;
			    break;   
			  case 'scan':
			    $key['trigger_word'] = $received->EventKey;
			    break;
			  case 'location':
			    $key['trigger_word'] = $received->Event;
			    break;
			  case 'click':
			    $key['trigger_word'] = $received->EventKey;
			    break;
			  case 'view':
			    $key['trigger_word'] = $received->Event;
			    break;
			  case 'templatesendjobfinish':
			    $key['trigger_word'] = $received->Event;
			    break;
			  default:
			    $key['trigger_word'] = '';
			}
		} else {
			$key['type'] = 'msg';
			switch ($received->MsgType) {
			  case 'text':
			    $key['trigger_word'] = $received->Content;
			    break;
			  case 'image':
			    $key['trigger_word'] = '';
			    break;
			  case 'voice':
			    $key['trigger_word'] = '';
			    break;
			  case 'video':
			    $key['trigger_word'] = '';
			    break;
			  case 'location':
			    $key['trigger_word'] = $received->MsgType;
			    break;
			  case 'link':
			    $key['trigger_word'] = '';
			    break;
			  default:
			    $key['trigger_word'] = '';
			}
		}
		$key = implode(':', $key);
		return DATA_PREFIX . $key;
	}

	public function getAccessToken() {
		return $this->getAccessKey();
	}

	public function getJSApiTicket() {
		return $this->getAccessKey('jsapi_ticket');
	}

	private function getAccessKey($type = 'access_token') {
		$key = WECHAT_TOKEN_PREFIX . $type;
  		if($key_value = $this->_redis->get($key)) {
    		return unserialize($key_value);
  		} else {
    		$key_value = '';
    		$expires_in = '';
    		if($type == 'access_token') {
				$wechatAPI = new WechatAPI(TOKEN, APPID, APPSECRET);
  				$data = $wechatAPI->getAccessToken();
  				if(isset($data->access_token)) {
  					$key_value = $data->access_token;
	        		$expires_in = $data->expires_in - AHEADTIME;
  				}
		    } else {
		      $wechatJSSDKAPI = new JSSDKAPI($this->getAccessKey('access_token'));
		      $data = $wechatJSSDKAPI->getTicket($type);
		      if($data->ticket){
		        $key_value = $data->ticket;
		        $expires_in = $data->expires_in - AHEADTIME;
		      } 
		    }
		    $this->_redis->set($key, serialize($key_value));
			$this->_redis->setTimeout($key, $expires_in);
			return $key_value;
  		}
	}

	public function get($key) {
		$key_value = $this->_redis->get($key);
		return unserialize($key_value);
	}
}