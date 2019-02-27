<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/20
 * Time: 14:16
 */

namespace Admin\Controller;
use Think\Controller;
class DisastersController extends AdminController
{
    public function index(){
        if($_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Disasters/index');
                return;
            }
            //print_r($start);die;

            //$k=I('post.k','','');
            //$lun=I('post.lun','','');
            //$zhi=I('post.zhi','','');
            //$data['times']=$lun;
            //$data['k']=$k;
            //$data['type']=$zhi;
            $Disa = M('Disaster_time');
            $Disa->times =I('post.lun','','addslashes');
            $Disa->k = I('post.k','','addslashes');
            $Disa->type = I('post.zhi','','addslashes');
            //print_r($_POST);die;
            if($Disa->filter('strip_tags')->add()){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("灾难分配成功！"),history.back(); </script>';
                exit();
            }
        }else{
            $data=M('Statistical')->select();
            //查看未回本用户
			$num_old='';
            $num_new='';
            foreach ($data as $k=>$v){
                //print_r($v);echo'<br />';
                $case =''.$v['name'].'_planting_record';
                $case_member =''.$v['name'].'_members';
                $case_managed=''.$v['name'].'_managed_to_record';
                $member_new=M(''.$case_member.'')->select();
                foreach ($member_new as $key=>$val){
                    $data_new=M('global_conf')->where('cases = "disasters_new"')->filter('strip_tags')->find();
                   $data_member=M(''.$case_member.'')->where('user ='.$val['user'].' AND cost_state=0 AND disasters_num < '.$data_new['value'])->find();
                    if(empty($data_member)){
                        $count_end=0;
                    }else{
                        $ready=$data_new['value']-$data_member['disasters_num'];
                        
                        $data_plant_new=M(''.$case.'')->where('seed_state<3  AND harvest_num>=52 AND disasters_state =0 AND user ='.$data_member['user'])->count();
                        if($ready>=$data_plant_new){
                            $count=$data_plant_new;
                        }else{
                            $count=$ready;
                        }
                       
						$managed_count=M(''.$case_managed.'')->where('state =0 AND user ='.$data_member['user'])->count();
                        $heart=M(''.$case_managed.'')->where('service_type = 4 AND state =0 AND user ='.$data_member['user'])->find();
                        $ai=M(''.$case_managed.'')->where('service_type = 5 AND state =0 AND user ='.$data_member['user'])->find();
                        if(empty($heart)&&empty($ai)){
                            $managed_count_end=$managed_count;
                        }elseif(!empty($heart)&&!empty($ai)){
                            $managed_count_end=$managed_count-2;
                        }else{
                            $managed_count_end=$managed_count-1;
                        }
                        if($managed_count_end==3){
                            $count_end=0;
                        }else{
                            $count_end=$count;
                        }
                    }
                    $num_new+=$count_end;
					
					$data_old=M('global_conf')->where('cases = "disasters_old"')->filter('strip_tags')->find();
					$data_member_o=M(''.$case_member.'')->where('user ='.$val['user'].' AND cost_state=1 AND disasters_num < '.$data_old['value'])->find();
                    //print_r($data_member_o['user']);
                    if(empty($data_member_o)){
                        $count_end_o=0;
                    }else{
                        $ready_o=$data_old['value']-$data_member_o['disasters_num'];
						$data_plant_old=M(''.$case.'')->where('seed_state<3  AND harvest_num>=52 AND disasters_state =0 AND user ='.$data_member_o['user'])->count();
                        //print_r($data_plant_old);
                        if($ready_o>=$data_plant_old){
                            $count_o=$data_plant_old;
                        }else{
                            $count_o=$ready_o;
                        }
                        $managed_count_o=M(''.$case_managed.'')->where('state =0 AND user ='.$data_member_o['user'])->distinct(true)->count();
                        $heart_o=M(''.$case_managed.'')->where('service_type = 4 AND state =0 AND user ='.$data_member_o['user'])->filter('strip_tags')->find();
                        $ai_o=M(''.$case_managed.'')->where('service_type = 5 AND state =0 AND user ='.$data_member_o['user'])->filter('strip_tags')->find();
                        if(empty($heart_o)&&empty($ai_o)){
                            $managed_count_end_o=$managed_count_o;
                        }elseif(!empty($heart_o)&&!empty($ai_o)){
                            $managed_count_end_o=$managed_count_o-2;
                        }else{
                            $managed_count_end_o=$managed_count_o-1;
                        }
                        if($managed_count_end_o==3){
                            $count_end_o=0;
                        }else{
                            $count_end_o=$count_o;
                        }
                    }
                    $num_old+=$count_end_o;
					
					
                }
            }
            
            //当前灾难剩余轮数
            $y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");
            //print_r($m);die;
            $tm =date("d")+1;

            $start= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳

            //print_r($start);die;
            $end = $start+24*3600;
            $new['type'] = ':type';
            $data_new=M('disaster_time')->where($new)->bind(':type','0',\PDO::PARAM_INT)->filter('strip_tags')->find();
            $data_old=M('disaster_time')->where($new)->bind(':type','1',\PDO::PARAM_INT)->filter('strip_tags')->find();
            if(empty($data_new)){
                $state_new=0;
            }else{
                $count_new=$data_new['times'];
                $state_new=1;
                $this->assign('count_new',$count_new);
            }
            if(empty($data_old)){
                $state_old=0;
            }else{
                $count_old=$data_old['times'];
                $state_old=1;
                $this->assign('count_old',$count_old);
            }
            $this->assign('state_new',$state_new);
            $this->assign('state_old',$state_old);
            creatToken();
            $con['cases'] = ':cases';
            $data_conf =M('Global_conf')->where($con)->bind(':cases','max_disasters',\PDO::PARAM_STR)->filter('strip_tags')->find();
            $conf=$data_conf['value'];
            $this->assign('num_new',$num_new);
            $this->assign('num_old',$num_old);
            $this->assign('conf',$conf);
            $this->display();
        }
    }
    public function check(){
        if(IS_AJAX){
            //$type=I('post.type','','');
            $type['type'] = ':type';
            $data=M('disaster_time')->where($type)->bind(':type',I('post.type'),\PDO::PARAM_STR)->find();
            if(empty($data)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }
	
	
	public function clear_disaster(){
		if(IS_GET){
			$type=I('get.type');
			if(M('disaster_time')->where('type='.$type)->delete()){
				echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
				echo '<script> alert("成功！"); </script>';
				echo "<script> window.location.href='".U('Disasters/index')."';</script>";
				exit();
			}else{
				echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
				echo '<script> alert("失败1！"); </script>';
				echo "<script> window.location.href='".U('Disasters/index')."';</script>";
				exit();
			}
		}else{
			echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
				echo '<script> alert("失败！"); </script>';
				echo "<script> window.location.href='".U('Disasters/index')."';</script>";
				exit();
		}
	}
}