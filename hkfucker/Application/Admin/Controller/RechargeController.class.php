<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Our\Admin;
use Think\Model;
class RechargeController extends AdminController
{
    public function index(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Recharge/index');
                return;
            }
            $Admin = new Admin();
            $post=array_filter(I('post.'));
            $user=$post['start_user'];
            if($user){
                $data = $Admin->Set_Level($user);
                $this->assign('user_info',$data);
                $this->display('index');
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }else{
            $this->display();
        }
    }
    
	public function coin_add(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Recharge/index');
                return;
            }
			$user=I('post.user');
			$coin=I('post.coin');
            if(preg_match("/^1[34578]\d{9}$/",$user)){
                $sqluser = substr($user,0,3);
                $sqlname = ''.$sqluser.'_members';
			
				if($coin<=0){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
					echo "<script> alert('输入信息有误');</script>";
					echo "<script> window.location.href='".U('Recharge/coin_add',array('user'=>$user))."';</script>";
					exit();
				}
				$long = M("$sqlname");
				$long->startTrans();
				$xiao = $long->where('user='.$user)->find();
				$arr['user'] = $user;
				$arr['coin'] = $coin;
				$arr['time'] = time();
				$arr['coin_front'] = $xiao['coin'];
				$arr['coin_after'] = $xiao['coin']+$coin;
				$arr['coin_feeze'] = $xiao['coin_freeze'];
				
                if(M('coin_add')->add($arr)){
					// var_dump($_POST['can_cash']);die; 
					if($_POST['can_cash']==='1'){
						$users_gold = M($sqluser . "_users_gold")->where('user='.$user)->setInc('user_fees',$coin);						
					}else{
						$users_gold = true;
					}
					if($long->where('user='.$user)->setInc('coin',$coin) && $users_gold ){
						$long->commit();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('添加成功');</script>";
						echo "<script> window.location.href='".U('Recharge/index_details',array('user'=>$user))."';</script>";
					}else{
						$long->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('记录添加失败');</script>";
						echo "<script> window.location.href='".U('Recharge/index')."';</script>";
					}
                }else{
					$long->rollback();
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
					echo "<script> alert('金币添加失败');</script>";
                    echo "<script> window.location.href='".U('Recharge/coin_add',array('user'=>$user))."';</script>";
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("1号错误！"); </script>';
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }else{
            $user =I('get.user','','addslashes');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $sqluser = substr($user, 0, 3);
                $sqlname = ''.$sqluser.'_members';
                $sqllist = M($sqlname)->where('user='.$user)->find();
                $user_time = M('verification')->where('user='.$user)->find();
                $this->assign('time',$user_time);
                $this->assign('data',$sqllist);
                creatToken();
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("2号错误！"); </script>';
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }
    }
	
	public function coin_edit(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Recharge/index');
                return;
            }
			$user=I('post.user');
			$coin=I('post.coin');
            if(preg_match("/^1[34578]\d{9}$/",$user)){
                $sqluser = substr($user,0,3);
                $sqlname = ''.$sqluser.'_members';
			
				if($coin<=0){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
					echo "<script> alert('输入信息有误');</script>";
					echo "<script> window.location.href='".U('Recharge/coin_edit',array('user'=>$user))."';</script>";
					exit();
				}
				$long = M("$sqlname");
				$long->startTrans();
				$xiao = $long->where('user='.$user)->find();
				$arr['user'] = $user;
				$arr['coin'] = $coin;
				$arr['time'] = time();
				$arr['coin_front'] = $xiao['coin'];
				$arr['coin_after'] = $xiao['coin']-$coin;
				$arr['coin_feeze'] = $xiao['coin_freeze'];
				$arr['type'] = 1;
				
                if(M('coin_add')->add($arr)){
					// var_dump($_POST['can_cash']);die; 
					if($_POST['can_cash']==='1'){
						$users_gold = M($sqluser . "_users_gold")->where('user='.$user)->setDec('user_fees',$coin);						
					}else{
						$users_gold = true;
					}
					if($long->where('user='.$user)->setDec('coin',$coin) && $users_gold ){
						$long->commit();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('扣除成功');</script>";
						echo "<script> window.location.href='".U('Recharge/index_details',array('user'=>$user))."';</script>";
					}else{
						$long->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('记录添加失败');</script>";
						echo "<script> window.location.href='".U('Recharge/index')."';</script>";
					}
                }else{
					$long->rollback();
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
					echo "<script> alert('金币扣除失败');</script>";
                    echo "<script> window.location.href='".U('Recharge/coin_edit',array('user'=>$user))."';</script>";
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("3号错误！"); </script>';
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }else{
            $user =I('get.user','','addslashes');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $sqluser = substr($user, 0, 3);
                $sqlname = ''.$sqluser.'_members';
                $sqllist = M($sqlname)->where('user='.$user)->find();
                $user_time = M('verification')->where('user='.$user)->find();
                $this->assign('time',$user_time);
                $this->assign('data',$sqllist);
                creatToken();
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("4号错误！"); </script>';
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }
    }
	
	public function index_details(){
		if (IS_POST){
            $Admin = new Admin();
            $post=array_filter(I('post.'));
            $user=$post['start_user'];
            if($user){
                $data = $Admin->Set_Level($user);
                $this->assign('user_info',$data);
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('Recharge/index')."';</script>";
                exit();
            }
        }else{
			$user = I('get.user');
			$sqluser = substr($user, 0, 3);
            $sqlname = ''.$sqluser.'_members';
            $sqllist = M($sqlname)->where('user='.$user)->select();
			$res = M('coin_add')->where('user='.$user)->order('time desc')->select();
			$this->assign('user_info',$sqllist);
			$this->assign('res',$res);
            $this->display();
        }
	}
}