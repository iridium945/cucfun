<?php
/**
  * wechat php test
  */

define("TOKEN", "cucfun");



class wechatCallbackapiTest {

private $postStr;

    function __construct($postStr) {
    	$this->postStr = $postStr;
    }

	public function valid()
    {
        $echoStr = $this->postStr["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {

      	//extract post data
		if (!empty($this->postStr)){

              	$postObj = simplexml_load_string($this->postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "你刚刚发了个大槽到大屏幕 【".$keyword."】";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }

	private function checkSignature()
	{
        $signature = $this->postStr["signature"];
        $timestamp = $this->postStr["timestamp"];
        $nonce = $this->postStr["nonce"];

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>