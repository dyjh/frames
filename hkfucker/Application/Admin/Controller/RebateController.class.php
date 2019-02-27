<?php
namespace Admin\Controller;
use Think\Controller;

class RebateController extends AdminController
{
	/**
	*机构列表
	**/
    public function index(){//机构账户
        $institutions = M('institutions');
        $where = "id>=1";
        $count = $institutions->where($where)->distinct(true)->count();
        //var_dump($count);
        $num =6;

        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
        if(IS_POST){
            $p =intval(I('post.p',1,'addslashes'));
        }else{
            $p =intval(I('get.p',1,'addslashes'));

        }

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
        $list =$institutions->field(true)->where($where)->order('id')->page($p.','.$num)->filter('strip_tags')->select();

        //$this->assign('member',$sum);
        $this->assign('organ',$list);
        if(empty($list)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $this->display();
    }

	
	
	
    /*public function record(){//返佣记录
        if(IS_GET){
			if(!checkToken($_GET['TOKEN'])){		
                $this->redirect('Rebate/record');
                return;
            }
			$post=array_filter(I('get.')); //回调函数过滤数组中的值
            $user=$get['start_user'];
            $time=$get['time'];
			if(!$user){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('Rebate/record')."';</script>";
                exit();
			}
			//var_dump(11111);
			switch($time){
				case 1:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d'))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 2:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-1 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 3:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-2 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 4:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-3 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 5:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-4 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 6:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-5 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 7:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-6 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				default :
				
				break; 
			}
			if($user !== ''){
				$cond['user'] = $user;
			}
			$cond['user'] = $user;
			//var_dump()
			$th = date('Y-m-d H:i:s',time());
			$tms = substr($th,0,7);
			$rebate_record = M(''.$tms.'_rebate_record');
			$cond['id'] = array('egt',1);
			$count = $rebate_record->where($cond)->distinct(true)->count();
			//var_dump($count);
			$num =15;

			$pages = ceil($count/$num);
			//$this->assign('pages',$pages+1);
			
			$p =intval(I('get.p',1,'addslashes'));
										
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
			//var_dump($start);
			$this->assign('start',$start); //分页
			$this->assign('end',$end+1); //分页

			$this->assign('p',$p);			
			
			//var_dump($cond);die;
			$list = $rebate_record->where($cond)->page($p.','.$num)->order('id desc')->select();			
			//var_dump($list);						
			$this->assign('list',$list);
			if(empty($list)){
				$state=0;
			}else{
				$state=1;
			}
			$this->assign('state',$state);
			$this->display('record');
				
		}else{
			$th = date('Y-m-d H:i:s',time());
			$tms = substr($th,0,7);
			$rebate_record = M(''.$tms.'_rebate_record');
			$where = "id>=1";
			$count = $rebate_record->where($where)->distinct(true)->count();
			//var_dump($count);
			$num =15;

			$pages = ceil($count/$num);
			//$this->assign('pages',$pages+1);
			
			$p =intval(I('get.p',1,'addslashes'));
										
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
			//var_dump($start);
			$this->assign('start',$start); //分页
			$this->assign('end',$end+1); //分页

			$this->assign('p',$p);
			//var_dump($where);die;
			
			//$where['user'] = $user;
			$list = $rebate_record->page($p.','.$num)->order('id desc')->select();			
			//var_dump($list);						
			$this->assign('list',$list);
			if(empty($list)){
				$state=0;
			}else{
				$state=1;
			}
			$this->assign('state',$state);
			$this->display('record');
				
		}
    }*/
	
	
	public function record(){//返佣记录
              
        if(isset($_GET['time'])){			
			switch ($_GET['time']){				
				case 1:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d'))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 2:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-1 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 3:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-2 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 4:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-3 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 5:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-4 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 6:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-5 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				case 7:
					$cond['time'] = array(array('egt',strtotime(date('Y-m-d',strtotime('-6 day')))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
				break;
				default:
					
				break;
			}						

		}
		
		if(isset($_GET['type'])){			
			switch ($_GET['type']){				
				case 1:
					$cond['type'] = 1;
				break;
				case 2:
					$cond['type'] = 2;
				break;
				case 3:
					$cond['type'] = 3;
				break;
				default:
					
				break;
			}						

		}
		$num =15;			
		
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
			$cond['user']	=  sprintf(I('get.start_user'));
        }
		
		//var_dump($cond);

		unset($all_tables[0]);						
			
		$th = date('Y-m-d H:i:s',time());
		$tms = substr($th,0,7);
		$rebate_record = M(''.$tms.'_rebate_record');
		$all_list =  $rebate_record->field(true)->where($cond)->select();
			
		array_multisort(i_array_column($all_list,'time'),SORT_DESC,$all_list);
		
		// 获得分页						
		$page = intval(I("get.p",1,'addslashes'));	
		
		$result = page_array($num,$page,$all_list);	
	
		$data   =  $result['array']; 		
	
        $this->assign('start_user',$_GET['start_user']);
		
        $this->assign('state',$_GET['time']);
		
        $this->assign('type',$_GET['type']);
		
        require_once("ThinkPHP/Common/init.php");

        $this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support); //分页

        $this->assign('list',$data);
		
        $this->assign('result',$result);
		
        $this->assign('start',$result['start']);
		
        $this->assign('now_oage',$result['now_oage']);
		
        $this->assign('end',$result['end']+1);
		$this->display('record');
    }
	
	
	

    public function team(){//团队列表
        if(!empty($_GET)){
            //var_dump($_GET['user']) ;exit;
            $user = I('get.user','','addslashes');
            //查询有多少个用户表
            $statistical=M('Statistical');
            $dsql = $statistical->field('id,name')->select();
            if(IS_POST){
                $p =intval(I('post.p',1,'addslashes'));
            }else{
                $p =intval(I('get.p',1,'addslashes'));
            }
            $num =6;
            $sum = array();

            //var_dump($count);die;

            for($i=0;$i<count($dsql);$i++){
                $where['id'] = ':id';
                $bind[':id'] = array($dsql[$i]['id'],\PDO::PARAM_INT);
                $rsql = $statistical->field('name')->where($where)->bind($bind)->filter('strip_tags')->select();

                $members = M(''.$rsql[0]['name'].'_members');
                $conr['team'] = array("LIKE", "%".$user."%");
                $res =$members->where($conr)->order('id')->field(true)->page($p.','.$num)->filter('strip_tags')->select();
                $sum += $res;
            }
            $count = count($sum);
            $pages = ceil($count/$num);
            if($p<1){
                $p =1;
            }else if($p > $pages){
                $p = $pages;
            }
            $showPage = 5;
            $off=floor($pages/2);
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


            $this->assign('member',$sum);
            $this->display('team');
        }
        //var_dump($sum);


    }
    public function edit(){//返佣设置
        if(IS_POST){
            $commission=M('commission');
            $count=$commission->distinct(true)->count();
            //print_r($_POST);die;
            $id=array_keys($_POST);
            for($i=1;$i<= $count;$i++){
                $data['poundage_value']=$_POST[$i];
                //print_r($id[$i-1]);
                //print_r($_POST[$i]);echo '<br />';
                $where['id'] = ':id';
                $bind[':id'] = array($id[$i-1],\PDO::PARAM_INT);
                if($commission->where($where)->bind($bind)->filter('strip_tags')->save($data)){

                }
            }
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            echo '<script> alert("修改成功！"),history.back(); </script>';
            //echo "<script> window.location='http://www.jrohl.com/index.php/Admin/Admin/config.html ';</script>";
            exit();
        }else{
            $commission=M('commission');
            $data=$commission->filter('strip_tags')->select();
            $this->assign('data',$data);
            $this->display();
        }
    }
    public function set(){//返佣修改
        if(!empty($_POST)){
            if($_POST['poundage_value']!==''){
                //$id = I('post.id');
                //$conm['poundage_value'] = I('post.poundage_value');
                //echo $id,$conm['poundage_value'];die;
                $commission = M('commission');
                $where['id'] = ':id';
                $bind[':id'] = array(I('post.id'),\PDO::PARAM_INT);
                $commission->poundage_value = I('post.poundage_value','','addslashes');
                if($commission->where($where)->bind($bind)->filter('strip_tags')->save()!==false){
                    echo 200;
                }else{
                    echo 400;
                }
            }else{
                echo 400;
            }
        }
    }

    
    public function remove(){//删除返佣
        if(IS_GET){
            $user = I('get.user');
			$pre = substr($user,0,3);
			$member = M(''.$pre.'_members');
            $institutions = M('institutions');
			$cond['identity'] = '普通用户';
            if($institutions->where('user='.$user.'')->delete()!==false && $member->where('user='.$user.'')->save($cond)!==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改成功！"),history.back(); </script>';
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }
        }
    }
	
	
	/**
     * seeds_num()   仓库种子总数
     * **/
	public function seeds_num($name){
        $statistical=M('Statistical');
        $dsql = $statistical->field('name')->select();
        //return $dsql;
        $number = count($dsql);
        $or = array();
        for($i=0;$i<$number;$i++){
            $prop_num = M(''.$dsql[$i]['name'].'_record_conversion');
			$cond['type'] = 'c';
			//$cond['pay_time'] = array(array('gt',(time()-86400)),array('lt',time()));
			$cond['name'] = $name;
            $num_sql = $prop_num->where($cond)->sum('num');
            $or[] =$num_sql;
        }
        $sum = 0;
        $sum_or = count($or);
        for($j=0;$j<$sum_or;$j++){
            $sum += $or[$j];
        }
        return $sum;
    }
	
	
	/**
     * seeds_day()   每天仓库种子总数
     * **/
	public function seeds_day($name){
        $statistical=M('Statistical');
        $dsql = $statistical->field('name')->select();
        //return $dsql;
        $number = count($dsql);
        $or = array();
        for($i=0;$i<$number;$i++){
            $prop_num = M(''.$dsql[$i]['name'].'_record_conversion');
			$cond['type'] = 'c';
			$cond['buy_time'] = array(array('egt',strtotime(date('Y-m-d'))),array('lt',strtotime(date('Y-m-d',strtotime('+1 day')))));
			$cond['name'] = $name;
            $num_sql = $prop_num->where($cond)->sum('num');
            $or[] =$num_sql;
        }
        $sum = 0;
        $sum_or = count($or);
        for($j=0;$j<$sum_or;$j++){
            $sum += $or[$j];
        }
        return $sum;
    }


	
	 
    public function exchange(){//重生统计
        $name1 = '土豆';
        $name2 = '草莓';
        $name3 = '樱桃';
        $name4 = '稻米';
        $name5 = '葡萄';
        $name6 = '番茄';
		$seeds_a = $this->seeds_num($name1);
		$seeds_b = $this->seeds_num($name2);
		$seeds_c = $this->seeds_num($name3);
		$seeds_d = $this->seeds_num($name4);
		$seeds_e = $this->seeds_num($name5);
		$seeds_f = $this->seeds_num($name6);
		//
		$seeds_g = $this->seeds_day($name1);
		$seeds_h = $this->seeds_day($name2);
		$seeds_i = $this->seeds_day($name3);
		$seeds_j = $this->seeds_day($name4);
		$seeds_k = $this->seeds_day($name5);
		$seeds_l = $this->seeds_day($name6);
        //var_dump($seeds_a);
        //var_dump($seeds_b);
        //var_dump($seeds_c);
        //var_dump($seeds_d);
		$this->assign('tudou',$seeds_a);
		$this->assign('caomei',$seeds_b);
		$this->assign('yingtao',$seeds_c);
		$this->assign('daomi',$seeds_d);
		$this->assign('putao',$seeds_e);
		$this->assign('fanqie',$seeds_f);
		//
		$this->assign('day_tudou',$seeds_g);
		$this->assign('day_caomei',$seeds_h);
		$this->assign('day_yingtao',$seeds_i);
		$this->assign('day_daomi',$seeds_j);
		$this->assign('day_putao',$seeds_k);
		$this->assign('day_fanqie',$seeds_l);
        $this->display('exchange');
    }
	
	
	
	
	
	
	//重生详情
	
	public function deta(){
		
		if(!checkToken($_GET['TOKEN'])){		
			$this->redirect('Rebate/deta');
			return;
		}
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
			$cond['user']	=  sprintf(I('get.start_user'));
        }
		//var_dump($_GET);
		$statistical=M('Statistical');
		$dsql = $statistical
			  ->field('id,name')
			  ->select();
			  
		$arr = array();
		$dsql_num = count($dsql);
		for($i=0;$i<$dsql_num;$i++){
			$conversion             = M(''.$dsql[$i]['name'].'_record_conversion');
			$cond['type'] = 'c';
			$sql = $conversion
				 ->field(true)
				 ->where($cond)
				 ->select();
			$arr[$i] = $sql;
		}
		$list = array();
		$temp = 0;
		//$arr_num = count($arr);
		for($i=0;$i<count($arr);$i++){
			for($j=0;$j<count($arr[$i]);$j++) {
				$list[$temp] = $arr[$i][$j];
				$temp++;
			}
		}
		$num =15;
		unset($all_tables[0]);	
		array_multisort(i_array_column($list,'buy_time'),SORT_DESC,$list);
		
		// 获得分页						
		$page = intval(I("get.p",1,'addslashes'));	
		
		$result = page_array($num,$page,$list);	
	    //var_dump($result['array']);
		$data   =  $result['array']; 		
	
        $this->assign('start_user',$_GET['start_user']);
		
        //$this->assign('state',$_GET['time']);
		
        //$this->assign('type',$_GET['type']);
		
        require_once("ThinkPHP/Common/init.php");

        $this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support); //分页

        $this->assign('list',$data);
		
        //$this->assign('result',$result);
		
        $this->assign('start',$result['start']);
		
        $this->assign('now_oage',$result['now_oage']);
		
        $this->assign('end',$result['end']+1);
		$this->assign('list',$result['array']);
		$this->display('deta');
				
		
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


}