<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10 0010
 * Time: 下午 7:11
 */

namespace Admin\Controller;
use Think\Controller;

class EmptyController extends Controller
{
    public function _empty()
    {
        //可以自己处理，跳转到相应链接
        $this->display('Empty/404');
    }
}