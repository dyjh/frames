<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19
 * Time: 18:08
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Tool;

class StatisticsController extends AdminController
{
    public function index(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Chest/index');
                return;
            }
            $user=I('post.user',0,'addslashes');
            if(!preg_match("/^1[34578]\d{9}$/", $user)){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }
            $table=new Tool();
            $case='member_record';
            $tel=$user;
            //print_r($tel);die;
            $case_m=$table->table($tel,$case);
            $data=M(''.$case_m.'')->where('user='.$user)->find();
            $data_seed=M('Seeds')->select();

            foreach ($data_seed as $k=>$v){
                $seed=$v['varieties'];
                $name[$k]=$seed;
                $case='seed_warehouse';
                $tel=$user;
                $cases=$table->table($tel,$case);
                $where['user']    =    ':user';
                $where['seeds']  =    ':seeds';
                $bind[':user']    =    array($user,\PDO::PARAM_STR);
                $bind[':seeds']  =    array($seed,\PDO::PARAM_STR);
                $num=M(''.$cases.'')->where($where)->bind($bind)->sum('num');
                if(empty($num)){
                    $seed_num[$k]=0;
                }else{
                    $seed_num[$k]=$num;
                }
            }
            $data['state']=1;
        }else{
            $data['state']=0;
        }

        /***分时间段查询充值金额***/
        if($_GET['h']!==null){
            $start_time =I('get.h',0,'addslashes');
            if(!preg_match('/^\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}$/', $start_time)){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }
        }else{
            $start_time =0;
        }
        if($_GET['e']!==null){
            $end_time =I('get.e',0,'addslashes');
            if(!preg_match('/^\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}$/', $end_time)){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }
        }else{
            $end_time =0;
        }
        if($end_time==0&&$start_time==0){
            $data_tel=M('statistical')->select();
            $var=M('verification')->count();
            foreach ($data_tel as $k=>$v){
                $cases=''.$v['name'].'_order';
                $data_top=M(''.$cases.'')->where('pay_cash=1 AND state =1')->select();
                $data_deposit=M(''.$cases.'')->where('pay_cash=2 AND state =1')->select();
                if(empty($data_top)){
                    $data_record['top']+=0;
                }else{
                    $data_record['top']+=M(''.$cases.'')->where('pay_cash=1 AND state =1')->sum('money');      //充值
                }
                if(empty($data_deposit)){
                    $data_record['deposit']+=0;
                }else{
                    $data_record['deposit']+=M(''.$cases.'')->where('pay_cash=2 AND state =1')->sum('money');                    //提现
                }
            }
        }else{
            $s_y=substr($start_time,0,4);
            $s_m=substr($start_time,5,2);
            $s_d=substr($start_time,8,2);
            $e_y=substr($end_time,0,4);
            $e_m=substr($end_time,5,2);
            $e_d=substr($end_time,8,2);
            $start= mktime(0,0,0,$s_m,$s_d,$s_y);
            $end= mktime(0,0,0,$e_m,$e_d,$e_y);
            $end=$end+24*3600;
            $data_tel=M('statistical')->select();
            $var=M('verification')->where('regis_time>="'.$start.'" AND regis_time<="'.$end.'"')->count();
            foreach ($data_tel as $k=>$v){
                $cases=''.$v['name'].'_order';
                $data_top=M(''.$cases.'')->where('pay_cash=1 AND state =1 AND pay_time>="'.$start.'" AND pay_time<="'.$end.'"')->select();
                $data_deposit=M(''.$cases.'')->where('pay_cash=2 AND state =1 AND pay_time>="'.$start.'" AND pay_time<="'.$end.'"')->select();
                if(empty($data_top)){
                    $data_record['top']+=0;
                }else{
                    $data_record['top']+=M(''.$cases.'')->where('pay_cash=1 AND state =1 AND pay_time>="'.$start.'" AND pay_time<="'.$end.'"')->sum('money');      //充值
                }
                if(empty($data_deposit)){
                    $data_record['deposit']+=0;
                }else{
                    $data_record['deposit']+=M(''.$cases.'')->where('pay_cash=2 AND state =1 AND pay_time>="'.$start.'" AND pay_time<="'.$end.'"')->sum('money');
                }
            }
        }
        /**查询结束**/
        $data_total=M('total_station')->where('id=1')->find();
        creatToken();
        $data_seed=M('Seeds')->select();
        //print_r($seed_num);
        //print_r($name);
		$seeds_array = array();
		
		/**种子发行总量**/
		$seeds_array['zonlian']='3亿';
		/**z钻石总量**/
		$money=0;
        $sta=M('statistical')->select();
        foreach($sta as $key=>$val){
            $case=''.$val['name'].'_members';
            $money+=M($case)->sum('diamond');
        }
        //print_r($money);die;
		/**种子商店售出总量**/
		$shop=M('shop')->where('id=6')->find();
		$seeds_array['shop_num']=(($shop['frequency']*3000)-$shop['num'])*1000;
		$seeds_array['shop_out']=$shop['num']*1000;
		
		/**礼包种子送出总量**/
		$gift=M('total_station')->where('id=1')->find();
		$seeds_array['gift_num']=$gift['member_num']*2017;
		
		/**种子当前发行总量**/
		$seeds_array['total_num']=$seeds_array['shop_num']+$seeds_array['gift_num'];
		
		/**种子当前剩余数量**/
		$seeds_array['surplus']=300000000-$seeds_array['total_num'];
		$this->assign('var',$var);
	    $this->assign('money',$money);
		$this->assign('seeds_array',$seeds_array);
        $this->assign('data_record',$data_record);
        $this->assign('data_seed',$data_seed);
        $this->assign('data_total',$data_total);
        $this->assign('name',$name);
        $this->assign('seed_num',$seed_num);
        $this->assign('data',$data);
        $this->display();
    }

    public function diamond(){
        //print_r($_GET);

            $data_member=array();
            $s=0;
            $statistical=M('statistical')->select();
            foreach ($statistical as $k=>$v){
                $case=''.$v['name'].'_members';
                $member=M($case)->field('num_id,user')->select();
                for($i=0;$i<count($member);$i++){
                    $data_member[$s]=$member[$i];
                    $s++;
                }
            }

           // print_r($data_member);die;
            if($_GET['h']!==null){
                $start_time =I('get.h',0,'addslashes');
                $start_s=substr($start_time,0,10);
                $s_y=substr($start_time,0,4);
                $s_m=substr($start_time,5,2);
                $s_d=substr($start_time,8,2);
                $s_h=substr($start_time,11,2);
                $h=$start_time;
                $start= mktime($s_h,0,0,$s_m,$s_d,$s_y);
//                print_r($start_time);echo '<br/>';
//                echo $start;echo '<br/>';die;
            }else{
                $start_s='';
                $s_h='';
                $h='';
                $start_time =0;
            }
            if($_GET['e']!==null){
                $end_time =I('get.e',0,'addslashes');
                $end_s=substr($end_time,0,10);
                $e_y=substr($end_time,0,4);
                $e_m=substr($end_time,5,2);
                $e_d=substr($end_time,8,2);
                $e_h=substr($end_time,11,2);
                $e=$end_time;
                $end= mktime($e_h,0,0,$e_m,$e_d,$e_y);
            }else{
                $end_s='';
                $e_h='';
                $e='';
                $end_time =0;
            }
//        print_r($start_time);echo '<br/>';
//            print_r($end_time);echo '<br/>';
//        echo $start;echo '<br/>';
//        echo $end ;die;

//            print_r($start_time);echo '<br/>';
//            print_r($end_time);die;
            for($p=0;$p<count($data_member);$p++){
                if($end_time==0&&$start_time==0){
                    //echo 1;die;
                    $num=substr($data_member[$p]['user'],0,3);
                    $cases=''.$num.'_record_conversion';
                    $data_diamond=M($cases)->where('num=0 AND user='.$data_member[$p]['user'])->sum('diamond');
//                    echo $data_diamond;echo "\n";
                    if((int)$data_diamond) {
                        $list               = $data_member[$p];
                        $list['diamond']  = $data_diamond;
                        $data_member_list[]  = $list;
//                        $data_member_list[$p]['diamond']=$data_diamond;
                    }
                }else{
//                     echo $start;echo '<br/>';
//                     echo $end ;die;
                    $num=substr($data_member[$p]['user'],0,3);
                    $cases=''.$num.'_record_conversion';
                    $data_diamond=M(''.$cases.'')->where('type="d" AND user='.$data_member[$p]['user'].' AND buy_time>="'.$start.'" AND buy_time<="'.$end.'"')->sum('diamond');
                    if((int)$data_diamond){
                        $list               = $data_member[$p];
                        $list['diamond']  = $data_diamond;
                        $data_member_list[]  = $list;
                    }
                }
            }

            array_multisort(i_array_column($data_member_list,'diamond'),SORT_DESC,$data_member_list);

            $count=count($data_member_list);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            $res =array_slice($data_member_list,($p-1)*8,8);

            if(empty($res)){
                $state=0;
            }else{
                $state=1;
            }
            $this->assign('h',$h);
            $this->assign('e',$e);
            $this->assign('start_s',$start_s);
            $this->assign('end_s',$end_s);
            $this->assign('s_h',$s_h);
            $this->assign('e_h',$e_h);
            $this->assign('state',$state);
            $this->assign('data',$res);
            //print_r($res);

            $this->display();

    }

}