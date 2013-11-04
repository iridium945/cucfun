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


/*
 * 程序参数总控
 */

return array (

    'URL_HTML_SUFFIX' => '',

    //数据库连接参数
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_NAME' => 'cucfun',
    'DB_PREFIX' => 'cucfun_',

    //地址设置
    'URL_MODEL' => 2,
    'URL_CASE_INSENSITIVE' => TRUE,

    //Cookie设置
    'COOKIE_EXPIRE'         => 604800,          // Coodie有效期7天
    //'COOKIE_DOMAIN'         => 'cucfun.com',  // Cookie有效域名
    'COOKIE_PATH'           => '/',             // Cookie路径
    'COOKIE_PREFIX'         => '',          // Cookie前缀 避免冲突

	// 新浪微博账户信息
	'WEIBO_ID' => '797356440',
	'WEIBO_SECRET' => 'ce8c95ba98ecf8290f0d8db9fadb8594',


    //URL路由
    'LOAD_EXT_CONFIG' => 'route',

    'SHOW_PAGE_TRACE' =>true

);
?>