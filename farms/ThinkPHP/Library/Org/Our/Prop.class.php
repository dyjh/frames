<?php
namespace Org\Our;
use Org\Our\Tool;
use Org\Our\Activity;

//道具
class Prop{
    /**
     * disasters()   除灾
     * @ $num    int     使用道具作用种植第几块地
     * // 1  为除草剂/草灾   // 2  为除虫剂/虫灾  // 3  为水壶/旱灾   // 5  丰收之心/
     * // 200--成功 400--失败 300--道具不够了  500--您目前这块地没有灾害
     * **/
    public function disasters($num){

        $Tool = New Tool;
        $user = session('user');
        $Planting_table = $Tool->table($user,'planting_record');
        $Prop_table = $Tool->table($user,'prop_warehouse');
        $user_planting = M("$Planting_table");
        $user_prop = M("$Prop_table");
        //开启事务
        $user_planting->startTrans();
        $user_prop->startTrans();

        $planting_list['number'] = $num;
        $planting_list['user'] = $user;
        $planting_list['harvest_state'] = 0;
        $disasters_message = $user_planting->field('id,disasters_state,disasters_time,disasters_value')->where($planting_list)->select();

        //如果存在任何一种灾害
        if($res[0]['disasters_state']!==0 || $res[0]['disasters_state']!==""){
            //灾害数组定义
            $disasters_data = array(1,2,3);
            //查找当前是何种灾害
            $disasters_case = in_array($disasters_message[0]['disasters_state'],$disasters_data);
            //如果存在，道具id即是灾害种类
            if($disasters_case){
                 $arr['user'] = $user;
                 $arr['prop_id'] = $disasters_message[0]['disasters_state'];
                 $user_prop_message = $user_prop->field('props,num')->where($arr)->select();

                 if($user_prop_message[0]['num']>=1){
                     //清除灾难状态和灾难时间
                     $list['disasters_state'] = 0;
                     $list['disasters_time'] = 0;
                     //灾害是否超过半个小时
                     if(time()-$disasters_message[0]['disasters_time']>=1800){   //超过修改状态，并减掉道具
                         $planting_res = $user_planting->where(array('id'=>$disasters_message[0]['id']))->save($list);
                         $prop_res = $user_prop->where($arr)->setDec('num',1);
                         $disasters_save = ture;
                         //$timeout = true;
                     }else{    //未超过修改状态，并减掉道具和灾难伤害
                         $planting_res = $user_planting->where(array('id'=>$disasters_message[0]['id']))->save($list);
                         $prop_res = $user_prop->where($arr)->setDec('num',1);
                         $disasters_save = $user_planting->where(array('id'=>$disasters_message[0]['id']))->setDec('disasters_value',7);
                         //$timeout = false;
						 
                     }

                     if($planting_res && $prop_res && $disasters_save){
                          //事务成功
                          $user_planting->commit();
                          $user_prop->commit();
                          $data['state'] = 30004;
						  
						  switch($disasters_message[0]['disasters_state']){
							  case '1': $dis_name = '草灾';break;
							  case '2': $dis_name = '虫灾';break;
							  case '3': $dis_name = '旱灾';break;
						  }
                         /* if($timeout){
                              $data['content'] = $dis_name.'已消除';
                          }else{
                              $data['content'] = $dis_name.'灾害已消除';
                          }*/
						  
						  $data['content'] = $dis_name.'已消除';
                          echo json_encode($data);
                          exit;
                     }else{
                          //事务失败
                          $user_planting->rollback();
                          $user_prop->rollback();
                          $data['state'] = 30003;
                          $data['content'] = '灾害消除失败';
                          echo json_encode($data);
                          exit;
                     }
                 }else{
                     $data['state'] = 30002;
                     if($user_prop_message[0]['props']){
                         $data['content'] = $user_prop_message[0]['props'].'不足';
                     }else{
                         $data['content'] = '仓库没有'.$user_prop_message[0]['props'];
                     }
                     echo json_encode($data);
                     exit;
                 }
             }else{
                 $data['state'] = 30001;
                 $data['content'] = '目前该地不存在灾害';
                 echo json_encode($data);
                 exit;
             }
         }
    }

