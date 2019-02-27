<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 11:50
 */

namespace Admin\Controller;
use Think\Upload;
class ImageController extends AdminController
{
    public function index(){
        $image=M('Image');
        $count = $image->distinct(true)->count();   //-->$count=M('Image')->count();原始语句
        $num =6;
        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
        $p = intval(I('get.p',1,'addslashes'));  //过滤方式加intval
        if($p<1){
            $p =1;
        }else if($p > $pages){
            $p = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$p-$off;
        $end=$p+$off;
        //起始页
        if($p-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($p+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }

        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页

        $this->assign('p',$p);
        $data =M('Image');
        $date = $data->order('id DESC')->page($p.','.$num)->filter('strip_tags')->select();

        foreach ($date as $k=>$v){
            $data_m=M('Page');
            $where['name'] = ':name';
            $list = $data_m->where($where)->bind(':name',$v['model'],\PDO::PARAM_STR)->filter('strip_tags')->find();  //$data_m->where('name="'.$v['model'].'"')->find();原始语句
            $date[$k]['ch']=$list['ch'];
        }
        if(empty($date)){
            $state=0;
        }else{
            $state=1;
        }

        $this->assign('state',$state);
        $this->assign('data',$date);
        $this->display();
    }

    public function del(){
        if(IS_AJAX){
            $where['id'] = ':id';
            if(M('Image')->where($where)->bind(':id',I('post.id'),\PDO::PARAM_INT)->filter('strip_tags')->delete()){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo -1;
        }
    }

    public function add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Image/index');
                return;
            }
            //$ip=gethostbyname($_SERVER['SERVER_NAME']);
            //print_r($ip);die;
            //$data['url']='http://lyogame.cn';/原始语句
            //$data['url'].=$ip;
            //$url=I('url');/
            //$data['url'].=$url;
            //$data['model']=I('post.model','','');

            $image=M('Image');
            // $ul='http://jeanho.xyz:9000';
            $ul='';
            $image->url = $ul.=I('post.url','','addslashes');
            $image->model = I('post.model','','addslashes');
            if($image->filter('strip_tags')->add()){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加成功！"); </script>';
                echo "<script> window.location.href='".U('Image/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加失败！"),history.back(); </script>';
                exit();
            }
        }else{
            creatToken();
            $data_page=M('page')->select();
            $this->assign('data_page',$data_page);
            $this->display();
        }
    }

    public function edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Image/index');
                return;
            }
            //$ip=gethostbyname($_SERVER['SERVER_NAME']);
            //$id=I('post.id','','');
            //$data['url']='http://lyogame.cn';
            //$data['url'].=$ip;
            //$url=I('post.url','','');
            //$data['url'].=$url;
            //$data['model']=I('post.model','','');
            //$old_img=I('post.old_img','','');

            $image=M('Image');
            //$ul='http://jeanho.xyz:9000'; 
			$ul='';
            $image->url = $ul.=I('post.url','','addslashes');
            $image->model = I('post.model','','addslashes');
            $image->old_img = I('post.old_img','','addslashes');
            $where['id'] = ':id';
            $bind[':id'] = array(I('post.id'),\PDO::PARAM_INT);
            if($image->where($where)->bind($bind)->filter('strip_tags')->save()){
                unlink(I('post.old_img'));
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改成功！"); </script>';
                echo "<script> window.location.href='".U('Image/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }
        }else{
            creatToken();
            $data_page=M('page')->select();
            //$id=I('id');
            $where['id'] = ':id';
            $data = M('Image')->where($where)->bind(':id',I('get.id'),\PDO::PARAM_INT)->filter('strip_tags')->find();
            $this->assign('data',$data);
            $this->assign('data_page',$data_page);
            $this->display();
        }
    }
}