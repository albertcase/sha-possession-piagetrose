<?php
namespace Lib;

class UserAPI extends Base {

  public function retrieveGroups() {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$this->_access_token}";
    $return = file_get_contents($url);
    return json_decode($return);
  }

  public function createGroup(array $data) {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token={$this->_access_token}";
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    // post data to wechat
    $return = $this->postData($url, $json);
    return $return;
  }

  public function updateGroupName(array $data) {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token={$this->_access_token}";
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    // post data to wechat
    $return = $this->postData($url, $json);
    return $return;
  }

  public function deleteGroup(array $data) {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/delete?access_token={$this->_access_token}";
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    // post data to wechat
    $return = $this->postData($url, $json);
    return $return;
  }

  public function moveMemberGroup(array $data) {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$this->_access_token}";
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    // post data to wechat
    $return = $this->postData($url, $json);
    return $return;
  }

  public function moveMemberGroupBatch(array $data) {
    $url = "https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token={$this->_access_token}";
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    // post data to wechat
    $return = $this->postData($url, $json);
    return $return;
  }

  public function getUserInfo($openid) {
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->_access_token}&openid={$openid}&lang=zh_CN";
    $return = file_get_contents($url);
    return json_decode($return);
  }

  public function isUserSubscribed($openid, $access_token) {
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->_access_token}&openid={$openid}&lang=zh_CN";
    $return = file_get_contents($url);
    $rs = json_decode($return);
    if(isset($rs->subscribe) && $rs->subscribe == 1)
      return TRUE;
    else
      return FALSE;
  }

  public function getAuthorizeUrl($appid, $callback, $scope) {
    $redirect_uri = urlencode($callback);
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
    return $url;
  }

  public function getSnsAccessToken($code, $appid, $appsecret) {
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?code={$code}&grant_type=authorization_code&appid={$appid}&secret={$appsecret}";
    $return = file_get_contents($url);
    return json_decode($return);
  }

  public function getSnsUserInfo($openid, $sns_access_token) {
      $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$sns_access_token}&openid={$openid}&lang=zh_CN";
      $userinfo = file_get_contents($url);
      $userinfo = json_decode($userinfo);
      return $userinfo;
  }

}