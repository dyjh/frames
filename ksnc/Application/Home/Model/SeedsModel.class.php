<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

class SeedsModel extends Model{

    function select_all_seed($where){

       $AllSeedList = $this->where($where)->cache('AllSeedList')->select();

       return $AllSeedList;
    }

}
