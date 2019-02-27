<?php
namespace Org\Our;
use Org\Our\Tool;
use Think\Model;
class Chest{

        public function chest($name,$double){
			if($double<=0){
				echo '请输入大于0的整数';
				 exit;
			}
			if(floor($double)==$double){
				
			}else{
				echo '请输入大于0的整数';
				 exit;
			}
			$double=intval(_safe($double));
			$name=_safe($name);
      			$shop = M('shop');
      			$shop_message = $shop->field('buy')->where('name="'.$name.'"')->find();
      			$get_seed = $shop_message['buy'];
            $data=M('treasure_chest')->where('name="'.$name.'"')->find();
            $table=new Tool();
			$prohibition = '土豆';
            $case='seed_warehouse';
            $tel=session('user');
            $user = session('user');
            $case_s=$table->table($tel,$case);
            $seed=$data['seed'];
            $case='members';
            $case_m=$table->table($tel,$case);
            $data_m=m($case_m)->field('nickname')->where('user="'.$user.'"')->find();
            $nickname=$data_m['nickname'];
            $data_seed=M($case_s)->where('user="'.$user.'" AND seeds ="'.$get_seed.'"')->find();
            $num_seed=$data_seed['num'];
			$model=new Model();
            if($double>1){
				
                $num=($double-1)*$data['seed_num'];
				$fruit_num=M($case_s)->where('user="'.$user.'" AND seeds ="'.$get_seed.'"')->sum('num');
				if($fruit_num>$num){
					if(M($case_s)->where('user="'.$user.'" AND seeds ="'.$get_seed.'"')->setDec('num',$num)){
							
						$data_open['open_seed']=$get_seed;
						$data_open['open_seed_num']=$num+$data['seed_num'];
						$data_open['time']=1;
						$data_open['type']='k';
						$data_open['user']=$nickname;
						M('record_treasure')->add($data_open);
					}else{
						$model->rollback();
						 echo '果实不够';
						 exit;
					}
				}else{
					$model->rollback();
						 echo '果实不够';
						 exit;
				}
				//echo $case_s;die;
        				
            }else{
				$data_open['open_seed']=$get_seed;
				$data_open['open_seed_num']=$data['seed_num'];
				$data_open['time']=1;
				$data_open['type']='k';
				$data_open['user']=$nickname;
				M('record_treasure')->add($data_open);
                $num=0;
            }

            if($num_seed>=$num){
                    $case='treasure_warehouse';
                    $tel=$user;
                    $case_t=$table->table($tel,$case);
                    $data_chest=M("$case_t")->where('user="'.$user.'" AND name="'.$name.'"')->find();
                    if($data_chest['num']>0){       //判断是否有宝箱
                        if(M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->setDec('num',1)){   //删除一个宝箱

                            if($data['number']>0){  //判断该宝箱可中奖人数还有多少
                                $max=$data['chance']*100;
                                $k=rand(1,100);
                                if($k<=$max){     //判定中奖

                                    $num=$data['seed_num']*$double*$data['multiple'];
                                    $table=new Tool();
                                    $case='seed_warehouse';
                                    $tel=$user;
                                    $case_s=$table->table($tel,$case);
                                    $c_seed=M('Seeds')->count();

                                    $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$seed.'"')->find();
                                    if(empty($data_seed)){
                                        $data_se['seeds']=$seed;
                                        $data_se['num']=$num;
                                        $data_se['user']=$user;
                                        if(M(''.$case_s.'')->add($data_se)){
                                            
                                        }else{
											$model->rollback();
                                           echo '系统故障3';
                                           exit;
                                        }
                                    }else{
                                        if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$seed.'"')->setInc('num',$num)){
                                          
                                        }else{
											$model->rollback();
                                           echo '系统故障5';
                                           exit;
                                        }
                                    }
									$data_record['open_seed']=$get_seed;
									$data_record['open_seed_num']=$num;
									$data_record['time']=1;
									$data_record['type']='k';
									$data_record['user']=$nickname;
									$data_record['image_name'] = image_name_icover($seed);
									$data_record['box_name'] = $name;
									M('record_treasure')->add($data_record);
									if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
										  $model->commit();
										  $cas=substr(session('user'),0,3);
										  $case_win=''.$cas.'_winning_record';
										  $data_win['user'] = session('user');
										  $data_win['name'] = $name;
										  $data_win['seed'] = $seed;
										  $data_win['num']  = $num;
										  $data_win['time'] = time();
										  M($case_win)->add($data_win);
											  
										  
										  if($data_record['open_seed']!==$prohibition){
											   //定义一个数组
											   $array = array();
											   //查看是否有缓存
											   $treasure_message = S('treasure_message');
											   $treasure_num = S('treasure_num');

											   //设置过期时间
											   $time=mktime(0,0,0,date('m'),date('d'),date('y'))+24*3600-time();
											   //如果不存在缓存
											   if($treasure_num==false){
												   Array_push($array,$data_record);  //将新添加数据加入空数组
												   S('treasure_message',$array,$time);  //新数组开启缓存
												   S('treasure_num',1,$time);  //计数从1开始
											   }else{
												   //如果存在缓存
												   Array_push($treasure_message,$data_record); //将新添加数据加入已有的缓存数组
												   $treasure_num = S('treasure_num')+1;   //计数加1
												   S('treasure_message',null);  //删除以前的缓存
												   S('treasure_num',null);    //删除以前的计数
												   S('treasure_message',$treasure_message,$time); //重新生成缓存
												   S('treasure_num',$treasure_num,$time);  //重新生成计数
											  }
										  }
										
										  $data=json_encode($data_record);
										  echo $data;

									}else{
										$model->rollback();
									   echo '系统故障4';
									   exit;
									}
                                }else{
                                    //判定未中奖赠送果实
                                    $table=new Tool();
                                    $case='seed_warehouse';
                                    $tel=$user;
                                    $case_s=$table->table($tel,$case);
                                    $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data['gift'].'"')->find();

                                    if(empty($data_seed)){
                                        $data_se['num']=$data['gift_num']*$double;
                                        $data_se['seeds']=$data['gift'];
                                        $data_se['user']=$user;
                                        if(M(''.$case_s.'')->add($data_se)){
                                            
                                        }else{
											$model->rollback();
                                          echo '系统故障7';
                                          exit;
                                        }
                                    }else{
										$data['gift_num']=$data['gift_num']*$double;
                                        if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data['gift'].'"')->setInc('num',$data['gift_num'])){
                                        }else{
											$model->rollback();
                                          echo '系统故障9';
                                          exit;
                                        }
                                    }
									$data_record['open_seed']=$data['gift'];
									$data_record['open_seed_num']=$data['gift_num'];
									$data_record['time']=1;
									$data_record['type']='k';
									$data_record['user']=$nickname;
									$data_record['image_name'] = image_name_icover($seed);
									$data_record['box_name'] = $name;
									//if(M('record_treasure')->add($data_record)){
										  $model->commit();
										  $cas=substr(session('user'),0,3);
										  $case_win=''.$cas.'_winning_record';
										  $data_win['user'] = session('user');
										  $data_win['name'] = $name;
										  $data_win['seed'] = $data['gift'];
										  $data_win['num']  = $data['gift_num'];
										  $data_win['time'] = time();
										  M($case_win)->add($data_win);
										  if($data_record['open_seed']!==$prohibition){
											   //定义一个数组
											   $array = array();
											   //查看是否有缓存
											   $treasure_message = S('treasure_message');
											   $treasure_num = S('treasure_num');

											   //设置过期时间
											   $time=mktime(0,0,0,date('m'),date('d'),date('y'))+24*3600-time();
											   //如果不存在缓存
											   if($treasure_num==false){
												   Array_push($array,$data_record);  //将新添加数据加入空数组
												   S('treasure_message',$array,$time);  //新数组开启缓存
												   S('treasure_num',1,$time);  //计数从1开始
											   }else{
												//如果存在缓存
												   Array_push($treasure_message,$data_record); //将新添加数据加入已有的缓存数组
												   $treasure_num = S('treasure_num')+1;   //计数加1
												   S('treasure_message',null);  //删除以前的缓存
												   S('treasure_num',null);    //删除以前的计数
												   S('treasure_message',$treasure_message,$time); //重新生成缓存
												   S('treasure_num',$treasure_num,$time);  //重新生成计数
											   }
										  }
										  
										  $data=json_encode($data_record);
										  echo $data;

									/*}else{
										$model->rollback();
									  echo '系统故障6';
									  exit;
									}*/
                                }
                            }else{
                                //当日剩余中奖人数为零
                                $data_se['seeds']=$data['gift'];
                                $data_se['num']=$data['gift_num']*$double;
                                $table=new Tool();
                                $case='seed_warehouse';
                                $tel=$user;
                                $case_s=$table->table($tel,$case);
                                $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->find();
                                if(empty($data_seed)){
                                    //$data_se['num']=$data['gift_num']*$double;
                                    $data_se['user']=$user;
                                    if(M(''.$case_s.'')->add($data_se)){                                        
                                    }else{
										$model->rollback();
                                      echo '系统故障11';
                                      exit;
                                    }
                                }else{
									
                                    if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->setInc('num',$data_se['num'])){                                        
                                    }else{
										$model->rollback();
                                      echo '系统故障13';
                                      exit;
                                    }
                                }
								$data_record['open_seed']=$data['gift'];
								$data_record['open_seed_num']= $data_se['num'];
								$data_record['time']=1;
								$data_record['type']='k';
								$data_record['user']=$nickname;
								$data_record['box_name'] = $name;
								$data_record['image_name'] = image_name_icover($seed);
								//if(M('record_treasure')->add($data_record)){
									  $model->commit();
									  $cas=substr(session('user'),0,3);
									  $case_win=''.$cas.'_winning_record';
									  $data_win['user'] = session('user');
									  $data_win['name'] = $name;
									  $data_win['seed'] = $data['gift'];
									  $data_win['num']  = $data_se['num'];
									  $data_win['time'] = time();
									  M($case_win)->add($data_win);
									  if($data_record['open_seed']!==$prohibition){
										  //定义一个数组
										  $array = array();
										  //查看是否有缓存
										  $treasure_message = S('treasure_message');
										  $treasure_num = S('treasure_num');

										  //设置过期时间
										  $time=mktime(0,0,0,date('m'),date('d'),date('y'))+24*3600-time();
										  //如果不存在缓存
										  if($treasure_num==false){
											   Array_push($array,$data_record);  //将新添加数据加入空数组
											   S('treasure_message',$array,$time);  //新数组开启缓存
											   S('treasure_num',1,$time);  //计数从1开始
										  }else{
											  //如果存在缓存
											   Array_push($treasure_message,$data_record); //将新添加数据加入已有的缓存数组
											   $treasure_num = S('treasure_num')+1;   //计数加1
											   S('treasure_message',null);  //删除以前的缓存
											   S('treasure_num',null);    //删除以前的计数
											   S('treasure_message',$treasure_message,$time); //重新生成缓存
											   S('treasure_num',$treasure_num,$time);  //重新生成计数
										  }
									  }
									  
									  $data=json_encode($data_record);
									  echo $data;

								/*}else{
									$model->rollback();
								  echo '系统故障10';
								  exit;
								}*/
                            }
                        }else{
						$model->rollback();
                          echo '系统故障14';
                          exit;
                        }
                    }elseif($data_chest['num']==0){
                        M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->delete();
                        echo $name.'数量不足';       //删除记录
                    }
            }else{
              echo '系统故障45';
              exit;
            }
      }

}
