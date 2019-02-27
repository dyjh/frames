<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26
 * Time: 16:32
 */

namespace Admin\Controller;


use Think\Controller;

class ChestController extends AdminController
{
    public function index(){
        $data=M('treasure_chest')->select();
        foreach ($data as $k=>$v){
            $data[$k]['chance']=$v['chance']*100;
        }
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        //$data_t=M('record_treasure')->order('time')->select();
//print_r($state);die;
        //$this->assign('data_t',$data_t);
        $open=array();
        $k=0;
        $data_s=M('Seeds')->field('varieties')->select();
        $count_s=M('Seeds')->count();
        for($i=0;$i<$count_s;$i++){
            $seed=$data_s[$i]['varieties'];
            $d=M('record_treasure')->where('type ="k" AND open_seed="'.$seed.'" AND time =0')->select();
            if(empty($d)){

            }else{
                $num=M('record_treasure')->where('type ="k" AND open_seed="'.$seed.'" AND time =0')->sum('get_seed_num');
                $data_o['num']=$num;
                $data_o['seed']=$seed;
                $open[$k]=$data_o;
                $k++;
            }
            //print_r($d);
        }
//        die;
        //print_r($open);die;
        $buy=array();
        $b=0;
        for($i=0;$i<$count_s;$i++){
            $seed=$data_s[$i]['varieties'];
            $d=M('record_treasure')->where('type ="b" AND get_seed="'.$seed.'" AND time =0')->select();
            if(empty($d)){

            }else{
                $num=M('record_treasure')->where('type ="b" AND get_seed="'.$seed.'" AND time =0')->sum('open_seed_num');
                $data_b['num']=$num;
                $data_b['seed']=$seed;
                $buy[$b]=$data_o;
                $b++;
            }
        }
        if(empty($buy)){
            $state_b=0;
        }else{
            $state_b=1;
        }
        if(empty($open)){
            $state_o=0;
        }else{
            $state_o=1;
        }
        $this->assign('state_b',$state_b);
        $this->assign('state_o',$state_o);
        $this->assign('buy',$buy);
        $this->assign('open',$open);
        $this->assign('state',$state);
        $this->assign('data',$data);
        $this->display();
    }
	
	// 2017年9月9日15:45:18
	// QHP 重写宝箱记录	
	// 下面有原方法
	public function record(){
		// 初始查询语句
		$where  = "  1=1 ";
		
		$start_time    =  empty($_GET['start_time'])  ? strtotime( date("Y-m-d") )  : strtotime($_GET['start_time']);
		$end_time  	   =  empty($_GET['end_time'])    ? ($start_time + 3600 * 24)  : strtotime($_GET['end_time']);
		
		$_GET['start_time'] =  date("Y-m-d",$start_time);
		$_GET['end_time']   =  date("Y-m-d",$end_time);
		
		// 拼接where
		$where    .= " and `time` between {$start_time} and {$end_time}" ;	
		
		$num =10;			
					
		$all_tables  =  M("statistical")->order("name asc")->select();
		
		$first_table = $all_tables[0]['name']."_winning_record";
		
		$first_field = $all_tables[0]['name']."_winning_record.* , ".$all_tables[0]['name']."_members.name as real_name  ";
		// $first_field = "*";
		
		$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$first_table. ".user ";
		
		// 增加查询果实
		if(isset($_GET['seeds_cate']) && !empty($_GET['seeds_cate']) ){
            $where  .= " and seed = '" . addslashes($_GET['seeds_cate']) . "'";
        }
				
		$first_where = $where;
		
		// 查询电话
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
            $user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
			$first_where	.=  sprintf($user_where,$first_table) ;
        }
	
		unset($all_tables[0]);					
		
		// 拼接 sql；
		foreach($all_tables as $val){
			
			$field   =  $val['name']."_winning_record.* , ".$val['name']."_members.`name`  as real_name  ";
			
			$table   =  $val['name']."_winning_record";
			$join    =  " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$table. ".user ";
			
			$sql = "select " . $field . " from " . $table . $join ." where  ".$where  . sprintf($user_where,$table) . " ";		

			$union[] = $sql;			
		}	
		
		
		$all_list =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->select();
		
		foreach($all_list as $val){
			
			$total[$val['name']][$val['seed']] += $val['num'];
			$seed_total[$val['seed']] += $val['num'];
			
		}
		
		$record_treasure_where['type']="k";
		$record_treasure_where['time']="1";
		$record_treasure_data = M("record_treasure")->where($record_treasure_where)->select();
		
		foreach($record_treasure_data as $val){
		
			$seed_open_total[$val['open_seed']] += $val['open_seed_num'];
			
		}
		
		// echo M($first_table)->getLastSql();
		array_multisort(i_array_column($all_list,'time'),SORT_DESC,$all_list);
		
		// 获得分页						
		$page = intval(I("get.p",1,'addslashes'));	
		
		$result = page_array($num,$page,$all_list); 
		// $data   =  $result['array']; 
		$legal_parameter = array("start_user","seeds","start_time","end_time");
		foreach($_GET as $key=>$val){
            if(in_array($key,$legal_parameter) && !empty($val)){
                $get_url[$key] .= $key."=".$val;
            }
        }

