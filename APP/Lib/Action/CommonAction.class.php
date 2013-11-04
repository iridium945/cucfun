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


class CommonAction extends Action {

    public static $user;
     // 自动加载方法
    public static function _initialize() {

        self::visitor();

    }

    public static function visitor() {
        if (!self::$user || self::$user->id != session('uid')) {

            if (isset($_SESSION['uid'])) {
                $temp_user = M('user')->find(session('uid'));
                self::$user = $temp_user;
            } elseif (self::cookieget('__u')) {
                $temp_user = M('user')->find(intval(self::cookieget('__u')));
                if (sha1($temp_user[id] . '3stc' . $temp_user[wbUid]) == self::cookieget('__c')) {
                    self::$user = $temp_user;
                    }
            }
        }
    }


	private static function cookieget($name) {
        return isset($_COOKIE[$name]) ? trim($_COOKIE[$name]) : null;

    }


}
