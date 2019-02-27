<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/22 0022
 * Time: 下午 4:45
 */

namespace Admin\Controller;
use Think\Controller;
class ChickenController extends Controller
{
    protected $cache = "0";//   缓存时间

    protected $table = "chicken_conf";

    public function conf(){
        //  查询所有系统参数
        $all_conf = M($this->table)->field()->select();

        $this->assign("all_conf",$all_conf);

        $this->display();
    }

    public function update_ajax($material_id,$object,$type="update"){
        // if(IS_AJAX){


        $object = I("post.object") ;

        $updata_arr = array(
            //"cases"    =>   $object['cases'],
            "value"    =>   $object['value'],
            //"note"     =>   $object['note'],
        );
        $dd=M($this->table);
        $dd->value=$updata_arr['value'];
        $res = $dd->where("id=%d",array($material_id))->save();

        if($res == 1){
            if($type=="update"){
                $data['status']  = 0;
                $data['content'] = 'OK';
            }elseif($type=="insert"){
                $data['status']  = -1;
                $data['content'] = 'OK';
            }

        }elseif($res == 0){
            $data['status']  = 0;
            $data['content'] = 'global_conf not update';
        }else{
            //            $this->ajaxReturn(M($this->table)->getLastSql(),'json');
            $data['status']  = 40033;
            $data['content'] = 'update global_conf error';
        }

        $this->ajaxReturn($data,'json');
        //     }
    }
    //果实主页
    public function index(){
        $case_shop='chickens';
        $data=M($case_shop)->select();
        //print_r($data_s);die;

        $this->assign('data',$data);

        $this->display();
    }