        $get_url_str .= implode("&",$get_url);
		// 获取果实种类
		$seeds_where['varieties'] = array("not in",array("摇钱树","种子"));
		$SeedsList  = M("seeds")->where($seeds_where)->select();

		// echo M("seeds")->getLastSql();
		$this->assign('total'	,$total);
		
		$this->assign('seed_open_total'	,$seed_open_total);
		
		$this->assign('seed_total'	,$seed_total);
		
		$this->assign('SeedsList'	,$SeedsList);
		
		$this->assign('get_url_str'	,$get_url_str);
		
		$this->assign('start_time'	,$start_time);
		
		$this->assign('end_time'	,$end_time);
		
        $this->assign('get_data',$_GET);		
		
        $this->assign('result',$result);
		
        $this->assign('start',$result['start']);
		
        $this->assign('now_oage',$result['now_oage']);
		
        $this->assign('end',$result['end']+1);
		
        $this->display();
	}
   
	public function old_record(){
		if(IS_POST){
			$user=I('post.user');
		}else{
			if(IS_GET){
				$user=I('get.user');
			}else{
				$user='';
			}
		}
		$data=array();
		$f=0;
		$tell=M('statistical')->select();
		foreach($tell as $key=>$val){
			$case=''.$val['name'].'_winning_record';
			$date['_string']='user like "%'.$user.'%"';
			$member=M($case)->where($date)->select();
			$c=count($member);
			for($i=0;$i<$c;$i++){
				$data[$f]=$member[$i];
				$f++;
			}
		}
		$count=count($data);//得到数组元素个数
		array_multisort(i_array_column($data,'time'),SORT_DESC,$data);
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
		$this->assign('user',$user);
        $res =array_slice($data,($o-1)*8,8);
        if(empty($res)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $this->assign('data',$res);//分页内容
		$this->display();
		
	}
      
	public function edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Chest/index');
                return;
            }
            $id = intval(I("post.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }
            $chest=M('treasure_chest');
            $data['seed']=_safe(I('post.seed'));
            $data['gift']=_safe(I('post.gift'));
            $data['seed_num']=I('post.seed_num','','strip_tags');
            $data['gift_num']=I('post.gift_num','','strip_tags');
            $data['name']=I('post.name','','');
            $data['number']=I('post.number','','strip_tags');
            $data['chance']=I('post.chance','','strip_tags')/100;
            $data['multiple']=I('post.multiple','','strip_tags');
            $data['max_mul']=I('post.max_mul','','strip_tags');
            $chest->seed=$data['seed'];
            $chest->gift=$data['gift'];
            $chest->seed_num=$data['seed_num'];
            $chest->gift_num=$data['gift_num'];
            $chest->name=$data['name'];
            $chest->number=$data['number'];
            $chest->chance=$data['chance'];
            $chest->multiple=$data['multiple'];
            $chest->max_mul=$data['max_mul'];
            if($chest->where('id =%d',array($id))->save() !==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('添加成功');</script>";
                echo "<script> window.location.href='".U('Chest/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('添加失败');</script>";
                echo "<script> window.location.href='".U('Chest/index')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $where['varieties']=array('not in','摇钱树');
            $data_seed=M('Seeds')->where($where)->select();
            $this->assign('data_seed',$data_seed);
            $id = intval(I("get.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }
            $data=M('treasure_chest')->where('id= %d',array($id))->find();
            $data['chance']=$data['chance']*100;
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Chest/index');
                return;
            }
            $chest=M('treasure_chest');
            $data['seed']=_safe(I('post.seed'));
            $data['gift']=_safe(I('post.gift'));
            $data['seed_num']=I('post.seed_num','','strip_tags');
            $data['gift_num']=I('post.gift_num','','strip_tags');
            $data['name']=I('post.name','','');
            $data['number']=I('post.number','','strip_tags');
            $data['chance']=I('post.chance','','strip_tags')/100;
            $data['multiple']=I('post.multiple','','strip_tags');
            $data['max_mul']=I('post.max_mul','','strip_tags');
            $chest->seed=$data['seed'];
            $chest->gift=$data['gift'];
            $chest->seed_num=$data['seed_num'];
            $chest->gift_num=$data['gift_num'];
            $chest->name=$data['name'];
            $chest->number=$data['number'];
            $chest->chance=$data['chance'];
            $chest->multiple=$data['multiple'];
            $chest->max_mul=$data['max_mul'];
            if($chest->add()){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('添加成功');</script>";
                echo "<script> window.location.href='".U('Chest/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('添加失败');</script>";
                echo "<script> window.location.href='".U('Chest/index')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $where['varieties']=array('not in','摇钱树');
            $data_seed=M('Seeds')->where($where)->select();
            $this->assign('data',$data_seed);
            $this->display();
        }
    }

    public function del(){
        if(IS_AJAX){
            $id = intval(I("post.id",0,'addslashes'));
            if($id==0){
                echo -1;
            }else{
                $chest=M('treasure_chest');
                if($chest->where('id= %d',array($id))->delete()){
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