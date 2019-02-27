<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 14:13
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Tool;
class ShareController extends AdminController
{
    public function index(){
        define('UC_AUTH_KEY', 'h@x.Mb^50W(TC:g?Xr_>4LjZ6|{k3]z"aE2vi1),'); //加密KEY
        if(IS_POST){
            //print_r($_POST);
            //$password=I('post.password','','');
            $price=I('post.price','','addslashes');
            $password=think_ucenter_md5(I('post.password'),UC_AUTH_KEY);
            $where['password'] = ':password';
            $data=M('ucenter_member')->where($where)->bind(':password',$password,\PDO::PARAM_STR)->filter('strip_tags')->find();
            if(empty($data)){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("密码错误，请重试！"),history.back(); </script>';
                exit();
            }else{
                $data_xxx=M('Statistical')->select();
                foreach ($data_xxx as $k=>$v){
                    $case_m=''.$v['name'].'_members';
                    $case_s=''.$v['name'].'_seed_warehouse';
                    $data_member=M(''.$case_m.'')->select();
                    $count=M(''.$case_m.'')->distinct(true)->count();
                    for($i=0;$i<$count;$i++){
                        $where['seeds'] = ':seeds';
                        $where['user'] = ':user';
                        $bind[':seeds'] = array('分红宝',\PDO::PARAM_STR);
                        $bind[':user'] = array($data_member[$i]['user'],\PDO::PARAM_STR);
                        $data=M(''.$case_s.'')->where($where)->bind($bind)->filter('strip_tags')->find();
                        $coin=$price*$data['num'];
                        if($coin==0){
                            if($i==$count-1){
                               // print_r($i);
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                echo '<script> alert("发放成功！"); </script>';
                                echo "<script> window.location.href='".U('Share/index')."';</script>";
                                exit();
                            }
                        }else{
                            $user['user'] = ':user';
                            if(M(''.$case_m.'')->where($user)->bind(':user',$data_member[$i]['user'],\PDO::PARAM_STR)->filter('strip_tags')->setInc('coin',$coin)){
                                $uid['id'] = ':id';
                                if(M('total_station')->where($uid)->bind(':id','1',\PDO::PARAM_INT)->filter('strip_tags')->setDec('income',$coin)){
                                    $table=new Tool();
                                    $case='member_record';
                                    $tel=$data_member[$i]['user'];
                                    $case_m_r=$table->table($tel,$case);
                                    if(M(''.$case_m_r.'')->where($user)->bind(':user',$tel,\PDO::PARAM_STR)->filter('strip_tags')->setInc('money',$coin)){}
                                    if($i==$count-1){
                                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                        echo '<script> alert("发放成功！"); </script>';
                                        echo "<script> window.location.href='".U('Share/index')."';</script>";
                                        exit();
                                    }
                                }else{
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                    echo '<script> alert("1号错误，请重试！"),history.back();</script>';
                                    exit();
                                }
                            }else{
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                echo '<script> alert("2号错误，请重试！"),history.back(); </script>';
                                exit();
                            }
                        }
                    }
                }
            }
        }else{
            $data_xxx=M('Statistical')->select();
            $num=0;
            foreach ($data_xxx as $k=>$v){
                $case_m=''.$v['name'].'_members';
                $case_s=''.$v['name'].'_seed_warehouse';
                $data_member=M(''.$case_m.'')->select();
                $count=M(''.$case_m.'')->distinct(true)->count();
                for($i=0;$i<$count;$i++){
                    $where['seeds'] = ':seeds';
                    $where['user'] = ':user';
                    $bind[':seeds'] = array('分红宝',\PDO::PARAM_STR);
                    $bind[':user'] = array($data_member[$i]['user'],\PDO::PARAM_STR);
                    $num+=M(''.$case_s.'')->where($where)->bind($bind)->sum('num');
                }
            }
            $this->assign('num',$num);
            $this->display();
        }
    }
}