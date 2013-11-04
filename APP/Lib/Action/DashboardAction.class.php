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


class DashboardAction extends CommonAction {


	public function index() {
		self::userGroup();

		$uid = CommonAction::$user['id'];
		if (CommonAction::$user['admin'] == 1) {
			$this->topics = M('topic')->order('id DESC')->select();
		} else {
			$this->topics = M('topic')->where(array('userId' => $uid ))->order('id DESC')->select();
		}

		$this->display();

	}


	public function addhandle() {
		self::userGroup();
		if(!IS_POST) _404('页面不存在...');
		$beginDate = strtotime( I('beginDateD').'-'.I('beginDateM').'-'.I('beginDateY') );
		$endDate = strtotime( I('endDateD').'-'.I('endDateM').'-'.I('endDateY') );
		$days = abs($beginDate - $endDate)/86400;

		$data = array(
			'title' => I('title'),
			'description' => I('description'),
			'keyword' => I('keyword'),
			'beginDate' => $beginDate,
			'endDate' => $endDate,
			'days' => floor($days),
			);

		if ( I('userId') ) {
			$data['userId'] = I('userId');
			} else {
			$data['userId'] = CommonAction::$user['id'];
			} ;

		$result = M('topic')->add($data);

		if ($result) {
			$this->success('添加成功！','/dashboard/');
		} else {
			$this->error('添加错误！','/dashboard/');
		}


	}



	public function edit() {
		self::userGroup();
		$topicid = I('id');
		if (!$topicid) { $this->redirect('/dashboard/'); }
		$this->topic = self::datechange( M('topic')->find($topicid) );

		$this->topicid = $topicid ;

		$this->display();

	}


	public function edithandle() {
		self::userGroup();
		if(!IS_POST) _404('页面不存在...');
		$beginDate = strtotime( I('beginDateD').'-'.I('beginDateM').'-'.I('beginDateY') );
		$endDate = strtotime( I('endDateD').'-'.I('endDateM').'-'.I('endDateY') );
		$days = abs($beginDate - $endDate)/86400;

		$data = array(
			'id' => I('id'),
			'title' => I('title'),
			'description' => I('description'),
			'keyword' => I('keyword'),
			'beginDate' => $beginDate,
			'endDate' => $endDate,
			'days' => floor($days),
			);

		if ( I('userId') ) { $data['userId'] = I('userId'); } ;

		$result = M('topic')->save($data);

		if ($result) {
			$this->success('修改成功！','/dashboard/');
		} else {
			$this->error('修改错误！','/dashboard/');
		}


	}



	public function datechange($data) {

		$data[beginDateY] = date('Y',$data[beginDate]);
		$data[beginDateM] = date('m',$data[beginDate]);
		$data[beginDateD] = date('d',$data[beginDate]);
		$data[endDateY] = date('Y',$data[endDate]);
		$data[endDateM] = date('m',$data[endDate]);
		$data[endDateD] = date('d',$data[endDate]);

		return $data;

	}



	private function userGroup() {
		if(!CommonAction::$user) $this->redirect('/');
	}
}