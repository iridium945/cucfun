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


class AjaxAction extends Action {

	/**
	 *  吐槽点赞方法
	 */
	public function likeMessage() {
		$mid = I('mid');
		$result = M('message')->where("id=$mid")->setInc('likecount');
		if(!IS_AJAX) _404('页面不存在...');
		if ($result) {
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
		}
		$this->ajaxReturn($data);

	}

	/**
	 *  屏蔽用户方法
	 */
	public function lockUser() {
		$uid = I('uid');
		$time = I('time');
		$time = time()+ $time * 60 ;
		$result = M('wxuser')->where("id=$uid")->setField('lockTime',$time);
		if(!IS_AJAX) _404('页面不存在...');
		if ($result) {
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
		}
		$this->ajaxReturn($data);

	}


}