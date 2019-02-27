<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Crypt\Driver\Think;
use User\Api\UserApi;
use Think\Controller;

/**
 * 后台攻略 管理
 * @author QHP
 */
class RaidersController extends AdminController {

    protected $table = "raiders";

    public function index(){
        $num=  M("raiders")->distinct(true)->count();
        $per_num =8;
        $k=intval(I('get.k',1,'addslashes'));
        $pages = ceil($num/$per_num);
        $this->assign('pages',$pages+1);
        if($k!==null){
            $k =I('get.k','','int');
        }else{
            $k =1;
        }
        if($k<1){
            $k =1;
        }else if($k > $pages){
            $k = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$k-$off;
        $end=$k+$off;
        //起始页
        if($k-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($k+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('k',$k);
        $all_raiders =M("raiders")->order('listorder DESc')-> page($k.','.$per_num)->filter('strip_tags')->select();
        if(empty($all_raiders)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign("all_raiders",$all_raiders);
        $this->assign('state',$state);
        $this->display();
    }

    /**
     *   异步修改攻略的审核 或 排序状态
     * @param  $material_id  配置ID
     * @param  $object       配置信息
     * @return $data         返回消息
     */
    public function update_ajax($material_id,$object,$type="update"){

        $object = array_filter(I("post.object")) ;
        $riders=M("raiders");
        if($object['is_show']){
            $updata_arr['is_show'] = $object['is_show'];
            $riders->is_show=intval(addslashes($object['is_show']));
        }
        if($object['is_free']){
            $updata_arr['is_free'] = $object['is_free'];
            $riders->is_free=intval(addslashes($object['is_free']));
        }
        if($object['listorder']){
            $updata_arr['listorder'] = $object['listorder'];
            $riders->listorder=intval(addslashes($object['listorder']));
        }
//        $riders->is_show=intval(I('post.is_show',0,'addslashes'));
//        $riders->listorder=intval(I('post.listorder',0,'addslashes'));
//        $riders->is_free=intval(I('post.is_free',0,'addslashes'));
        $res = $riders->where("rid= %d",array($material_id))->filter('strip_tags')->save();

        if($res == 1){
            if($type=="update"){
                $data['status']  = 0;
                $data['content'] = 'OK';
            }elseif($type=="insert"){
                $data['status']  = -1;
                $data['content'] = 'OK';
            }

        }elseif($res == 0){
            $data['status']  = 0;
            $data['content'] = 'raiders not update';
        }else{
//            $this->ajaxReturn(M($this->table)->getLastSql(),'json');
            $data['status']  = 40040;
            $data['content'] = 'update raiders error';
        }

        $this->ajaxReturn($data,'json');

    }



    public function delete_data($table=""){
        $id = intval(I("post.id",0,'addslashes'));
        if($id==0){
            $res=-1;
        }else{
            $res = M($this->table)->where('rid=%d',array($id))->delete();
            $res = M("raiders_content")->where('rid=%d',array($id))->delete();
        }
        if($res > 0){
            $data['status']  = 0;
            $data['content'] = 'delete success';
            $data['remove_tr'] = $id;
        }elseif($res == 0){
            $data['status']  = -1;
            $data['content'] = 'not data delete';
        }else{
            $data['status']  = 40099;
            $data['content'] = 'delete data error';
        }
        $this->ajaxReturn($data,'json');
    }


}