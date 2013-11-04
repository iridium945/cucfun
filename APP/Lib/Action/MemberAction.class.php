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


class MemberAction extends CommonAction {



    // 登陆页验证
    public function loginVerify() {
		define( "WB_CALLBACK_URL" , 'http://cucfun.com/weibo/callback/' );
		define( "WB_REDIRECT_URL" , 'http://cucfun.com/member/LoginMethod/' );

		WeiboAction::callback();
		self::LoginMethod();
    }



   // 登陆通用方法
   private function LoginMethod() {

		$weibo = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid = $_SESSION['token']['uid'];
		$user_message = $weibo->show_user_by_id( $uid );

        $user = M('user')->where(array('wbUid' => $uid))->find();

		if ($user) {
	        session('uid', $user['id']);
	        session('username', $user['screen_name']);

	        cookie('__u',$user['id']);
	        cookie('__c',sha1($user['id'] . '3stc' . $user['wbUid']));

	        $this->redirect('/dashboard/');
		} else {
			$this->error('无此用户，请先注册','/');
		}

   }


    // 登陆页验证
    public function regVerify() {
		define( "WB_CALLBACK_URL" , 'http://cucfun.com/weibo/callback/' );
		define( "WB_REDIRECT_URL" , 'http://cucfun.com/member/regMethod/' );

		WeiboAction::callback();
		self::regMethod();

    }

   // 注册通用方法
   private function regMethod() {

		$weibo = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$uid = $_SESSION['token']['uid'];
		$user_message = $weibo->show_user_by_id( $uid );

        $user = M('user')->where(array('wbUid' => $uid))->find();


        if (!$user_message['verified_reason']) {
        	$this->error('您不是认证用户','/');
        }

		if ($user) {
			self::LoginMethod();
		} else {
			$data = array(
				'wbUid' => $user_message['id'],
				'screen_name' => $user_message['screen_name'],
				'verified_reason' => $user_message['verified_reason'],
				'creatTime' => time()
			);
			M('user')->add($data);

			$this->success('验证成功，请再次登录','/');

		}

   }

   // 登出页面
    public function logout() {
        cookie(__u, null, -8640000);
        cookie(__c, null, -8640000);
        session('[destroy]');
        $this->redirect('/');
    }



}