    /**
     * prop()   道具的作用
     * @ $id    string     使用道具作用种植的id
     * @$user   string     使用这个道具的用户
     * @ $pop   string     使用道具的种类
     * // 4  为肥料  // 5  丰收之心
     * // 200--成功 400--失败 300--种子不够了  500--用错道具了
     * **/
    public function fertilization($num){
		//return ;
        /*$Tool = New Tool;
        $user = session('user');
        $Planting_table = $Tool->table($user,'planting_record');
        $Prop_table = $Tool->table($user,'prop_warehouse');

        $user_planting = M("$Planting_table");
        $user_prop = M("$Prop_table");
        //开启事务
        $user_planting->startTrans();
        $user_prop->startTrans();

        $comd['user'] = $user;
        $comd['prop_id'] = '4';
        $prop_num = $user_prop->field('num')->where($comd)->select();

        //查看道具够不够
        if($prop_num[0]['num']<1){
            $data['state'] = 20001;
            $data['content'] = '你目前拥有的肥料不足';
            echo json_encode($data);
            exit;
        }

        $com['user'] = $user;
        $com['number'] = $num;
        $com['harvest_state'] = 0;
        $join = $user_planting
              ->field('seed_type,seed_state,time,first_time,second_time,third_time,harvest_time')
              ->join('seeds on '.$Planting_table.'.seed_type=seeds.varieties')
              ->where($com)
              ->select();

        //如果查询出来没有该种子的生长周期记录
        if(!$join){
            $data['state'] = 20002;
            $data['content'] = '该种子不存在';
            echo json_encode($data);
            exit;
        }

        $first_time = $join[0]['harvest_time']-$join[0]['second_time']-$join[0]['third_time'];//钟子期完结时间
        $second_time = $join[0]['harvest_time']-$join[0]['third_time'];//发芽器完结时间
        $third_time = $join[0]['harvest_time'];//成熟期完结时间

        if($join[0]['seed_state']==0){ //匹配种子期
             //是否已经施过肥
             //if(S('fertilization')!==$num){
			   if(S($user."_fertilization")!==$num){
                 //如果大于两小时
                 if($first_time-time()>2*3600){
                     //修改种植记录的到下一阶段时间
                     $data['time'] = time()+2*3600;
                     //如果修改成功，并且减掉了相应的道具
                     if($user_planting->where($com)->save($data)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                          //事务成功
                          $user_planting->commit();
                          $user_prop->commit();
                          //S('fertilization',$num,$first_time-$data['time']);

						  S($user."_fertilization",$num,$first_time-$data['time']);
                          $data['state'] = 20004;
                          $data['content'] = '施肥成功';
                          echo json_encode($data);
                          exit;
                     }else{
                          //事务失败
                          $user_planting->rollback();
                          $user_prop->rollback();
                          $data['state'] = 20003;
                          $data['content'] = '施肥失败';
                          echo json_encode($data);
                          exit;
                     }
                 }else{
                     //距离下一阶段不足两小时
                     $arr['seed_state'] = 1;
                     $arr['time'] = $first_time;
                     //直接修改状态，并且减掉道具
                     if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                         //事务成功
                         $user_planting->commit();
                         $user_prop->commit();
                         $data['state'] = 20004;
                         $data['content'] = '施肥成功';
                         $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                         $data['next_phase'] = '1';
                         echo json_encode($data);
                         exit;
                     }else{
                         //事务失败
                         $user_planting->rollback();
                         $user_prop->rollback();
                         $data['state'] = 20003;
                         $data['content'] = '施肥失败';
                         echo json_encode($data);
                         exit;
                     }
                 }
             }else{
                $data['state'] = 20005;
                $data['content'] = '该阶段只能施一次肥';
                echo json_encode($data);
                exit;
             }

         }else if($join[0]['seed_state']==1){
             //是否已经施过肥
             if(S($user."_fertilization")!==$num){
                 //如果大于两小时
                 if($second_time-time()>2*3600){
                     //修改种植记录的到下一阶段时间
                     $data['time'] = time()+2*3600;
                     //如果修改成功，并且减掉了相应的道具
                     if($user_planting->where($com)->save($data)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                          //事务成功
                          $user_planting->commit();
                          $user_prop->commit();
                          S($user."_fertilization",$num,$second_time-$data['time']);
                          $data['state'] = 20004;
                          $data['content'] = '施肥成功';
                          echo json_encode($data);
                          exit;
                     }else{
                          //事务失败
                          $user_planting->rollback();
                          $user_prop->rollback();
                          $data['state'] = 20003;
                          $data['content'] = '施肥失败';
                          echo json_encode($data);
                          exit;
                     }
                 }else{
                     //距离下一阶段不足两小时
                     $arr['seed_state'] = 2;
                     $arr['time'] = $second_time;
                     //直接修改状态，并且减掉道具
                     if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                         //事务成功
                         $user_planting->commit();
                         $user_prop->commit();
                         $data['state'] = 20004;
                         $data['content'] = '施肥成功';
                         $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                         $data['next_phase'] = '2';
                         echo json_encode($data);
                         exit;
                     }else{
                         //事务失败
                         $user_planting->rollback();
                         $user_prop->rollback();
                         $data['state'] = 20003;
                         $data['content'] = '施肥失败';
                         echo json_encode($data);
                         exit;
                     }
                 }
              }else{
                  $data['state'] = 20005;
                  $data['content'] = '该阶段只能施一次肥';
                  echo json_encode($data);
                  exit;
              }

         }else if($join[0]['seed_state']==2){
             //是否已经施过肥
             if(S($user."_fertilization")!==$num){
                 //如果大于两小时
                 if($third_time-time()>2*3600){
                     //修改种植记录的到下一阶段时间
                     $data['time'] = time() + 2*3600;
                     //如果修改成功，并且减掉了相应的道具
                     if($user_planting->where($com)->save($data)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                          //事务成功
                          $user_planting->commit();
                          $user_prop->commit();
                          S($user."_fertilization",$num,$third_time-$data['time']);
                          $data['state'] = 20004;
                          $data['content'] = '施肥成功';
                          echo json_encode($data);
                          exit;
                     }else{
                          //事务失败
                          $user_planting->rollback();
                          $user_prop->rollback();
                          $data['state'] = 20003;
                          $data['content'] = '施肥失败';
                          echo json_encode($data);
                          exit;
                     }
                 }else{
                     //距离下一阶段不足两小时
                     $arr['seed_state'] = 3;
                     $arr['time'] = $third_time;
                     //直接修改状态，并且减掉道具
                     if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                         //事务成功
                         $user_planting->commit();
                         $user_prop->commit();
                         $data['state'] = 20004;
                         $data['content'] = '施肥成功';
                         $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                         $data['next_phase'] = '3';
                         echo json_encode($data);
                         exit;
                     }else{
                         //事务失败
                         $user_planting->rollback();
                         $user_prop->rollback();
                         $data['state'] = 20003;
                         $data['content'] = '施肥失败';
                         echo json_encode($data);
                         exit;
                     }
                 }
             }else{
                 $data['state'] = 20005;
                 $data['content'] = '该阶段只能施一次肥';
                 echo json_encode($data);
                 exit;
             }
         } */

        $Tool = New Tool;
        $user = session('user');
        $Planting_table = $Tool->table($user,'planting_record');
        $Prop_table = $Tool->table($user,'prop_warehouse');

        $user_planting = M("$Planting_table");
        $user_prop = M("$Prop_table");
        //开启事务
        $user_planting->startTrans();
        $user_prop->startTrans();

        $comd['user'] = $user;
        $comd['prop_id'] = '4';
        $prop_num = $user_prop->field('num')->where($comd)->select();
		//echo $prop_num[0]['num'];die;
        //查看道具够不够
        if($prop_num[0]['num']<1){
            $data['state'] = 20001;
            $data['content'] = '肥料不足';
            $data['cccc'] = $prop_num[0];
            echo json_encode($data);
            exit;
        }

        $com['user'] = $user;
        $com['number'] = $num;
        $com['harvest_state'] = 0;
        $join = $user_planting
              ->field($Planting_table.'.id,seed_type,seed_state,time,first_time,second_time,third_time,harvest_time')
              ->join('seeds on '.$Planting_table.'.seed_type=seeds.varieties')
              ->where($com)
              ->select();
			
		$com['id'] = $join[0]['id'];
			
        //如果查询出来没有该种子的生长周期记录
        if(!$join){
            $data['state'] = 20002;
            $data['content'] = '该种子不存在';
            echo json_encode($data);
            exit;
        }
		
        /**********************************/

        $needs_time[] = $join[0]['harvest_time']-$join[0]['second_time']-$join[0]['third_time'];//钟子期完结时间
        $needs_time[] = $join[0]['harvest_time']-$join[0]['third_time'];//发芽器完结时间
        $needs_time[] = $join[0]['harvest_time'];//成熟期完结时间

        //获取状态下  是否能够施肥
	
	    if( $join[0]['time'] - $join[0]['harvest_time'] == 0 && $join[0]['seed_state']==3){
            $data['state'] = 20006;
            $data['content'] = '作物已成熟，无法施肥';
            echo json_encode($data);
            exit;
        }
		
		//S($user.$num."_fertilization",null);
		
		//缓存
        $fertilization_state =  S($user.$num."_fertilization");
        //施肥可减少时间 
        $fertilization_time =  2 * 3600 ; 
	
        if($fertilization_state[$join[0]['seed_state']]){
              $data['state'] = 20005;
              $data['content'] = '该阶段只能施一次肥';
              echo json_encode($data);
              exit;
        }else{

            if( ($needs_time[$join[0]['seed_state']] - time()) < $fertilization_time){
				
                $fertilization['time']  = time();
                $fertilization['seed_state']  = $join[0]['seed_state'];
                $fertilization_state[$join[0]['seed_state']] = $fertilization;

                $arr['seed_state'] = $join[0]['seed_state'] + 1;
                $arr['time'] = time();
                $arr['harvest_time'] = $join[0]['harvest_time'] - $needs_time[$join[0]['seed_state']] + time();

                if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                    //事务成功
                    $user_planting->commit();
                    $user_prop->commit();
                    $data['state'] = 20004;
                    $data['content'] = '施肥成功';
                    $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                    $data['next_phase'] = $join[0]['seed_state']+1;
                    S($user.$num."_fertilization",$fertilization_state,$arr['harvest_time']-time());
					
					// 2017-08-19 增加施肥记录	 表为  record_conversion
						$fertilization_add_record['user']    = $user;
						$fertilization_add_record['name']    = "化肥";
						$fertilization_add_record['coin']    = $join[0]['seed_state'];  // 借用coin  字段 存储施肥阶段
						$fertilization_add_record['num']     = $num;					  //  借用num 字段 存储施肥土地
						$fertilization_add_record['diamond'] = $join[0]['id'];  	   // 借用 diamond  字段 存储种植ID
						$fertilization_add_record['buy_time']= time();					  // 
						$fertilization_add_record['type']    = "f";							
					    M(substr($user,0,3).'_record_conversion')->add($fertilization_add_record);
					
                    echo json_encode($data);
                    exit;
                }else{
                    //事务失败
                    $user_planting->rollback();
                    $user_prop->rollback();
                    $data['state'] = 20003;
                    $data['content'] = '施肥失败';
                    echo json_encode($data);
                    exit;
                }

            }else{
                $fertilization['time']  = time();
                $fertilization['seed_state']  = $join[0]['seed_state'];
                $fertilization_state[$join[0]['seed_state']] = $fertilization;

                $arr['time'] = $join[0]['time'] - $fertilization_time;
                $arr['harvest_time'] = $join[0]['harvest_time'] - $fertilization_time;

                if( ( $join[0]['harvest_time'] - $join[0]['time'] )< $fertilization_time){


                    $fertilization['time']  = time();
                    $fertilization['seed_state']  = $join[0]['seed_state'];
                    $fertilization_state[$join[0]['seed_state']] = $fertilization;

                    $arr['seed_state'] = $join[0]['seed_state'] + 1;
                    $arr['time'] = time();
                    $arr['harvest_time'] = $join[0]['harvest_time'] - $needs_time[$join[0]['seed_state']] + time();

                    if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                        //事务成功
                        $user_planting->commit();
                        $user_prop->commit();
                        $data['state'] = 20004;
                        $data['content'] = '施肥成功';
                        $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                        $data['next_phase'] = $join[0]['seed_state']+1;
                        S($user.$num."_fertilization",$fertilization_state,$arr['harvest_time']-time());
						
						// 2017-08-19 增加施肥记录	 表为  record_conversion
						$fertilization_add_record['user']    = $user;
						$fertilization_add_record['name']    = "化肥";
						$fertilization_add_record['coin']    = $join[0]['seed_state'];  // 借用coin  字段 存储施肥阶段
						$fertilization_add_record['diamond'] = $join[0]['id'];  	   // 借用 diamond  字段 存储种植ID
						$fertilization_add_record['num']     = $num;					  //  借用num 字段 存储施肥土地
						$fertilization_add_record['buy_time']= time();					  //  
						$fertilization_add_record['type']    = "f";	
					    M(substr($user,0,3).'_record_conversion')->add($fertilization_add_record);
						
                        echo json_encode($data);
                        exit;
                    }else{
                        //事务失败
                        $user_planting->rollback();
                        $user_prop->rollback();
                        $data['state'] = 20003;
                        $data['content'] = '施肥失败';
                        echo json_encode($data);
                        exit;
                    }

                }
                
                if($user_planting->where($com)->save($arr)!==false && $user_prop->where($comd)->setDec('num',1)!==false){
                    //事务成功
                    $user_planting->commit();
                    $user_prop->commit();
                    $data['state'] = 20004;
                    $data['content'] = '施肥成功';
                    $data['seed_type'] = image_name_icover($join[0]['seed_type']);
                    $data['next_phase'] = $join[0]['seed_state'];
                    S($user.$num."_fertilization",$fertilization_state,$join[0]['harvest_time']-time());
					
					// 2017-08-19 增加施肥记录	 表为  record_conversion
						$fertilization_add_record['user']    = $user;
						$fertilization_add_record['name']    = "化肥";
						$fertilization_add_record['coin']    = $join[0]['seed_state'];  // 借用coin  字段 存储施肥阶段
						$fertilization_add_record['num']     = $num;					  //  借用num 字段 存储施肥土地
						$fertilization_add_record['diamond'] = $join[0]['id'];  	   // 借用 diamond  字段 存储种植ID
						$fertilization_add_record['buy_time']= time();					  //  
						$fertilization_add_record['type']    = "f";					  //  
					    M(substr($user,0,3).'_record_conversion')->add($fertilization_add_record);
						
                    echo json_encode($data);
                    exit;
                }else{
                    //事务失败
                    $user_planting->rollback();
                    $user_prop->rollback();
                    $data['state'] = 20003;
                    $data['content'] = '施肥失败';
                    echo json_encode($data);
                    exit;
                }
            }           
        }
    }


    //收获
    public function harvest($user,$num,$type){
        $Tool = New Tool;
        //$user = session('user');

        $managed_table = $Tool->table($user,'managed_to_record');
        $Planting_table = $Tool->table($user,'planting_record');
        $user_managed = M("$managed_table");
        $user_planting = M("$Planting_table");

        //查找本条种植记录
        $data['user'] = $user;
        $data['number'] = $num;
        $data['harvest_state'] = 0;
        $planting_record = $user_planting->field('seed_type,seed_state,disasters_value,harvest_num,harvest_state,harvest_time')->where($data)->select();
        //$managed_record = $user_managed->field('id,end_time')->where('user="'.$user.'" and service_type=4 and state=0')->find();
		$managed_record = $user_managed->field('end_time')->where('user="'.$user.'" and service_type=4 and state=0')->select();
		
		if($planting_record[0]['harvest_state']==1){
			 $data['state'] = 40004;
             $data['content'] = '土地已收获';
             echo json_encode($data);
             exit;
		}
		
		if($planting_record[0]['seed_state']!=3 || $planting_record['harvest_time']>time()){
			 $data['state'] = 40004;
             $data['content'] = '种子还未成熟';
             echo json_encode($data);
             exit;
		}
		
		if($managed_record){
			
			for($i=0;$i<count($managed_record);$i++){
				if($managed_record[$i]['end_time']>=time()){
					$managed_state = 'have';
				}
			}
			
			if($managed_state=='have'){
			    $Add_value = 7;
			}else{
				$Add_value = 0;
			}			
			/*if(time()<=$managed_record['end_time']){
			    $Add_value = 7;
			}else{
				//时间差安全机制
				$man_state['state'] = 1;
				//$user_managed->where('user="'.$user.'" and service_type=4 and state=0')->save($man_state);
				$user_managed->where('id='.$managed_record['id'])->save($man_state);
				$Add_value = 0;
			}*/
		}else{
			$Add_value = 0;
		}
	
	
	    //六级手机碎片活动

	    /*$member = substr($user,0,3).'_members';
	    $level = M("$member")->field('level')->where(array('user'=>$user))->find();
		if($level['level']>=6){
			$Activity = New Activity();
			$Acti_name = $Activity->production($user);
			//如果有奖品，则添加，前端进行判断
			if(!empty($Acti_name)){
				$data['activity'] = $Acti_name;
			}		  			
		}*/
		
        if($planting_record[0]['disasters_value']==0 || $planting_record[0]['disasters_value']==""){

             if($planting_record[0]['seed_type']=="摇钱树"){
                 $this->Storage($user,'分红宝',$planting_record[0]['harvest_num']);
             }else{
				 $fruit_num = $planting_record[0]['harvest_num']+$Add_value;
                 $this->Storage($user,$planting_record[0]['seed_type'],$fruit_num);
             }
			 
             //修改土地的收获状态
             $arr['harvest_state'] = 1;
             $harvest_state = $user_planting->where($data)->save($arr);
             if($harvest_state){
				  //消除缓存
				  S($user.$num."_fertilization",null);
		  
                  $data['state'] = 40003;
				  if($planting_record[0]['seed_type']=="摇钱树"){
					   $data['content'] = '收获:分红宝x'.$planting_record[0]['harvest_num']; 
				  }else{
					   $data['content'] = '收获:'.$planting_record[0]['seed_type'].'x'.$planting_record[0]['harvest_num'];
					   if($Add_value!==0){
                           $data['content'].= ' 丰收+'.$Add_value;
                       }					  
				  }
				  
                  if($type=='manual'){
					  $str = $user.' '.date('Y-m-d H:i:s',time()).' 手动';
					  file_put_contents('../log/harvest.log',$str.PHP_EOL."\n",FILE_APPEND);
					  echo json_encode($data);
                      exit;
				  }else if($type='auto'){
					  $str = $user.' '.date('Y-m-d H:i:s',time()).' 自动';
					  file_put_contents('../log/harvest.log',$str.PHP_EOL."\n",FILE_APPEND);
					  return 40003;
				  }
				  
             }else{
                 $data['state'] = 40002;
                 $data['content'] = '收获状态修改失败';
                 echo json_encode($data);
                 exit;
             }
        }else{
			
			if($planting_record[0]['seed_type']=="摇钱树"){
                 $this->Storage($user,'分红宝',$planting_record[0]['harvest_num']);
             }else{
				 $fruit_num = $planting_record[0]['harvest_num']-$planting_record[0]['disasters_value']+$Add_value;
                 $this->Storage($user,$planting_record[0]['seed_type'],$fruit_num);
             }
             //修改土地的收获状态
             $arr['harvest_state'] = 1;
			 $arr['disasters_state'] = 0;
			 $arr['disasters_time'] = 0;
			 $arr['disasters_value'] = 0;
             $harvest_state = $user_planting->where($data)->save($arr);
             if($harvest_state){
				 //消除缓存
				 S($user.$num."_fertilization",null);
				 
                 $data['state'] = 40003;
				 if($planting_record[0]['seed_type']=="摇钱树"){ 
					 $data['content'] = '收获:分红宝x'.$planting_record[0]['harvest_num'];
				 }else{
					 $data['content'] = '收获:'.$planting_record[0]['seed_type'].'x'.$planting_record[0]['harvest_num'];
                     $data['content'].= ' 灾害-'.$planting_record[0]['disasters_value'];
                     if($Add_value!==0){
                        $data['content'].= ' 丰收+'.$Add_value;
                     }
				 }
				 
                 if($type=='manual'){
					  $str = $user.' '.date('Y-m-d H:i:s',time()).' 手动';
					  file_put_contents('../log/harvest.log',$str.PHP_EOL."\n",FILE_APPEND);
					  echo json_encode($data);
                      exit;
				 }else if($type='auto'){
					  $str = $user.' '.date('Y-m-d H:i:s',time()).' 自动';
					  file_put_contents('../log/harvest.log',$str.PHP_EOL."\n",FILE_APPEND);
					  return 40003;
				 }
             }else{
                 $data['state'] = 40002;
                 $data['content'] = '收获状态修改失败';
                 echo json_encode($data);
                 exit;
             }
        }
    }


    //入库
    private function Storage($user,$seed_type,$fruit_num){
          $Tool = New Tool;
          //$user = session('user');

          $fruit_table = $Tool->table($user,'seed_warehouse');
          $user_fruit_warehouse = M("$fruit_table");
          //开启事务
          $user_fruit_warehouse->startTrans();

          //查询仓库是否存在，存在则修改
          $data['user'] = $user;
          $data['seeds'] = $seed_type;
          $fruit_message = $user_fruit_warehouse->where($data)->select();
          if($fruit_message){
               if($user_fruit_warehouse->where($data)->setInc('num',$fruit_num)==false){
                   $data['state'] = 40001;
                   $data['content'] = '果实入库失败！';
                   echo json_encode($data);
                   exit;
               }else{
                   //事务成功
                   $user_fruit_warehouse->commit();
               };
          }else{
               $arr['user'] = $user;
               $arr['seeds'] = $seed_type;
               $arr['num'] = $fruit_num;
               if($user_fruit_warehouse->add($arr)==false){
                   $data['state'] = 40001;
                   $data['content'] = '果实入库失败！';
                   echo json_encode($data);
                   exit;
               }else{
                   //事务成功
                   $user_fruit_warehouse->commit();
               };
          }
    }
}
