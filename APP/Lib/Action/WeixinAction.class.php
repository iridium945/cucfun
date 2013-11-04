<?php
// +----------------------------------------------------------------------
// | CUCFUN [ SHOW YOUR POINT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://cucanima.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Gavin Foo <fuxiaopang@msn.com>
// +----------------------------------------------------------------------


class WeixinAction extends Action {

	public $postStr;

	private $fromUsername;
	private $toUsername;
	private $CreateTime;
	private $keyword;
	private $textTpl;
	private $wxuserid;
	private $MsgId;

    public function sync(){

	    define("TOKEN", "cucfun");

	    $this->postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

	    echo $this->responseMsg();


    }

    public function responseMsg()
    {

      	//extract post data
		if (!empty($this->postStr)){

              	$postObj = simplexml_load_string($this->postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $this->fromUsername = $fromUsername = $postObj->FromUserName;
                $this->toUsername = $toUsername = $postObj->ToUserName;
                $this->CreateTime = $CreateTime = $postObj->CreateTime;
                $msgType = trim($postObj->MsgType);
                $this->keyword = $keyword = keywords(trim($postObj->Content));   // 已经过滤
                $this->MsgId = trim($postObj->MsgId);

                $this->wxuserid = trim($fromUsername);
                $time = time();
                $this->textTpl = $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

				if ($postObj->Event == "subscribe") {
					$this->subscribe();
				}

				if($msgType != "text") {
					$this->wxMessageReturn("对不起 只支持文字内容哟~");
				}
				if(!empty( $keyword ))
                {
              		$this->userdecide();
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }

	/**
	 * 判断用户 OpenID 是否在数据库中
	 */
	public function userdecide() {
		$wxuserid = $this->wxuserid;
		$wxUser = M('wxuser')->field("id,openId,topicId,lockTime")->where(array("openId"=> $wxuserid))->find();

		if (!$wxUser) {									// 没有的用户记录 OpenID
			$this->subscribe();
		} else {
			$time = time();
			if($time > $wxUser['lockTime']) {          // 判断是否被屏蔽
				if ($wxUser['topicId']){			   // 判断是否已经已经参加主题
					$this->messageSave($wxUser['topicId'],$wxUser['id']);
				} else {							   // 没有主题则进入主题选择模式
					$this->topicConfirm();
				}
			} else {                                    // 屏蔽反馈
				$min = abs($wxUser['lockTime'] - time());
				$min = date("i分s秒",$min);
				$this->wxMessageReturn("可能由于你之前的言论过于敏感或含有广告成分，所以系统暂时将你锁定，距离解禁还有".$min);
			}

		};

	}

	/**
	 * 数据入库
	 */
	public function messageSave($tid,$wxuid) {
		$message = $this->keyword;
		$MsgId = $this->MsgId;

		if($message == "取消") {									// 主题取消判断
			M('wxuser')->where(array("id"=> $wxuid))->setField("topicId",0);
			$this->wxMessageReturn("退出主题成功");
		} else {												// 存储数据
			$topic = M('topic')->where("id=$tid")->find();
			$data = array ('topicId'=> $tid,"wxUserId"=>$wxuid, 'content'=>$message, 'creatTime'=>time(), 'MsgId'=>$MsgId );
			$result = M('message')->add($data);
			M('topic')->where("id=$tid")->setInc('count');
			M('topic')->where("id=$tid")->setField('lastId',$result);

			$this->wxMessageReturn("在".$topic[title]."中吐槽成功\n" .
					"回复“取消”退出当前活动");
		}
	}


	/**
	 * 判断用户输入内容是否为主题关键字
	 */
	public function topicConfirm() {
		$wxuserid = $this->wxuserid;
		$message = $this->keyword;
		$topicarr = listTopicTitleArr();

		if (in_array($message,$topicarr)) {						// 存储用户数据主题编号
			$tid = $message;
			$ttitle = M('topic')->where("id=$tid")->getField('title');
			M('wxuser')->where(array("openId"=> $wxuserid))->setField("topicId",$tid);
			$this->wxMessageReturn("你选择了 [".$ttitle."]，亲\n" .
					"现开始咆哮吧！ 少年！ [呲牙]\n" .
					"要记得文明哟~ [偷笑]\n" .
					"(如需退出请回复“取消”)");
		} else {												// 输出主题清单
			$topics = listTopicTitle();
			$this->wxMessageReturn("欢迎回到吐槽系统哟~\n" .
					"请输入你要吐槽的活动名称\n" .
					"+------------------------+\n" .
					$topics."\n" .
					"+------------------------+\n" .
					"(请输入编号即可，如 1)");
		}

	}


	/**
	 * 初次订阅写入数据库
	 */
	public function subscribe(){
			$wxuserid = $this->wxuserid;
			$data[openId] = $wxuserid;
			$data[creatTime] = time();
			M('wxuser')->data($data)->add();
			$this->wxMessageReturn("欢迎来到吐槽系统~\n" .
					"谢谢你关注我(*^__^*) 嘻嘻…… [害羞] [害羞]\n" .
					"我们将先记录下你的用户名ID，作为识别信息，如果想发表吐槽请回复随意内容后获取活动列表");

	}


	/**
	 */
	public function wxMessageReturn($message) {

		$msgType = "text";
    	$contentStr = $message;
    	$resultStr = sprintf($this->textTpl, $this->fromUsername, $this->toUsername, time(), $msgType, $contentStr);
    	echo $resultStr;

	}


}