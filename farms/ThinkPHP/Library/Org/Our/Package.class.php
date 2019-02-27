<?php
namespace Org\Our;
use Org\Our\Tool;
use Think\Model;

class Package{
	
    public function get($user){
		
		if(is_numeric($user) && strlen($user)==11){
			
			$table_fix = substr($user,0,3);
			$table = $table_fix."_members";
			$gift = M("$table")->field('gift_state')->where(array('user'=>$user))->find();		
			if($gift['gift_state']==1){
				echo 4;
				exit;
			}else{
				$model=new Model();
				$model->startTrans();
				$table=new Tool();
				$case='prop_warehouse';
				$tel=$user;
				$case_p=$table->table($tel,$case);
				$data_0=M('Package')->where('type=0')->select();
				
				foreach ($data_0 as $k=>$v){
					$data_prop=M(''.$case_p.'')->where('props="'.$v['name'].'" AND user='.$user)->find();
					if(empty($data_prop)){
						$data['prop_id']=$v['prop_id'];
						$data['props']=$v['name'];
						$data['num']=$v['num'];
						$data_b=M('butler_service')->where('type="'.$v['name'].'"')->find();
						$data['user']=$user;
					
						if(M(''.$case_p.'')->add($data)){

						}else{
							$model->rollback();
							echo 3;
						}
					}else{
						if(M(''.$case_p.'')->where('props="'.$v['name'].'" AND user='.$user)->setInc('num',$v['num'])){

						}else{
							$model->rollback();
							echo 4;
						}
					}
				}
				$data_1=M('Package')->where('type=1')->find();
				$table=new Tool();
				$case='members';
				$tel=$user;
				$case_m=$table->table($tel,$case);
				
				if(M(''.$case_m.'')->where('user='.$user)->setInc('diamond',$data_1['num'])){
					$data_m['gift_state']=1;
					if(M(''.$case_m.'')->where('user='.$user)->save($data_m)!==false){						 
						 $arr['user'] = $user;
						 $arr['time'] = time();
						 if(M('gift_bag_record')->add($arr)){
							 $model->commit();
						     echo 1;
						 }
					}else{
						$model->rollback();
						echo 2;
					}
				}else{
					 $model->rollback();
					 echo 2;
				} 
			}
		}
    }
}