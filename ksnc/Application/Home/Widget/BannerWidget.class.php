<?php

namespace Home\Widget;
use Think\Controller;
use Think\Model;

class BannerWidget extends Controller{
    public $del=0;

    public function Index(){

        $data_page=M('page')->select();
        foreach ($data_page as $k=>$v){
            if($_SERVER['PATH_INFO']==''){
                $url='index';
            }else{
                $var = preg_match('/'.$v['name'].'/i',$_SERVER['REQUEST_URI']);
            }
            if(!empty($var)){
                $url=$v['name'];
            }else{

            }
        }

//       print_r($_SERVER);
        $data_image=M('Image')->where('model="'.$url.'"')->select();
        $count=M('Image')->where('model="'.$url.'"')->count();
        $str='[';
        for($i=0;$i<$count;$i++){
            $str.='"'.$data_image[$i]['url'].'",';
        }
        $str=substr($str,0,strlen($str)-1);
        $str.=']';
        $this->assign('str',$str);

        $this->display('Public/banner');
    }
    public function Market(){
        //print_r($_SERVER['REQUEST_URI']);
        // $url = substring($_SERVER['REQUEST_URI'],0,-5);
        //print_r($url);
        $url='Matching';
        $data_image=M('Image')->where('model="'.$url.'"')->select();
        $count=M('Image')->where('model="'.$url.'"')->count();
        $str='[';
        for($i=0;$i<$count;$i++){
            $str.='"'.$data_image[$i]['url'].'",';
        }
        $str=substr($str,0,strlen($str)-1);
        $str.=']';
        $this->assign('str',$str);

        $this->display('Public/banner');
    }
}