<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27 0027
 * Time: 下午 2:37
 */

namespace Admin\Controller;
use Think\Tool;

class BackController extends AdminController
{
    public function index(){
		if(IS_POST){          
			//echo 1;
            $level =intval(I('post.level',1,'addslashes'));     //查询等级条件
			$users=I('post.user');                              //按用户查询条件
        }else{
			//echo 2;
            $level =intval(I('get.level',1,'addslashes'));      //get传值
        }
        $p=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){          //循环表头
            $cases=''.$v['name'].'_members';
			if(empty($users)){
				
				$data_m=M($cases)->where('cost_state = 1 AND level ='.$level)->order('level')->select();
				$count=M($cases)->where('cost_state = 1 AND level ='.$level)->order('level')->count();
			}else{
				
				$date['_string']=' (user like "%'.$users.'%")  OR ( num_id like "%'.$users.'") OR ( name like "%'.$users.'")';
				$data_m=M($cases)->where($date)->order('level')->select();
				//print_r($data_m);die;
				$count=M($cases)->where($date)->order('level')->count();
			}
			//$data_m=M($cases)->where('level > 4 AND cost_state = 1')->order('level')->select();
            //$count=M($cases)->where('level > 4 AND cost_state = 1')->count();
            for($i=0;$i<$count;$i++){      //存放数组
				$input[$p]=$data_m[$i];
				$p++;
            }
        }
        $columnKey='level';
        array_multisort(i_array_column($input,$columnKey),SORT_ASC,$input);   //对最终数组排序
        
		foreach($input as $key=>$val){
			$num=substr($val['user'],0,3);
			$ca=''.$num.'_member_record';
			$tp=M($ca)->where('user="'.$val['user'].'"')->find();
			$dd[$val['user']] = $tp;
		}
		
		$this->assign('dd',$dd); //分页
		
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
        $this->assign('start_p',$start_p); //分页
        $this->assign('end_p',$end_p+1); //分页
        $this->assign('count',$count);
		$this->assign('level',$level);
        $this->assign('p',$p);
        $list =M('backmoney_user')->page($p.','.$num_p)->select();
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
			
			//print_r($_POST);
			//die;
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
					//print_r($data_m);
					//die;
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
				$level=I('get.level');
				//echo $level;die;
                $data_s=M('statistical')->select();
                foreach ($data_s as $k=>$v){
                    $cases=''.$v['name'].'_members';
                    $where['level'] = ':level';
                    $where['cost_state'] = ':cost_state';
                    $bion[':level'] = array('lt','4',\PDO::PARAM_INT);
                    $bion[':cost_state'] = array('1',\PDO::PARAM_INT);
                    $data_m=M($cases)->where('level='.$level.' AND cost_state=1')->field('user')->order('level')->filter('strip_tags')->select();
                    $count=M($cases)->where('level='.$level.' AND cost_state=1')->count();
                    for($i=0;$i<$count;$i++){
                        $user=$data_m[$i]['user'];
                        //$back['user'] = ':user';
                        $data_back=M('backmoney_user')->where('user="'.$user.'"')->filter('strip_tags')->find();
                        if(empty($data_back)){
                            $input['user']=$data_m[$i]['user'];
                            $input['allow_seed']=$allow_seed;
                            $input['ban_cycle']=$ban_cycle;
                            M('backmoney_user')->add($input);
                        }else{
							$save['allow_seed']=$allow_seed;
                            $save['ban_cycle']=$ban_cycle;
							M('backmoney_user')->where('user="'.$data_m[$i]['user'].'"')->save($save);
						}
                    }
                }
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("设置完毕！"),history.back(); </script>';
				exit();
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
	public function level(){
		//print_r($_POST);die;
		
        if(IS_POST){
			//echo 1;
            $level =intval(I('post.level',1,'addslashes'));
			$users=I('post.user');
        }else{
			//echo 2;
            $level =intval(I('get.level',1,'addslashes'));
        }
		
		
		//print_r($level);
        $f=0;
        $input=array();
        $data_s=M('statistical')->select();
		//print_r($data_s);die;
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
            //$where['level'] = ':level';
            //$bion[':level'] = array('eq',$level,\PDO::PARAM_INT);
            //$data_m=M($cases)->where($where)->bind($bion)->order('level')->select();
			
			
			if(empty($users)){
				
				$data_m=M($cases)->where('level ='.$level)->order('level')->select();
				$count=M($cases)->where('level ='.$level)->order('level')->count();
			}else{
				
				$date['_string']=' (user like "%'.$users.'%")  OR ( num_id like "%'.$users.'") OR ( name like "%'.$users.'")';
				$data_m=M($cases)->where($date)->order('level')->select();
				//print_r($data_m);die;
				$count=M($cases)->where($date)->order('level')->count();
			}
			
			//print_r($data_m);
            //$count=M($cases)->where($where)->bind($bion)->distinct(true)->count();
			
			//print_r($count);die;
			//print_r($count);die;
            for($i=0;$i<$count;$i++){
                $user=$data_m[$i]['user'];
                //$whe['user'] = ':user';
                //$data_back=M('backmoney_user')->where($whe)->bind(':user',$user,\PDO::PARAM_STR)->find();
                //$data_back=M('backmoney_user')->where('user="'.$user.'"')->find();
                //if(empty($data_back)){
                    $input[$f]=$data_m[$i];
                    $f++;
                //}
				
            }
        }
		//print_r($input);
        $columnKey='level';
        array_multisort(i_array_column($input,$columnKey),SORT_ASC,$input);   //数组排序
        
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
			if($day=='s'){
				$data_p=1;
			}
			//echo $ban_cycle;echo $day;echo $data_p;die;
            if($data_p!==false){
				$first=substr($ban_cycle,0,1);
				if($data_p=='s'){
					$ban_cycle=$ban_cycle;
				}else{
					if($first==$day){
						$a=''.$first.',';
						$ban_cycle=str_replace($a,"",$ban_cycle);
					}else{
						$a=','.$day.'';
						$ban_cycle=str_replace($a,"",$ban_cycle);
					}
				}
				//echo $ban_cycle;die;
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
            }else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("该日期不存在，请重新选日期！"),history.back(); </script>';
                exit();
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
	public function level_del(){
			$level=I('get.level');
			//echo $level;die;
			$data_s=M('statistical')->select();
			foreach ($data_s as $k=>$v){
				$cases=''.$v['name'].'_members';
				$data_m=M($cases)->where('level='.$level.' AND cost_state=1')->field('user')->order('level')->filter('strip_tags')->select();
				$count=M($cases)->where('level='.$level.' AND cost_state=1')->count();
				for($i=0;$i<$count;$i++){
					$user=$data_m[$i]['user'];
					//$back['user'] = ':user';
					$data_back=M('backmoney_user')->where('user="'.$user.'"')->filter('strip_tags')->delete();
				}
			}
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("删除完毕！"),history.back(); </script>';
			exit();
	}
	public function del(){
		if(IS_AJAX){
            $id = intval(I("post.id",0,'addslashes'));
			//print_r($id);die;
            if($id==0){
                echo 0;
            }else{
				
                $infor=M('backmoney_user');
                if($infor->where('id ='.$id)->filter('strip_tags')->delete()){
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }else{
            echo -1;
        }
	}
}