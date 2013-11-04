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


class ApiAction extends Action {

/**
 *  主题数据字段获取方法
 */
	public function topic() {
		$tid = I('id'); $get = I('get');

		if ($get == 'all') {
			$contents = M('topic')->where("id=$tid")->select();
		} else {
			$content = M('topic')->where("id=$tid")->getField($get);
		}

		if ($content) {
			$data['data'] = urlencode($content);
		} elseif ($contents) {
			$data['data'] = self::urlcodeContent($contents);
		} else {
			$data['code'] = 404;
			$data['errorMessage'] = 'NOT FOUND';
		}
		self::output($data);

	}

/**
 * 获取吐槽数据通用信息
 * 最新、随机、区间
 */
	public function message() {
		$get = I('get');
		if ( I('id') ) { $tid = I('id'); } ;
		if ( I('limit') ) { $limit = I('limit'); } else { $limit = 10; };
		if ( I('field')=="all" ) { $field = "id,wxUserId,creatTime,MsgId,likecount,content"; } else { $field = "content";  };

		if ($get == 'new') {   // 获取最新的数据
			$messages = M('message')->where("topicId=$tid")->order('id DESC')->limit($limit)->field($field)->select();
		} elseif ($get == 'random') {    // 随机获取数据
			$messages = M('message')->order('RAND( )')->limit($limit)->select();
		} elseif ($get == 'between') {   // 指定获取数据
			$from = I('from'); $to = I('to');
			$where['topicId']  = $tid;
			$where['id']  = array('between',"$from,$to");
			$messages = M('message')->where($where)->select();
		}

		self::parserMessage($messages);
	}



/**
 * 吐槽信息压制判断
 */
	private function parserMessage($messages) {

		if ($messages) {
			$data['data'] = self::urlcodeMessage($messages);
		} else {
			$data['code'] = 404;
			$data['errorMessage'] = 'NOT FOUND';
		}

		self::output($data);

	}

/**
 *  吐槽信息[content]字段 CODE转码专用
 */
	private function urlcodeMessage($datas) {

		foreach ( $datas as $data ) {
        	$data[content] = urlencode($data[content]);
        	$newdatas[] = $data;
    	}

    	return $newdatas;

	}


/**
 *  所有字段 CODE转码专用
 */
	private function urlcodeContent($datas) {

		foreach ( $datas as $data ) {
			foreach ($data as $key => $value) {
        		$data[$key] = urlencode($data[$key]);
			}
        	$newdatas[] = $data;
    	}
    	return $newdatas;

	}


	private function output($data) {

		header("Content-type: application/json; charset=utf-8");
		echo urldecode( json_encode($data) );

	}
}