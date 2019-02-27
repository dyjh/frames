<?php
/**
 * Created by PhpStorm.
 * User: QHP
 * Date: 2017/7/5 0005
 * Time: 16:46
 */

namespace Admin\Controller;
use Org\Our\Admin;
use Think\Model;

class ProtectController extends AdminController{

	public $ProtectNum   =  5; //  当前守护数量
	
	
	protected function contrust_counting(){
		
			$counting = array(
				"1"		=>array(1=>0,0=>0),
				"2"		=>array(1=>0,0=>0),
				"3"		=>array(1=>0,0=>0),
				"4"		=>array(1=>0,0=>0),
				"5"		=>array(1=>0,0=>0),
				'all'	=>array(1=>0,0=>0),
			);
			
			return $counting;
	}
		
    public function index(){
		
		$counting = $this->contrust_counting();
			
		$where  = "  1=1 ";
		
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
			$table_where = " name='".substr($_GET['start_user'],0,3)."'";
        }
		            
		$all_tables  =  M("statistical")->order("name asc")->where($table_where)->select();
		
		$first_table = $all_tables[0]['name']."_managed_to_record";		

		// $first_field = $first_table.".* , ".$all_tables[0]['name']."_members.name  ";
		
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
            $user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
			$where	.=  sprintf($user_where,$first_table) ;
        }
		
		$first_where = $where;
		
		// $first_join  = " inner join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$first_table. ".user ";
		
		unset($all_tables[0]);					
		
		// 拼接 sql；
		foreach($all_tables as $val){
			
			$table   =  $val['name']."_managed_to_record";
			
			// $field   =  $table.".* , ".$val['name']."_members.name  ";
			$field = "*";

			// $join    =  " inner join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$table. ".user ";
			
			$sql = "select " . $field . " from " . $table . $join ." where  ".$where . sprintf($user_where,$table) ." ";
			
			$union[] = $sql;	
			
		}	
				
		$all_list =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->select();
		
		foreach($all_list as $val){
			
			$counting[$val['service_type']][$val['state']]++;
			
			$user_list[$val['user']][$val['service_type']]  = $val['state'];
			
		}

		foreach($user_list as $key=>$val){
			
			if(count($val) >= $this->ProtectNum){ 
				// echo $key."-----";
				// print_r($val);
				if( (count($val)-count(array_filter($val))) ==  $this->ProtectNum ) {
					$counting['all'][0]++;
				}
				else {
					$counting['all'][1]++;
				}
			}
			
		}
		
		// print_r(M($first_table)->getLastSql());
		// print_r($counting);
		
		$this->assign('counting',$counting);
		
		$this->display();

    }

}














// that  is  all

//  BY  QHP   2017年8月21日16:19:27
