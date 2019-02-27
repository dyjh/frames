<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27 0027
 * Time: 下午 2:37
 */

namespace Admin\Controller;
use Think\Tool;
use Org\Our\Admin;
class BackerController extends AdminController
{

	public function level(){
		//print_r($_POST);die;
		set_time_limit(0);
        if(IS_POST){
            $level =intval(I('post.level',1,'addslashes'));
        }else{
            $level =intval(I('get.level',1,'addslashes'));
        }
		//print_r($level);
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
		//print_r($data_s);die;
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m=M($cases)->where('level ='.$level)->order('level')->select();
			$count=M($cases)->where('level ='.$level)->order('level')->count();//->where('level ='.$level)->order('level')
            for($i=0;$i<$count;$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$l=0;
		$data_arr = array();
		$guolv = array(18382050570,18228068397,18780164595,18768477519,18584084806,15802858094,13308081857,15140491373,15008210274,14747470001,14747470002,14747470003,14747470004,14747470005,14747470006,14747470007,14747470008,14747470009,14747470010,14747470011,14747470012,14747470013,14747470014,14747470015,14747470016);
		foreach ($data_s as $key=>$val){
			$ca=''.$val['name'].'_prop_warehouse';
			$m=M($ca)->where('props="种子" AND num>="100000"')->select();
			$m_count = count($m);
				for($a=0;$a<$m_count;$a++){
                $user=$m[$a]['user'];
				if(in_array($user,$guolv)){
					continue;
			}
				$sql_count = substr($user, 0, 3);
				$cases_count=''.$sql_count.'_members';
				$m_user = M($cases_count)->where('user='.$user)->find();
				$data_arr[$l]=$m[$a];
				$data_arr[$l]['level'] = $m_user['level'];
				$data_arr[$l]['name'] = $m_user['name'];
                $l++;		
            }
					
		}
        $columnKey='level';
        array_multisort(i_array_column($data_arr,$columnKey),SORT_ASC,$data_arr);   //数组排序
		$this->assign('data_arr',$data_arr);
		$Admin = New Admin();
		$data_num = $Admin->arr_num($data_arr);
		$this->assign('data_num',$data_num);
		$this->assign('l',$l);
		
        array_multisort(i_array_column($input,$columnKey),SORT_ASC,$input);   //数组排序
        $seeds=array();
        $arr=array();
		//$seeds['caomei'] = 0;
		
		foreach($input as $key=>$val){

			
			 $sqluser = substr($val['user'], 0, 3);
             $sqlname = ''.$sqluser.'_seed_warehouse';
			 $prop = ''.$sqluser.'_prop_warehouse';
             $caomei = M($sqlname)->where('user="'.$val['user'].'" AND seeds="草莓"')->sum('num');
             $tudou = M($sqlname)->where('user="'.$val['user'].'" AND seeds="土豆"')->sum('num');
             $daomi = M($sqlname)->where('user="'.$val['user'].'" AND seeds="稻米"')->sum('num');
             $yingtao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="樱桃"')->sum('num');
             $putao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="葡萄"')->sum('num');
             //$v_putao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="葡萄" AND num>100')->find();
			 //print_r($v_putao.'/');
             $fanqie = M($sqlname)->where('user="'.$val['user'].'" AND seeds="番茄"')->sum('num');
             $boluo = M($sqlname)->where('user="'.$val['user'].'" AND seeds="菠萝"')->sum('num');
             $zhongzi = M($prop)->where('user="'.$val['user'].'" AND props="种子"')->sum('num');
			 $seeds['caomei']=$seeds['caomei']+$caomei;
			 $seeds['tudou']=$seeds['tudou']+$tudou;
			 $seeds['daomi']=$seeds['daomi']+$daomi;
			 $seeds['yingtao']=$seeds['yingtao']+$yingtao;
			 $seeds['putao']=$seeds['putao']+$putao;
			 $seeds['fanqie']=$seeds['fanqie']+$fanqie;
			 $seeds['boluo']=$seeds['boluo']+$boluo;
			 $seeds['zhongzi']=$seeds['zhongzi']+$zhongzi;
			 /*if(!empty($v_putao)){
				 $ssnum += $v_putao['num'];
				 $arr[]=$v_putao;
			 }*/
			 
		}
		$seeds['zongshu']=$seeds['caomei']+$seeds['tudou']+$seeds['daomi']+$seeds['yingtao']+$seeds['putao']+$seeds['fanqie']+$seeds['boluo'];
		//print($ssnum);
		//echo '</br>';
		//print_r($arr);
		$this->assign('seeds',$seeds);
		
        $count=count($input);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);

        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =intval(I('get.o',1,'addslashes'));
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$o-$off;
        $end=$o+$off;
        //起始页
        if($o-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('o',$o);
        $res =array_slice($input,($o-1)*8,8);
        if(empty($res)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $this->assign('data',$res);//分页内容


        $count_p=M('backmoney_user')->distinct(true)->count();
        $num_p =6;
        $pages_p = ceil($count_p/$num_p);
        //$this->assign('pages',$pages+1);
        if(IS_POST){
            $p =intval(I('post.p',1,'addslashes'));
        }else{
            $p =intval(I('get.p',1,'addslashes'));
        }

        if($p<1){
            $p =1;
        }else if($p > $pages_p){
            $p = $pages_p;
        }
        $showPage_p = 5;
        $off_p=floor($showPage_p/2);
        $start_p=$p-$off_p;
        $end_p=$p+$off_p;
        //起始页
        if($p-$off_p < 1){
            $start_p = 1;
            $end_p = $showPage_p;
        }
        //结束页
        if($p+$off_p > $pages_p){
            $end_p = $pages_p;
            $start_p = $pages_p-$showPage_p+1;
        }

        if($pages_p < $showPage_p){
            $start_p = 1;
            $end_p = $pages_p;
        }
        $this->assign('count',$count);
        $this->assign('start_p',$start_p); //分页
        $this->assign('end_p',$end_p+1); //分页
        $this->assign('level',$level);
        $this->assign('p',$p);
        $list =M('backmoney_user')->page($p.','.$num_p)->filter('strip_tags')->select();
        if(empty($list)){
            $state_p=0;
        }else{
            $state_p=1;
        }
        $this->assign('state_p',$state_p);
        $this->assign('notice', $list); // 赋值数据集
        $this->display();
    }
	
    public function conf(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Back/index');
                return;
            }
            $start=I('post.start','','addslashes');
            $end=I('post.end','','addslashes');
            //print_r($end);
            $str='';
            for($i=$start;$i<=$end;$i++){
                $str.=''.$i.',';
            }
            $ban_cycle=substr($str,0,strlen($str)-1);
            $allow_seed=I('post.allow_seed','','addslashes');
            $type=I('post.type','','addslashes');
            if($type=='one'){
                $data['user']=I('post.user','','addslashes');
                $lv=I('post.level','','addslashes');
                $table=new Tool();
                $case='members';
                $tel=$data['user'];
                $case_m=$table->table($tel,$case);
                $where['user'] = ':user';
                $data_m=M($case_m)->where($where)->bind(':user',$tel,\PDO::PARAM_STR)->filter('strip_tags')->find();
                if($lv==$data_m['level']){
                    $data['ban_cycle']=$ban_cycle;
                    $data['allow_seed']=$allow_seed;
                    if(M('backmoney_user')->filter('strip_tags')->add($data)){
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("设置成功！"),history.back(); </script>';
                        exit();
                    }else{
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("设置失败，请重试！"),history.back(); </script>';
                        exit();
                    }
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("该用户已升级！"),history.back(); </script>';
                    exit();
                }
            }else{
                $data_s=M('statistical')->select();
                foreach ($data_s as $k=>$v){
                    $cases=''.$v['name'].'_members';
                    $where['level'] = ':level';
                    $where['cost_state'] = ':cost_state';
                    $bion[':level'] = array('lt','4',\PDO::PARAM_INT);
                    $bion[':cost_state'] = array('1',\PDO::PARAM_INT);
                    $data_m=M($cases)->where($where)->bind($bion)->field('user')->order('level')->filter('strip_tags')->select();
                    $count=M($cases)->where($where)->bind($bion)->distinct(true)->count();
                    for($i=0;$i<$count;$i++){
                        $user=$data_m[$i]['user'];
                        $back['user'] = ':user';
                        $data_back=M('backmoney_user')->where($back)->bind(':user',$user,\PDO::PARAM_STR)->filter('strip_tags')->find();
                        if(empty($data_back)){
                            $input['user']=$data_m[$i]['user'];
                            $input['allow_seed']=$allow_seed;
                            $input['ban_cycle']=$ban_cycle;
                            M('backmoney_user')->filter('strip_tags')->add($input);
                        }
                    }
                }
            }
        }else{
            $type=I('get.type','','addslashes');
            //print_r($type);
            if($type=='one'){
                $level=I('get.level','','addslashes');
                $user=I('get.user','','addslashes');
                $this->assign('user',$user);
                $this->assign('level',$level);
            }
            creatToken();
            $this->assign('type',$type);
            $this->display();
        }
    }
    public function edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Back/index');
                return;
            }
            $day=I('post.day','','addslashes');
            //print_r($end);
            $allow_seed=I('post.allow_seed','','addslashes');

            $data['user']=I('post.user','','addslashes');
            //print_r($data['user']);die;
            $ban_cycle=I('post.ban_cycle','','addslashes');
            $data_p=strpos($ban_cycle,$day);
            if($data_p==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("该日期不存在，请重新选日期！"),history.back(); </script>';
                exit();
            }else{
                $first=substr($ban_cycle,0,1);
                if($first==$day){
                    $a=''.$first.',';
                    $ban_cycle=str_replace($a,"",$ban_cycle);
                }else{
                    $a=','.$day.'';
                    $ban_cycle=str_replace($a,"",$ban_cycle);
                }
                //print_r($ban_cycle);die;
                $lv=I('post.level','','addslashes');
                $table=new Tool();
                $case='members';
                $tel=$data['user'];
                $case_m=$table->table($tel,$case);
                $where['user'] = ':user';
                $data_m=M($case_m)->where($where)->bind(':user',$tel,\PDO::PARAM_STR)->filter('strip_tags')->find();
                if($lv==$data_m['level']){
                    $data['ban_cycle']=$ban_cycle;
                    $data['allow_seed']=$allow_seed;
                    if(M('backmoney_user')->where($where)->bind(':user',$tel,\PDO::PARAM_STR)->filter('strip_tags')->save($data) !==false){
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("设置成功！"),history.back(); </script>';
                        exit();
                    }else{
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("设置失败，请重试！"),history.back(); </script>';
                        exit();
                    }
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("该用户已升级！"),history.back(); </script>';
                    exit();
                }
            }
        }else{
            creatToken();
            $user=I('get.user','','addslashes');
            $table=new Tool();
            $case='members';
            $tel=$user;
            $case_m=$table->table($tel,$case);
            $where['user'] = ':user';
            $data_m=M($case_m)->where($where)->bind(':user',$tel,\PDO::PARAM_STR)->filter('strip_tags')->find();
            $level=$data_m['level'];
            $data=M('backmoney_user')->where($where)->bind(':user',$user,\PDO::PARAM_STR)->filter('strip_tags')->find();
            $this->assign('data',$data);
            $this->assign('user',$user);
            $this->assign('level',$level);
            $this->display();
        }
    }
	
	public function active(){
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_prop_warehouse';
			$data_m = M($cases)->where('props="种子" AND num<=99')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_members';
			$dd = M("$prop")->where('user='.$val['user'])->field('user,level,num_id,name,nickname')->find();
			$yi[$dd[level]][] = $dd;
			}
		//print_r(count($yi[1]));die;
		//print_r($yi[6]);
		$y = date('Y');
		$m = date('m');
		$d = date('d');
		$today = mktime(0,0,0,$m,$d,$y)-24*3600;
		$tomorrow = $today+20*3600;
		$tomo = $today+21*3600;
		$bhy_jr = array();
		$bhy_jr[0] = M('user_bhy')->where('time>"'.$tomorrow.'" AND time<"'.$tomo.'" AND type=2')->field('yi,er,san,si,wu,liu,qi,zongshu')->find();
		$bhy_jr[0]['type'] = '昨日';
		$bhy_jr[1]['type'] = '今日';
		$bhy_jr[1]['yi'] = count($yi[1]);
		$bhy_jr[1]['er'] = count($yi[2]);
		$bhy_jr[1]['san'] = count($yi[3]);
		$bhy_jr[1]['si'] = count($yi[4]);
		$bhy_jr[1]['wu'] = count($yi[5]);
		$bhy_jr[1]['liu'] = count($yi[6]);
		$bhy_jr[1]['qi'] = count($yi[7]);
		$bhy_jr[1]['zongshu'] = count($input);
		$bhy_jr[2]['type'] = '比例';
		$bhy_jr[2]['yi'] = round((1-($bhy_jr[0]['yi']/$bhy_jr[1]['yi']))*100,2).'%';
		$bhy_jr[2]['er'] = round((1-($bhy_jr[0]['er']/$bhy_jr[1]['er']))*100,2).'%';
		$bhy_jr[2]['san'] = round((1-($bhy_jr[0]['san']/$bhy_jr[1]['san']))*100,2).'%';
		$bhy_jr[2]['si'] = round((1-($bhy_jr[0]['si']/$bhy_jr[1]['si']))*100,2).'%';
		$bhy_jr[2]['wu'] = round((1-($bhy_jr[0]['wu']/$bhy_jr[1]['wu']))*100,2).'%';
		$bhy_jr[2]['liu'] = round((1-($bhy_jr[0]['liu']/$bhy_jr[1]['liu']))*100,2).'%';
		$bhy_jr[2]['qi'] = round((1-($bhy_jr[0]['qi']/$bhy_jr[1]['qi']))*100,2).'%';
		$bhy_jr[2]['zongshu'] = round((1-($bhy_jr[0]['zongshu']/$bhy_jr[1]['zongshu']))*100,2).'%';
		$this->assign('bhy_jr',$bhy_jr);
		$w=0;
        $sl=array();
		//print_r($data_s);die;
        foreach ($data_s as $k=>$v){
            $ca=''.$v['name'].'_members';
			$count=M($ca)->order('level')->count();
			$level=M($ca)->order('level')->field('user,level')->select();
			
			foreach($level as $a=>$b){
				$sl[$b['level']][] = $b;
			}
        }
		$jr = array();
		$jr[0] = M('user_bhy')->where('time>"'.$tomorrow.'" AND time<"'.$tomo.'" AND type=1')->field('yi,er,san,si,wu,liu,qi,zongshu')->find();
		$jr[0]['type'] = '昨日';
		$jr[1]['type'] = '今日';
		$jr[1]['yi'] = count($sl[1]);
		$jr[1]['er'] = count($sl[2]);
		$jr[1]['san'] = count($sl[3]);
		$jr[1]['si'] = count($sl[4]);
		$jr[1]['wu'] = count($sl[5]);
		$jr[1]['liu'] = count($sl[6]);
		$jr[1]['qi'] = count($sl[7]);
		$jr[1]['zongshu'] = count($sl[1])+count($sl[2])+count($sl[3])+count($sl[4])+count($sl[5])+count($sl[6])+count($sl[7]);
		$jr[2]['type'] = '比例';
		$jr[2]['yi'] = round((1-($jr[0]['yi']/$jr[1]['yi']))*100,2).'%';
		$jr[2]['er'] = round((1-($jr[0]['er']/$jr[1]['er']))*100,2).'%';
		$jr[2]['san'] = round((1-($jr[0]['san']/$jr[1]['san']))*100,2).'%';
		$jr[2]['si'] = round((1-($jr[0]['si']/$jr[1]['si']))*100,2).'%';
		$jr[2]['wu'] = round((1-($jr[0]['wu']/$jr[1]['wu']))*100,2).'%';
		$jr[2]['liu'] = round((1-($jr[0]['liu']/$jr[1]['liu']))*100,2).'%';
		$jr[2]['qi'] = round((1-($jr[0]['qi']/$jr[1]['qi']))*100,2).'%';
		$jr[2]['zongshu'] = round((1-($jr[0]['zongshu']/$jr[1]['zongshu']))*100,2).'%';
		
		/*$Admin = New Admin();
		$res[5] = $Admin->seed_level(5);
		$res[6] = $Admin->seed_level(6);
		$res[7] = $Admin->seed_level(7);
		print_r($res);*/
		$this->assign('jr',$jr);
        $this->display();
    }
	
	public function back(){
		$b = M('repo_record');
		$set = $b->query("SELECT time,sum(num) from repo_record GROUP BY time");
		$user = $b->query("SELECT user,user from repo_record GROUP BY user");
		$ming = array();
		foreach($user as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_members';
			$dd = M("$prop")->where('user='.$val['user'])->field('user,level,num_id,name,nickname')->find();
			//$ming[$dd[level]][] = $dd;
			$ming[] = $dd;
		}
		$columnKey='level';
        array_multisort(i_array_column($ming,$columnKey),SORT_DESC,$ming);   //数组排序
		$zs = $b->sum('num');
		$state = count($ming);
		
		$this->assign('row',$set);
		$this->assign('state',$state);
		$this->assign('m',$ming);
		$this->assign('zs',$zs);
		$this->display();
	}
	
	public function back_details(){
		$user=I('get.user','','addslashes');
		$b = M('repo_record');
		$res = $b->where('user='.$user)->select();
		$res_details = $b->where('user='.$user)->sum(num);
		$coun = count($res);
		$this->assign('u',$user);
		$this->assign('r',$res);
		$this->assign('c',$coun);
		$this->assign('d',$res_details);
		$this->display();
	}
	
	
	//一键守护功能
	public function ceshi_01(){
		echo '已关闭状态';
		die;
		exit;
		
		set_time_limit(0);
		
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m = M($cases)->where('level=6')->field('user,level,num_id,name,nickname')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$u=0;
		$cg=0;
		$gc=0;
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_managed_to_record';
			$dd = M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=1')->find();
			/*if(!empty($dd)){
			$yi[] = $dd;
			}*/
			/*$yi[] = $dd;
			$u++;*/
			
			if($dd == null){
				$padd['user'] = $val['user'];
				$padd['service_type'] = 1;
				$padd['end_time'] = time()+345600;
				$padd['state'] = 0;
				if(M("$prop")->add($padd)){
					echo '成功1/';
					$gc++;
					}
				}else{
				if(M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=1')->setInc('end_time',345600)){
					echo '成功2/';
					$cg++;
					}	
				}
			$u++;
			}
			echo $u;
			echo '<br/>';
			echo $cg;
			echo '<br/>';
			echo $gc;
			
			//$ppd=array_filter($yi);
			//print_r(count($ppd));
		/*}	
		print_r($yi);
		echo $u;*/
		
    }
	
	public function ceshi_02(){
		echo '已关闭状态';
		die;
		exit;
		
		set_time_limit(0);
		
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m = M($cases)->where('level=6')->field('user,level,num_id,name,nickname')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$u=0;
		$cg=0;
		$gc=0;
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_managed_to_record';
			$dd = M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=2')->find();
			/*if(!empty($dd)){
			$yi[] = $dd;
			}*/
			/*$yi[] = $dd;
			$u++;*/
			
			if($dd == null){
				$padd['user'] = $val['user'];
				$padd['service_type'] = 2;
				$padd['end_time'] = time()+345600;
				$padd['state'] = 0;
				if(M("$prop")->add($padd)){
					echo '成功1/';
					$gc++;
					}
				}else{
				if(M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=2')->setInc('end_time',345600)){
					echo '成功2/';
					$cg++;
					}	
				}
			$u++;
			}
			echo $u;
			echo '<br/>';
			echo $cg;
			echo '<br/>';
			echo $gc;
			
			//$ppd=array_filter($yi);
			//print_r(count($ppd));
		/*}	
		print_r($yi);
		echo $u;*/
		
    }
	
	public function ceshi_03(){
		echo '已关闭状态';
		die;
		exit;
		
		set_time_limit(0);
		
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m = M($cases)->where('level=6')->field('user,level,num_id,name,nickname')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$u=0;
		$cg=0;
		$gc=0;
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_managed_to_record';
			$dd = M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=3')->find();
			/*if(!empty($dd)){
			$yi[] = $dd;
			}*/
			/*$yi[] = $dd;
			$u++;*/
			
			if($dd == null){
				$padd['user'] = $val['user'];
				$padd['service_type'] = 3;
				$padd['end_time'] = time()+345600;
				$padd['state'] = 0;
				if(M("$prop")->add($padd)){
					echo '成功1/';
					$gc++;
					}
				}else{
				if(M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=3')->setInc('end_time',345600)){
					echo '成功2/';
					$cg++;
					}	
				}
			$u++;
			}
			echo $u;
			echo '<br/>';
			echo $cg;
			echo '<br/>';
			echo $gc;
			
			//$ppd=array_filter($yi);
			//print_r(count($ppd));
		/*}	
		print_r($yi);
		echo $u;*/
		
    }
	
	public function ceshi_04(){
		echo '已关闭状态';
		die;
		exit;
		
		set_time_limit(0);
		
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m = M($cases)->where('level=6')->field('user,level,num_id,name,nickname')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$u=0;
		$cg=0;
		$gc=0;
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_managed_to_record';
			$dd = M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=4')->find();
			/*if(!empty($dd)){
			$yi[] = $dd;
			}*/
			/*$yi[] = $dd;
			$u++;*/
			
			if($dd == null){
				$padd['user'] = $val['user'];
				$padd['service_type'] = 4;
				$padd['end_time'] = time()+345600;
				$padd['state'] = 0;
				if(M("$prop")->add($padd)){
					echo '成功1/';
					$gc++;
					}
				}else{
				if(M("$prop")->where('user="'.$val['user'].'" AND state=0 AND end_time>1505128800 AND service_type=4')->setInc('end_time',345600)){
					echo '成功2/';
					$cg++;
					}	
				}
			$u++;
			}
			echo $u;
			echo '<br/>';
			echo $cg;
			echo '<br/>';
			echo $gc;
			
			//$ppd=array_filter($yi);
			//print_r(count($ppd));
		/*}	
		print_r($yi);
		echo $u;*/
		
    }
	//xyl 查询种植情况
	public function	zt(){
	if(IS_POST){
		set_time_limit(0);
		$level =intval(I('post.level',1,'addslashes'));      //post
		if(!empty($_POST['start_time'])){
		$start=I('post.start_time');
		$s_y=substr($start,0,4);
		$s_m=substr($start,5,2);
		$s_d=substr($start,8,2);
		$start_time = mktime(0,0,0,$s_m,$s_d,$s_y);
		$end=I('post.end_time');
		$e_y=substr($end,0,4);
		$e_m=substr($end,5,2);
		$e_d=substr($end,8,2);
		$end_time = mktime(0,0,0,$e_m,$e_d,$e_y);
		$member=array();
		$f=0;
		$verification=M('verification')->where('regis_time>'.$start_time.' AND regis_time<'.$end_time.'')->select();
		
		foreach($verification as $key=>$val){
			$member_team=M('team_relationship')->field('user,referees')->where('user='.$val['user'].' AND level='.$level)->find();
			if(!empty($member_team)){
				$member[$f]=$member_team;
				if(!empty($member[$f]['referees'])){
					$member_ref=M('team_relationship')->where('user='.$member[$f]['referees'].'')->find();
					$member[$f]['level']=$member_ref['level'];
					$member[$f]['num']=$member_ref['activity_info'];
				}else{
					$member[$f]['level']='该用户无推荐人';
					$member[$f]['num']=0;
				}
				$member[$f]['count']=M('team_relationship')->where('referees='.$member[$f]['user'].'')->count();
				
				//echo $member['user'];echo '<br/>';
				$f++;
			}
		}
		
		$state=1;
		$ty=0;
		$seed =I('post.seed','土豆','addslashes');      //post
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$this->assign('er',$member);
		$this->assign('state',$state);
		$this->assign('level',$level);
		$this->assign('seed',$seed);
		$this->assign('u',$f);
		$this->assign('type',$ty);
		$this->display();
		exit;
		}

		$seed =I('post.seed','土豆','addslashes');      //post
		$ts =intval(I('post.ts',1,'addslashes')); 
		$f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m = M($cases)->where('level="'.$level.'"')->field('user,level,num_id,name,nickname')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		$u=0;
		$y=0;
		$l=0;
		$yi = array();
		$er = array();
		$dqsj = 3600*($ts*24);
		$ygsj = time()-$dqsj;
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_planting_record';
			$dd = M("$prop")->where('user="'.$val['user'].'" AND seed_type="'.$seed.'" AND time>"'.$ygsj.'"')->count();
			$find = M("$prop")->where('user="'.$val['user'].'" AND seed_type="'.$seed.'" AND time>"'.$ygsj.'"')->find();
			switch ($dd){
            case true :
                $yi[$find['user']] = $dd;
				$y++;
                break;
            case false  :
                $er[]['user'] = $val['user'];	
				$u++;
                break;
            default :
                $this->error('参数错误');
                break;
        }
		$l++;
		}
		$state=0;
		$start_time  =  strtotime(date("Y-m-d"));
		$this->assign('start_time',$start_time);
		$this->assign('er',$er);
		$this->assign('u',$u);
		$this->assign('start_time',$start_time);
		$this->assign('level',$level);
		$this->assign('seed',$seed);
		$this->assign('state',$state);
		$this->assign('ts',$ts);
		$this->display();
	}else{
		//$seed =I('get.seed','土豆','addslashes');      //get传值
		//$ts =intval(I('get.ts',1,'addslashes'));     //get传值
		$start_time  =  empty($_GET['start_time'])  ? strtotime(date("Y-m-d"))  : strtotime($_GET['start_time']);
		$end_time  	 =  empty($_GET['end_time'])    ? $start_time + (3600 * 24) : strtotime($_GET['end_time']);
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$this->assign('seed',$seed);
		$this->assign('state',$state);
		$this->assign('ts',$ts);
		$this->display();
	}
		
	}
	
	
	public function sjdy(){
		
		if(!is_numeric($_POST['sj']) || !is_numeric($_POST['num']) || empty($_POST['seed_name'])){
			  echo '提交填写不完整';
			  exit;
		}
		
		$start=I('post.start_time');
		$s_y=substr($start,0,4);
		$s_m=substr($start,5,2);
		$s_d=substr($start,8,2);
		$start_time = mktime(0,0,0,$s_m,$s_d,$s_y);
		$end=I('post.end_time');
		$e_y=substr($end,0,4);
		$e_m=substr($end,5,2);
		$e_d=substr($end,8,2);
		$end_time = mktime(0,0,0,$e_m,$e_d,$e_y);
		$sj_num = $_POST['sj'];
		$data['seed'] = $_POST['seed_name'];
		$user = explode(',',$_POST['user_number']);
		$data['num'] = $_POST['num'];
		$data['imm_num'] = $_POST['num'];
        $data['type'] = $_POST['type'];
		
		if($data['type']==1){
			$data['start_time'] = $start_time;
		    $data['end_time'] = $end_time;
		}else{
			$data['start_time'] = 0;
		    $data['end_time'] = 0;
		}
			
		shuffle($user);
		
		$seed_orientation = M('seed_orientation');
		$scessue = 0;
		$err = 0;
		$readly = 0;
		$str = "";
		for($i=0;$i<$sj_num;$i++){
		   $list = $seed_orientation->where('user="'.$user[$i].'" and seed="'.$data['seed'].'"')->find();
		   if($list){
			   $readly++;
			   $err++;
			   $str.= $user[$i].' ';
			   continue;
		   }else{
			   $data['user'] = $user[$i];	
			   $res = $seed_orientation->add($data);
			   if($res){
			      $scessue++;
			   }else{
			      $err++;
			   }
		   } 	   
		}
		echo '添加成功'.$scessue.'个，添加失败'.$err.'个，有'.$readly.'个已经定向<br/>';
		if($readly>0){
			echo $str;
		}
	}
	
	public function bltj(){
		set_time_limit(0);
		$Admin = New Admin();
		
		$res[1] = $Admin->seed_level(1);
		$res[2] = $Admin->seed_level(2);
		$res[3] = $Admin->seed_level(3);
		$res[4] = $Admin->seed_level(4);
		$res[5] = $Admin->seed_level(5);
		$res[6] = $Admin->seed_level(6);
		$res[7] = $Admin->seed_level(7);
		$count = count($res)+1;
		$f = 0;
		$arr = array();
		for($i=1;$i<$count;$i++){
			$arr['tudou'] += $res[$i]['tudou'];
			$arr['caomei'] += $res[$i]['caomei'];
			$arr['yingtao'] += $res[$i]['yingtao'];
			$arr['daomi'] += $res[$i]['daomi'];
			$arr['fanqie'] += $res[$i]['fanqie'];
			$arr['putao'] += $res[$i]['putao'];
			$arr['boluo'] += $res[$i]['boluo'];
			$arr['zhongzi'] += $res[$i]['zhongzi'];
			$arr['zongshu'] += $res[$i]['zongshu'];
            $f++;		
            }
		//print_r($res);
		$this->assign('arr',$arr);
		$this->assign('seeds',$res);
		
		
		$gudong[0] = $Admin->Set_gudong(15908144678);//貔貅
		$gudong[1] = $Admin->Set_gudong(18502838021);//藤藤菜
		$gudong[2] = $Admin->Set_gudong(13086631981);//采月姐姐
		$gudong[3] = $Admin->Set_gudong(18382077208);//赚钱能手
		$gudong[4] = $Admin->Set_gudong(13882139257);//渝凡渝凡
		$gudong[5] = $Admin->Set_gudong(17628281862);//小白
		$gudong[6] = $Admin->Set_gudong(18628282865);//凯撒大帝
		$countt = count($gudong);
		$arrt = array();
		for($g=0;$g<$countt;$g++){
			$arrt['tudou'] += $gudong[$g]['tudou'];
			$arrt['caomei'] += $gudong[$g]['caomei'];
			$arrt['yingtao'] += $gudong[$g]['yingtao'];
			$arrt['daomi'] += $gudong[$g]['daomi'];
			$arrt['fanqie'] += $gudong[$g]['fanqie'];
			$arrt['putao'] += $gudong[$g]['putao'];
			$arrt['boluo'] += $gudong[$g]['boluo'];
			$arrt['zhongzi'] += $gudong[$g]['zhongzi'];
			$arrt['zongshu'] += $gudong[$g]['zongshu'];
            }
		$this->assign('gudong',$gudong);
		$this->assign('arrt',$arrt);
		
		$sy = array();
		$sy['tudou'] = $arr['tudou'] - $arrt['tudou'];
		$sy['caomei'] = $arr['caomei'] - $arrt['caomei'];
		$sy['yingtao'] = $arr['yingtao'] - $arrt['yingtao'];
		$sy['daomi'] = $arr['daomi'] - $arrt['daomi'];
		$sy['fanqie'] = $arr['fanqie'] - $arrt['fanqie'];
		$sy['putao'] = $arr['putao'] - $arrt['putao'];
		$sy['boluo'] = $arr['boluo'] - $arrt['boluo'];
		$sy['zhongzi'] = $arr['zhongzi'] - $arrt['zhongzi'];
		$sy['zongshu'] = $arr['zongshu'] - $arrt['zongshu'];
		$this->assign('sy',$sy);
		
		
		$y = date('Y');
		$m = date('m');
		$d = date('d');
		$today = mktime(0,0,0,$m,$d,$y)-24*3600;
		$tomorrow = $today+20*3600;
		$tomo = $today+21*3600;
		$bilie = array();
		$bilie[0] = M('seeds_record')->where('time>"'.$tomorrow.'" AND time<"'.$tomo.'"')->find();
		$bilie[0]['type'] = '昨日';
		$bilie[1] = $arr;
		$bilie[1]['type'] = '今日';
		$bilie[2]['type'] = '比例';
		$bilie[2]['tudou'] = round((1-($bilie[0]['tudou']/$bilie[1]['tudou']))*100,2).'%';
		$bilie[2]['caomei'] = round((1-($bilie[0]['caomei']/$bilie[1]['caomei']))*100,2).'%';
		$bilie[2]['yingtao'] = round((1-($bilie[0]['yingtao']/$bilie[1]['yingtao']))*100,2).'%';
		$bilie[2]['daomi'] = round((1-($bilie[0]['daomi']/$bilie[1]['daomi']))*100,2).'%';
		$bilie[2]['fanqie'] = round((1-($bilie[0]['fanqie']/$bilie[1]['fanqie']))*100,2).'%';
		$bilie[2]['putao'] = round((1-($bilie[0]['putao']/$bilie[1]['putao']))*100,2).'%';
		$bilie[2]['boluo'] = round((1-($bilie[0]['boluo']/$bilie[1]['boluo']))*100,2).'%';
		$bilie[2]['zhongzi'] = round((1-($bilie[0]['zhongzi']/$bilie[1]['zhongzi']))*100,2).'%';
		$bilie[2]['zongshu'] = round((1-($bilie[0]['zongshu']/$bilie[1]['zongshu']))*100,2).'%';
		$this->assign('bilie',$bilie);
		$this->display();
	}
	
	public function bltj_567(){
		set_time_limit(0);
		$Admin = New Admin();
		
		$res[0] = $Admin->seed_level_gg(5);
		$res[0]['level'] = '5级';
		$res[1] = $Admin->seed_level_gg(6);
		$res[1]['level'] = '6级';
		$res[2] = $Admin->seed_level_gg(7);
		$res[2]['level'] = '7级';
		$count = count($res);
		$f = 0;
		$arr = array();
		for($i=0;$i<$count;$i++){
			$arr['tudou'] += $res[$i]['tudou'];
			$arr['caomei'] += $res[$i]['caomei'];
			$arr['yingtao'] += $res[$i]['yingtao'];
			$arr['daomi'] += $res[$i]['daomi'];
			$arr['fanqie'] += $res[$i]['fanqie'];
			$arr['putao'] += $res[$i]['putao'];
			$arr['boluo'] += $res[$i]['boluo'];
			$arr['zhongzi'] += $res[$i]['zhongzi'];
			$arr['zongshu'] += $res[$i]['zongshu'];
            $f++;		
            }
		//print_r($res);
		$this->assign('arr',$arr);
		$this->assign('seeds',$res);
		

		$this->display();
	}
}