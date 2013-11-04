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


class IndexAction extends CommonAction {

    public function index(){
		$this->page_name = "index";
		$this->display();

   }

    public function how(){
		$this->page_name = "how";
		$this->display();

   }


    public function lists(){
    	$this->topics_count = M('topic')->count();
    	$this->messages_count = M('message')->count();

		$now_where['beginDate'] = array('ELT',time()); $now_where['endDate'] = array('EGT',time());
    	$this->now_topics = M('topic')->order('id DESC')->where($now_where)->select();
    	$this->now_where_count = M('topic')->where($now_where)->order('id DESC')->count();

    	$end_where['beginDate'] = array('ELT',time()); $end_where['endDate'] = array('ELT',time());
    	$this->end_topics = M('topic')->order('id DESC')->where($end_where)->select();
    	$this->end_where_count = M('topic')->where($end_where)->order('id DESC')->count();

    	$future_where['beginDate'] = array('EGT',time()); $future_where['endDate'] = array('EGT',time());
    	$this->future_topics = M('topic')->where($future_where)->order('id DESC')->select();
    	$this->future_topics_count = M('topic')->where($future_where)->order('id DESC')->count();


		$this->page_name = "view";
		$this->display();

   }


    public function view(){
    	$this->page_name = "view";
    	$id = I('id');
    	$page_size = 100;
		$count = M('message')->where("topicId=$id")->count();

    	$this->topic = M('topic')->where("id=$id")->find();
		$this->page_name = "view";

		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
        import('Class.Page', APP_PATH);
        $page_nav = new SubPages($page_size,$count,$page,10, "/view/$id/p/",2);
        $this->page_nav = $page_nav->subPageCss2() ;

    	$this->messages = M('message')->order('id DESC')->where("topicId=$id")->page($page.','.$page_size)->select();

		$this->display();

   }




}