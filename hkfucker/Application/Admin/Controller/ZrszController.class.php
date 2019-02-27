<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Our\Admin;
use Think\Model;
class ZrszController extends AdminController
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
                $data = $Admin->Set_Zrsz();
                $this->assign('user_info',$data);
                $this->display('index');
        }else{
            $this->display();
        }
    }
	
	public function shop_1330(){
		$shop = M('shop');
		$res = $shop->where('id=6')->find();
		if($res['num'] == 0){
			$shop->where('id=6')->setInc('num',1000);
			$shop->where('id=6')->setInc('frequency');
			$shop->where('id=6')->setField('note','开仓时间 13:30');
			M('global_conf')->where('id=21')->setField('value','48600');
			echo '成功';
		}else{
			echo '还没卖完呢，急啥';
		}
	}
	
	
	public function shop_2030(){
		$shop = M('shop');
		$res = $shop->where('id=6')->find();
		if($res['num'] == 0){
			$shop->where('id=6')->setInc('num',1000);
			$shop->where('id=6')->setInc('frequency');
			$shop->where('id=6')->setField('note','开仓时间 20:30');
			M('global_conf')->where('id=21')->setField('value','73800');
			echo '成功';
		}else{
			echo '还没卖完呢，急啥';
		}
	}
    
	/*public function coin_edit(){
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
				$arr['coin_after'] = $xiao['coin']+$coin;
                if(M('coin_add')->add($arr)){
					if($long->where('user='.$user)->setInc('coin',$coin)){
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
                    echo "<script> window.location.href='".U('Recharge/coin_edit',array('user'=>$user))."';</script>";
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
	}*/
}