    public function chicken_edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/index');
                return;
            }
            $chicken=M('chickens');
            $chicken->name = I('post.name','','addslashes');
            $chicken->price = I('post.price','','addslashes');
            $chicken->cycle = I('post.cycle','','addslashes')*3600;
            $chicken->earnings = I('post.earnings','','addslashes')*3600;
            $chicken->conversion = I('post.conversion','','addslashes')*3600;
            $chicken->num = I('post.num','','addslashes')*3600;
            //echo 1;die;
            $where['id'] = ':id';
            $bind[':id'] = array(I('post.id'),\PDO::PARAM_INT);
            if($chicken->where($where)->bind($bind)->filter('strip_tags')->save() !==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改成功！"); </script>';
                echo "<script> window.location.href='".U('Chicken/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }
        }else{
            creatToken();
            //$id=I('get.id'.''.'');
            $chicken=M('chickens');
            $where['id'] = ':id';
            $bind[':id'] = array(I('get.id'),\PDO::PARAM_INT);
            $data=$chicken->where($where)->bind($bind)->filter('strip_tags')->find();
            //print_r($data);die;
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function house_record(){
        if(IS_POST){
            $user=I('post.user');
        }else{
            $user=I('get.user');
        }
        if(empty($user)){
            $sql='';
        }else{
            $sql='user='.$user.'';
        }
        $count= M('chicken_house')->where($sql)->count();   //得到总的条数
        //print_r($num);
        $per_num = 9;  //每页显示的条数
        $pages = ceil($count/$per_num);//总页数
        $this->assign('pages',$pages+1);
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
        $data = M('chicken_house')->where($sql)-> page($o.','.$per_num)->select();
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $this->assign('data',$data);
        $this->display();
    }

	public function chickens(){
		$chickens_data=M('chickens')->select();
		//print_r($chickens_data);echo 2;
		$data=array();
		$all=array();
		//echo 1;
		$data_mem=M('chicken_house')->select();
		$find=I('post.user');
		if(!empty($find)){
			
			//$data['user']=$find;
			$case=''.substr($find,0,3).'_chicken_record';
			foreach($chickens_data as $key=>$val){
				$count=M($case)->where('user='.$find.' AND chicken_type="'.$val['name'].'"')->count();
				$data[''.$val['id'].'']=$count;
			}
			foreach($data_mem as $k=>$v){
				
				$case=''.substr($v['user'],0,3).'_chicken_record';
				foreach($chickens_data as $key=>$val){
					$count=M($case)->where('user='.$v['user'].' AND chicken_type="'.$val['name'].'"')->count();
					
					$all[''.$val['id'].'']+=$count;
				}
				
			}
			$statu=1;
		}else{
			$statu=0;
			foreach($data_mem as $k=>$v){
				$data[$k]['user']=$v['user'];
				$case=''.substr($v['user'],0,3).'_chicken_record';
				foreach($chickens_data as $key=>$val){
					$count=M($case)->where('user='.$v['user'].' AND chicken_type="'.$val['name'].'"')->count();
					$data[$k][''.$val['id'].'']=$count;
					$all[''.$val['id'].'']+=$count;
				}
				
			}
		}
		//print_r($data);
				
		$this->assign('statu',$statu);
		$this->assign('chicken',$chickens_data);
		$this->assign('all',$all);
		if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
		$this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);

        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o',1,'int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $off=floor($showPage/2);
        $start_page=$o-$off;
        $end_page=$o+$off;
		
        //起始页
        if($o-$off < 1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
		
        $this->assign('start',$start_page); //分页
        $this->assign('end',$end_page+1); //分页
	
        $res =array_slice($data,($o-1)*8,8);
		//print_r($data);
        $this->assign('data',$res);//分页内容
		
		//var_dump($res);
		
		$this->display();
		//print_r($all);die;
		//print_r($data);
	}
	
    public function commission(){
        if(IS_POST){
            $user=I('post.user');
            $start_time=I('post.start_time');
            $end_time=I('post.end_time');
        }else{
            $user=I('get.user');
            $start_time=I('get.start_time');
            $end_time=I('get.end_time');
        }
        if(!empty($start_time)){
            $s_y=substr($start_time,0,4);
            $s_m=substr($start_time,5,2);
            $s_d=substr($start_time,8,2);
            $s_h=substr($start_time,11,2);
            $s_i=substr($start_time,14,2);
            $s_s=substr($start_time,17,2);
            $start_time = mktime($s_h,$s_i,$s_s,$s_m,$s_d,$s_y);
        }
        if(!empty($end_time)){
            $e_y=substr($end_time,0,4);
            $e_m=substr($end_time,5,2);
            $e_d=substr($end_time,8,2);
            $e_h=substr($end_time,11,2);
            $e_i=substr($end_time,14,2);
            $e_s=substr($end_time,17,2);
            $end_time = mktime($e_h,$e_i,$e_s,$e_m,$e_d,$e_y);
        }
        if(empty($user)&&empty($start_time)&&empty($end_time)){
            $sql="";
        }
        if(!empty($user)&&empty($start_time)&&empty($end_time)){
            $sql='user='.$user.'';
        }
        if(empty($user)&&!empty($start_time)&&!empty($end_time)){
            $sql='time<='.$end_time.' AND time>='.$start_time.'';
        }
        if(empty($user)&&!empty($start_time)&&empty($end_time)){
            $sql='time>='.$start_time.'';
        }
        if(empty($user)&&empty($start_time)&&!empty($end_time)){
            $sql='time<='.$end_time.'';
        }
        if(!empty($user)&&!empty($start_time)&&!empty($end_time)){
            $sql='time<='.$end_time.' AND time>='.$start_time.' AND user='.$user.'';
        }
        if(!empty($user)&&!empty($start_time)&&empty($end_time)){
            $sql='time>='.$start_time.' AND user='.$user.'';
        }
        if(!empty($user)&&empty($start_time)&&!empty($end_time)){
            $sql='time<='.$end_time.' AND user='.$user.'';
        }
        $case=''.date('Y-m').'_chicken_com_record';
        $count= M($case)->where($sql)->count();   //得到总的条数
        //print_r($num);
        $per_num = 9;  //每页显示的条数
        $pages = ceil($count/$per_num);//总页数
        $this->assign('pages',$pages+1);
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
        $data = M($case)->where($sql)-> page($o.','.$per_num)->select();
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('user',$user);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        $this->assign('state',$state);
        $this->assign('data',$data);
        $this->display();
    }
}