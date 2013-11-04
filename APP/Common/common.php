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




// 打印函数
function p($arr) {

    echo '<pre>'. print_r($arr, true) . '<pre>';

 }


function listTopicTitle() {

	$now_where['beginDate'] = array('ELT',time()); $now_where['endDate'] = array('EGT',time());
	$topics = M('topic')->field("id,title")->where($now_where)->select();

	foreach ($topics as $topic) {
		$titles[] = "[".$topic['id']."] ".$topic['title'];
	}
	$titles = join(" \n",$titles);

	return $titles;
}


function listTopicTitleArr() {
	$now_where['beginDate'] = array('ELT',time()); $now_where['endDate'] = array('EGT',time());
	$topics = M('topic')->field("id")->where($now_where)->select();

	foreach ($topics as $topic) {
		$titles[] = $topic['id'];
	}

	return $titles;
}


// 关键词过滤
function keywords($str) {
	$arr = array("逼" => "*", "操" => "*", "肏" => "*", "你妈" => "尼玛", "fuck" => "f**k", "FUCK" => "F**K"  , "他妈" => "TM");

	return strtr($str,$arr);

}

?>