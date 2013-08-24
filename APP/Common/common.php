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



function listTopicTitle() {

	$topics = M('topic')->field("id,title")->select();

	foreach ($topics as $topic) {
		$titles[] = "[".$topic['id']."] ".$topic['title'];
	}
	$titles = join(" | ",$titles);

	return $titles;
}


function listTopicTitleArr() {

	$topics = M('topic')->field("id")->select();

	foreach ($topics as $topic) {
		$titles[] = $topic['id'];
	}

	return $titles;
}


?>