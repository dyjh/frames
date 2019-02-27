<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-05-11
 * Time: 17:57
 */

namespace Admin\Controller;
use Think\Controller;

class ConfController extends AdminController
{
    public function pay_time(){
        if(IS_POST){
            $conf=M('Global_conf');
            $data_e['value']=I('post.end_time','','');
            $data_s['value']=I('post.start_time','','');
            if($conf->where('cases="end_time"')->save($data_e) !==false){
                if($conf->where('cases="start_time"')->save($data_s) !==false){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改成功！"),history.back(); </script>';
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改失败！"),history.back(); </script>';
                    exit();
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }
        }else{
            $conf=M('Global_conf');
            $data_e=$conf->where('cases="end_time"')->find();
            $data_s=$conf->where('cases="start_time"')->find();
            $this->assign('data_e',$data_e);
            $this->assign('data_s',$data_s);
            $this->display();
        }
    }
}