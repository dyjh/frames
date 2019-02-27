<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\FruitRecordModel;
use Org\Our\Tool;

class MenudataController extends Controller{

    //兑换
    public function exchange(){

        $page = I("post.page");//  测试

        $member_where['user'] = session('user');
        $member_prefix = substr(session('user'),0,3);
        $material_table = $member_prefix."_meterial_warehouse";

        // 查询出所有兑换材料
        $all_material_list = M("house_material")->page($page,4)->select();

        if($all_material_list){

            $all_material_list = material_handle_list($all_material_list);    //  已发布的所有材料  及 兑换数量

            //获取用户拥有的果实，及能够兑换的材料数
            $SeedWareHousee = new FruitRecordModel($member_prefix."_seed_warehouse");

            $seed_warehouse = $SeedWareHousee->get_user_seed_list($member_where,1,1);

            foreach($seed_warehouse as $key=>$val){
                $seed_list[$val['seeds_id']]['seeds'] =$val['seeds'];
                $seed_list[$val['seeds_id']]['num'] = $val['nums'];
                $seed_list[$val['seeds_id']]['free_seed_num'] =$val['free_seed_num'];
                $seed_list[$val['seeds_id']]['can_use_seed_num'] =$val['num'];
            }

            // 计算用户可以兑换的材料数量
            foreach($all_material_list as $key=>$val){
                $change_seed_list = array();
                foreach($val as $seed_id=>$value){
                    if(is_numeric($seed_id)){
                        $seed_ids['need_value'] = $value;                                                  // 所需果实数量
                        $seed_ids['seed_name']  = $seed_list[$seed_id]['seeds'];                           // 果实名称
                        $seed_ids['user_has_use_seed'] = $seed_list[$seed_id]['can_use_seed_num'];         // 用户可用的果实数量
                        $change_seed_list[] = $seed_ids;
                    }
                }
                $change_list['seeds_list'] = $change_seed_list ;
                $change_list['material_name'] = $val['name'];
                $change_list['material_id'] = $val['id'];
                $change_list['price'] = $val['price'];
                $array[] = $change_list;
            }			
			
            unset($seed_warehouse);

            $str = "";
            for($i=0;$i<count($array);$i++){

                $str .= '<div class="exchange_list">';
                $str .= '<div class="exchange_material" onclick="material_title('.$array[$i]['material_id'].')">';
                $str .= '<div class="exchange_title" id="exchange_title_'.($i+1).'">';
                $str .= '<span>['.$array[$i]['material_name'].']可用于土地升级</span>';
                $str .= '</div>';
                $str .= '<img src="/farms/Public/Home/images/index/'.image_name_icover($array[$i]['material_name']).'2.png">';
                $str .= '</div>';
                $str .= '<div class="exchange_box">';
                $str .= '<div class="exchange_fruitnumber"><span class="needfruita_'.($i+1).'">'.$array[$i]['seeds_list'][0]['need_value'].'</span></div>';
                $str .= '<div class="exchange_fruitnumber"><span class="needfruitb_'.($i+1).'">'.$array[$i]['seeds_list'][1]['need_value'].'</span></div>';
                $str .= '<div class="exchange_fruit" style="margin-left: -1%">';
                $str .= '<img src="/farms/Public/Home/images/fruit/'.image_name_icover($array[$i]['seeds_list'][0]['seed_name']).'.png">';
                $str .= '</div>';
                $str .= '<div class="exchange_fruit">';
                $str .= '<img src="/farms/Public/Home/images/fruit/'.image_name_icover($array[$i]['seeds_list'][1]['seed_name']).'.png">';
                $str .= '</div>';
                $str .= '<div class="have_fruit" style="margin-left: -4%"><span class="have_fruita_'.($i+1).'">'.$array[$i]['seeds_list'][0]['user_has_use_seed'].'</span></div>';
                $str .= '<div class="have_fruit"><span class="have_fruitb_'.($i+1).'">'.$array[$i]['seeds_list'][1]['user_has_use_seed'].'</span></div>';
                $str .= '</div>';
                $str .= '<div class="deals_numberbox">';
                $str .= '<input type="number" name="" class="deals_number_'.($i+1).'" value="1">';
                $str .= '<div class="deals_drop" style="margin-left: 0%" onclick="count(\'exchange\',1,'.($i+1).')"></div>';
                $str .= '<div class="deals_drop" onclick="count(\'exchange\',2,'.($i+1).')"></div>';
                $str .= '</div>';
                $str .= '<div class="determine_box">';
                $str .= '<div class="determine_button" style="margin-top:37%" onclick="exchange_fruit('.$array[$i]['material_id'].',1)"></div>';
                $str .= '</div></div></div>';
            }
            echo json_encode($str);
            die;

        }else{
            echo '';
        }
    }


    public function warehouse(){

        $Post = I("post.");
        $page = $Post['page'];

        $where_warehouse = array('user'=>session('user'),"num"=>array("gt","0"));

        switch ($Post['type']) {

            case 'fruit':

                $table_fix = substr(session('user'),0,3);
                $table = $table_fix."_seed_warehouse";
                $seed_warehouse = M("$table");
                $res = $seed_warehouse->field('seeds,num')->page($page,16)->where($where_warehouse)->select();
				
                $str = '';

                if($res){
                    for($i=0;$i<count($res);$i++){
                        $str .= '<div class="warehose_prompt_box">';
                        if($res[$i]['seeds']=="分红宝"){
                            $str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['seeds'].']可获得分红奖励</span></div>';
                        }else{
							
                           //$str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['seeds'].']可用于出售或者兑换材料</span></div>';
							$re_seed = $res[$i]['seeds'];
                            $str .= "<div class='warehose_prompt warehose_prompt_".($i+1)."'><span>[".$res[$i]['seeds']."]可用于出售或者兑换材料&nbsp;&nbsp;&nbsp;&nbsp;<b style='color:red' onclick=\"reborn('$re_seed')\">重生种子</b></span></div>";
                        }
                        $str .= '<div class="warehouse_list_box" onclick="click_warehouse('.$i.')">';
                        $str .= '<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['seeds']).'.png">';
                        $str .= '<div class="warehouse_number"><span>'.$res[$i]['num'].'</span></div>';
                        $str .= '</div></div>';
                    }				
                }else{
                    $str .= '<div class="warehose_prompt_box">暂无果实</div>';
                }
                echo json_encode($str);
                die;
                break;

            case 'material':

                $table_fix = substr(session('user'),0,3);
                $table = $table_fix."_meterial_warehouse";
                $meterial_warehouse = M("$table");
                $res = $meterial_warehouse->field('prop_name,num')->page($page,16)->where($where_warehouse)->select();

                if($res){
                    $str = "";
                    for($i=0;$i<count($res);$i++){
                        $str .= '<div class="warehose_prompt_box">';
                        $str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['prop_name'].']可用于升级土地</span></div>';
                        $str .= '<div class="warehouse_list_box" onclick="click_warehouse('.$i.')">';
                        $str .= '<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['prop_name']).'.png">';
                        $str .= '<div class="warehouse_number"><span>'.$res[$i]['num'].'</span></div>';
                        $str .= '</div></div>';
                    }
                    echo json_encode($str);
                    die;
                }else{
                    echo json_encode('<div class="warehose_prompt_box">暂无材料</div>');
                }
                break;

            case 'prop':

                $table_fix = substr(session('user'),0,3);
                $table = $table_fix."_prop_warehouse";
                $prop_warehouse = M("$table");
                $res = $prop_warehouse->field('props,num')->page($page,16)->where($where_warehouse)->select();
                if($res){
                    $str = "";
                    for($i=0;$i<count($res);$i++){
                        $str .= '<div class="warehose_prompt_box">';
                        $str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['props'].']';
                        if($res[$i]['props']=="种子"){
                            $str .=  '神秘的种子</span></div>';
                        }else if($res[$i]['props']=="肥料"){
                            $str .=  '加速种植物生长2小时</span></div>';
                        }else if($res[$i]['props']=="水壶"){
                            $str .=  '消除旱灾</span></div>';
                        }else if($res[$i]['props']=="除草剂"){
                            $str .=  '消除草灾</span></div>';
                        }else if($res[$i]['props']=="除虫剂"){
                            $str .=  '消除虫灾</span></div>';
                        }
                        $str .= '<div class="warehouse_list_box" onclick="click_warehouse('.$i.')">';
                        $str .= '<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['props']).'2.png">';
                        $str .= '<div class="warehouse_number"><span>'.$res[$i]['num'].'</span></div>';
                        $str .= '</div></div>';
                    }
                    echo json_encode($str);
                    die;
                }else{
                    $str = '<div class="warehose_prompt_box">暂无道具</div>';
                    echo json_encode($str);
                    die;
                }
                break;

            case 'utreasure':

                $table_fix = substr(session('user'),0,3);
                $table = $table_fix."_treasure_warehouse";
                $prop_warehouse = M("$table");
                $res = $prop_warehouse->page($page,16)->where($where_warehouse)->select();
                if($res){
                    $str = '';
                    for($i=0;$i<count($res);$i++){
                        $str .= '<div class="warehose_prompt_box">';
                        $str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['name'].']</span></div>';
                        $name = $res[$i]['name'];
                        $str .= "<div class='warehouse_list_box' onclick=\"open_box('$name')\">";
                        $str .= '<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['name']).'2.png">';
                        $str .= '<div class="warehouse_number"><span>'.$res[$i]['num'].'</span></div>';
                        $str .= '</div></div>';
                    }
                    echo json_encode($str);
                    die;
                }else{
                    $str = '<div class="warehose_prompt_box">暂无宝箱</div>';
                    echo json_encode($str);
                    die;
                }
			case 'huodong':
			
				 $table_fix = substr(session('user'),0,3);
                 $table = $table_fix."_activity_warehouse";
                 $activity_warehouse = M("$table");
                 $res = $activity_warehouse->page($page,16)->where($where_warehouse)->select();
				 if($res){
                    $str = '';
                    for($i=0;$i<count($res);$i++){
                        $str .= '<div class="warehose_prompt_box">';
                        $str .= '<div class="warehose_prompt warehose_prompt_'.($i+1).'"><span>['.$res[$i]['name'].']</span></div>';
                        $name = $res[$i]['name'];
                        $str .= '<div class="warehouse_list_box" onclick="synthetic()">';
                        $str .= '<img src="/farms/Public/Home/images/huodong/'.image_name_icover($res[$i]['name']).'.png">';
                        $str .= '<div class="warehouse_number"><span>'.$res[$i]['num'].'</span></div>';
                        $str .= '</div></div>';
                    }
                    echo json_encode($str);
                    die;
                }else{
                    $str = '<div class="warehose_prompt_box" style="width:30%">暂无活动材料</div>';
                    echo json_encode($str);
                    die;
                }
        }
    }


    public function shop(){

        $Post = I("post.");
        $page = $Post['page'];
        $shop = M('shop');
		
        //开启memcached
        //$mem=new \Memcached;
        //$mem->addServer('localhost',11211);

		//$mem->delete($Post['type']);
        //var_dump($mem->get($Post['type']));
        //exit;

        if($Post['type']=="seed"){
			
			$res = $shop->page($page,4)->order('id desc')->where('name="种子" and type=1 or name="肥料" and type=1')->select();
            /*if($mem->get('seed')){
                echo json_encode($mem->get('seed'));
                exit;
            }else{
                $res = $shop->page($page,4)->order('id desc')->where('name="种子" and type=1 or name="肥料" and type=1')->select();
            }*/
        }else if($Post['type']=="shopprop"){
            
			/*if($mem->get('shopprop')){
                echo json_encode($mem->get('shopprop'));
                exit;
            }else{
                $res = $shop->page($page,4)->order('id desc')->where('name="除草剂" and type=1 or name="除虫剂" and type=1 or name="水壶" and type=1')->select();
            }*/
			
			$res = $shop->page($page,4)->order('id desc')->where('name="除草剂" and type=1 or name="除虫剂" and type=1 or name="水壶" and type=1')->select();
        
		}else if($Post['type']=="treasure"){
           /* if($mem->get('treasure')){
                echo json_encode($mem->get('treasure'));
                exit;
            }else{
                $res = $shop->page($page,4)->where('name="黄铜宝箱" and type=1 or name="白银宝箱" and type=1 or name="黄金宝箱" and type=1 or name="钻石宝箱" and type=1')->select();
            }*/
			$res = $shop->page($page,4)->where('name="黄铜宝箱" and type=1 or name="白银宝箱" and type=1 or name="黄金宝箱" and type=1 or name="钻石宝箱" and type=1')->select();
			
        }else if($Post['type']=="service" || $Post['type']=="exchange"){
            /*if($mem->get('service')){
                echo json_encode($mem->get('service'));
                exit;
            }else{
                $res = $shop->page($page,4)->where('name="草灾守护" and type=1 or name="虫灾守护" and type=1 or name="旱灾守护" and type=1 or name="丰收之心" and type=1 or name="自动收获" and type=1')->select();
            }*/						
			$res = $shop->page($page,5)->where('name="草灾守护" and type=1 or name="虫灾守护" and type=1 or name="旱灾守护" and type=1 or name="丰收之心" and type=1 or name="稻草人" and type=1')->select();   					
        }

        $str = '';
		
		if($Post['type']=="service"){
			
			for($i=0;$i<count($res);$i++){
			   $str.='<div class="shop_body_boxss">';
               $str.='<div class="shop_ser_box">';
			   $str.='<div class="shop_ser_img">';
			   $str.='<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['name']).'.png">';			
			   $str.='</div>';	
               $str.='<div class="ser_buy_dimd" style="margin-top:15%">';			   
			   $str.='<img src="/farms/Public/Home/images/index/buy_diamond.png">';		
			   $str.='<span class="need_'.$res[$i]['id'].'">'.$res[$i]['price'].'</span>';			
			   $str.='</div>';
			   
			   if(!empty($res[$i]['seed'])){  
				   $need_seed = explode('|',$res[$i]['seed']);
				   for($a=0;$a<count($need_seed);$a++){
					    $seed_list =  explode(',',$need_seed[$a]);
						$str.='<div class="ser_buy_dimd">';			   
					    $str.='<img src="/farms/Public/Home/images/fruit/'.image_name_icover($seed_list[0]).'.png" style="width:41%;height:70%;margin-top:-6%;margin-left: -8%;">';
					    $str.='<span>'.$seed_list[1].'</span>';
					    $str.='</div>';
				   } 
			   }else{
				   $str.='<div class="ser_buy_dimd">';			   
				   $str.='只需钻石即可购买';
				   $str.='</div>';
			   }
               
			   $str.='</div>';			   
			   $str.='<div class="shop_ser_box">';			
			   $str.='<div class="ser_text">';
               $str.='<p style="font-weight: bold;">'.$res[$i]['name'].'</p>';			   
			   $str.='<p>'.$res[$i]['note'].'</p>';
               $str.='</div>';
               $str.='<div class="ser_input">';
               $str.='<input class="shop_number_'.$res[$i]['id'].'" type="number" value="1" oninput="if(value.length>5)value=value.slice(0,5)">';			   
			   $str.='<img src="/farms/Public/Home/images/index/shop_number.png"></div>';		
			   $str.='<div class="add_input">';	
               $str.='<div class="add_img">';
               $str.='<img onclick="count(\'shop\',1,'.$res[$i]['id'].')" src="/farms/Public/Home/images/index/drop.png">';			   
			   $str.='</div>';			
			   $str.='<div class="add_img" style="margin-left:6%">';			
			   $str.='<img onclick="count(\'shop\',2,'.$res[$i]['id'].')" src="/farms/Public/Home/images/index/add.png">';	
               $str.='</div></div>';
			   $str.='<div class="ser_buy">';
               $str.='<img onclick="buy('.$res[$i]['id'].')" src="/farms/Public/Home/images/index/buy_button.png">';			   
			   $str.='</div></div></div>';					   
			}
		}else if($Post['type']=="exchange"){
		
		     for($i=0;$i<count($res);$i++){
				 $str.= '<div class="ser_box">';
				      $str.= '<div class="ser_type_text">'.$res[$i]['name'].'</div>';		
				      $str.= '<div class="ser_type" id="ex_'.($i+1).'" onclick="ex_ser('.$res[$i]['id'].')"><img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['name']).'.png"></div>';
				       if(!empty($res[$i]['exchange'])){
						  $need_seed = explode(',',$res[$i]['exchange']);
						  $str.= '<div class="ser_num"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($need_seed[0]).'.png"><span>'.$need_seed[1].'</span></div></div>';	
				       }else{
						   $str.= '';	 
					   }
			 }
	    }else{
			for($i=0;$i<count($res);$i++){

				$str.='<div class="shop_body_box">';
				$str.='<div class="shop_body_list">';
				$str.='<div class="body_list_left">';
				$str.='<img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['name']).'.png"></div></div>';
				$str.='<div class="shop_body_list list_center">';
				$str.='<p>';
				if($res[$i]['name']=="种子"){
					$str.=$res[$i]['name'].'x1000';
					/*$str.='</p><p>'.$res[$i]['note'].'</p><P style="color:#f00">剩余:'.$res[$i]['num'].'</P></div>';*/
					$str.='</p><p>'.$res[$i]['note'].'</p></div>';
				}else{
					$str.=$res[$i]['name'];
					$str.='</p><p>'.$res[$i]['note'].'</p></div>';
				}   
				$str.='<div class="shop_body_list">';
				$str.='<div class="list_input"><input class="shop_number_'.$res[$i]['id'].'" type="number" value="1" oninput="if(value.length>5)value=value.slice(0,5)"><img src="/farms/Public/Home/images/index/shop_number.png"></div>';
				$str.='<div class="list_input">';
				$str.='<div class="list_img"><img onclick="count(\'shop\',1,'.$res[$i]['id'].')" src="/farms/Public/Home/images/index/drop.png"></div>';
				$str.='<div class="list_img"><img onclick="count(\'shop\',2,'.$res[$i]['id'].')" src="/farms/Public/Home/images/index/add.png"></div>';
				$str.='</div></div>';
				$str.='<div class="shop_body_list list_button">';
				$str.='<div class="body_list_purchase">';
				$str.='<img onclick="buy('.$res[$i]['id'].')" src="/farms/Public/Home/images/index/buy_button.png"></div>';
				if($Post['type']=="treasure"){
					$str.='<div class="body_list_purchase"><div class="body_list_img"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($res[$i]['buy']).'.png"></div>';
				}else{
					$str.='<div class="body_list_purchase"><div class="body_list_img"><img src="/farms/Public/Home/images/index/'.image_name_icover($res[$i]['buy']).'.png"></div>';
				}
				$str.='<div class="body_list_number"><span class="need_'.$res[$i]['id'].'">'.$res[$i]['price'].'</span></div>';
				$str.='<input type="hidden" id="buy_'.$res[$i]['id'].'" value="'.$res[$i]['buy'].'"/>';
				$str.='</div></div></div>';
            }   
		}

        //$mem->add($Post['type'],$str);
	    echo json_encode($str);	
    }

    public function ranking(){
		if(IS_AJAX){
			//过滤数据
			$Post = I("post.");
            $page = $Post['page'];
			//验证数据
			if(is_numeric($page) || $_COOKIE['login_user']!==""){
				  //开启memcached
				//$mem=new \Memcached;
				//$mem->addServer('localhost',11211);

				//$mem->delete('rank');
				//var_dump($mem->get('rank'));
   			    //exit;
				
				//if($mem->get('rank')==""){

					$statistical = M('statistical');
					$for = $statistical->field(true)->select();
					$arr = array();
					for($i=0;$i<count($for);$i++){
						$res = $statistical->where('id='.$for[$i]['id'].'')->field(true)->select();
						$members = M(''.$res[0]['name'].'_members');
						$list = $members->order('level desc')->field('user,nickname,level,diamond')->select();
						$arr[$i] = $list;
					}
					$list = array();
					$temp = 0;
					for($i=0;$i<count($arr);$i++){
						for($j=0;$j<count($arr[$i]);$j++) {
							if($arr[$i][$j]['user']!=='13668281892' && $arr[$i][$j]['user']!=='14747470001' && $arr[$i][$j]['user']!=='14747470002' &&
							 $arr[$i][$j]['user']!=='14747470003' && $arr[$i][$j]['user']!=='14747470004' && $arr[$i][$j]['user']!=='14747470005' &&
							  $arr[$i][$j]['user']!=='14747470006' && $arr[$i][$j]['user']!=='14747470007' && $arr[$i][$j]['user']!=='14747470008' &&
							   $arr[$i][$j]['user']!=='14747470009' && $arr[$i][$j]['user']!=='14747470010' && $arr[$i][$j]['user']!=='14747470011' &&
							    $arr[$i][$j]['user']!=='14747470012' && $arr[$i][$j]['user']!=='14747470013' && $arr[$i][$j]['user']!=='14747470014' &&
								 $arr[$i][$j]['user']!=='14747470015' && $arr[$i][$j]['user']!=='14747470016' && $arr[$i][$j]['user']!=='17898108001' &&
								  $arr[$i][$j]['user']!=='17898108002' && $arr[$i][$j]['user']!=='17898108003' && $arr[$i][$j]['user']!=='17898108004' 
							){
								$list[$temp] = $arr[$i][$j];
								$temp++;
							}
							
						}
					}

					$orderBy = array('level'=>'desc','diamond'=>'desc');
					$der = resultOrderBy($list,$orderBy);
					$rank = array();
					for($o=0;$o<100;$o++){
						$rank[$o] = $der[$o];
					}

					$many = array();
					for($l=0;$l<count($rank);$l++){
						if($rank[$l]!==null){
							$many[$l]=$rank[$l];
						}
					}
					//$mem->add('rank',$many,7200);
				//}

                /*if(session('my_rank')==null){
                  foreach($mem->get('rank') as $key=>$val){
                     if($val['user'] == $_SESSION['user'] || $val['user'] == $_COOKIE['login_user']){
                         session('my_rank',$key+1) ;
                     }
                  }
                }
			
				//$page_count = ceil(count($mem->get('rank'))/10);		
				//$res = array_slice($mem->get('rank'),($page*10)-10,10);
				
				$page_count = ceil(count($mem->get('rank'))/10);		
				$res = array_slice($mem->get('rank'),($page*10)-10,10);*/
				
				
				///if(session('my_rank')==null){
                  foreach($many as $key=>$val){
                     if($val['user'] == $_SESSION['user']){
                         session('my_rank',$key+1) ;
                     }
                  }
                //}
				
				$page_count = ceil(count($many)/10);		
				$res = array_slice($many,($page*10)-10,10);

				if($res){
                    $str = "";
					for($i=0;$i<count($res);$i++){

						$str.='<ul>
								 <li>'.($page*10-10+$i+1).'</li>
								 <li style="width:30%;margin-left:6%;overflow:hidden">'.($res[$i]['nickname']?$res[$i]['nickname']:"农场用户").'</li>
								 <li style="width:7%;margin-left:7%">'.$res[$i]['level'].'</li>
								 <li style="margin-left:12%">'.$res[$i]['diamond'].'</li>
							  </ul>';
					}
					$str.='<div class="switch_ranking">
								<div class="switch_rankingbox">
								  <div class="personal_ranking"><span>';

                    $str .= (session('my_rank')==null) ? "未上榜" : session('my_rank');

					$str.='</span></div>
							  <div class="page_frame">';

                    switch($page <= 1){
                        case true:
                            $str.='<div class="previous_page" ></div>';
                            break;
                        default:
                            $str.='<div class="previous_page" onclick="page(\'pre\')"></div>';
                            break;
                    }
//                     $str.='<div class="previous_page" onclick="page(\'pre\')"></div>';

					 $str.='<div class="current_page"><h8><span>'.$page.'</span>/<span>'.$page_count.'</span></h8></div>';

                    switch($page >= $page_count){
                        case true:
                            $str.='<div class="previous_page"></div>';
                            break;
                        default:
                            $str.='<div class="previous_page"  onclick="page(\'next\')"></div>';
                            break;
                    }

                     $str.='</div>
							</div>
						  </div>';	  
				}else{
					echo '';
				}
				echo json_encode($str);
				die;	
			}
		}
    }

    public function log(){
              
		if(IS_AJAX){
			//过滤
			$Post = I('post.');
		    $page = $Post['page'];
			//验证数据
			if(is_numeric($page) || $_COOKIE['login_user']!==""){
			
				 //开启memcached
				 //$mem=new \Memcached;
				 //$mem->addServer('localhost',11211);

				 if($_SESSION['user']){
					 $user = $_SESSION['user'];
					 $table_fix = substr($_SESSION['user'],0,3);
				 }else if($_COOKIE['login_user']){
					 $user = $_COOKIE['login_user'];
					 $table_fix = substr($_COOKIE['login_user'],0,3);
				 }

                //$mem->delete($user.'lg');
				//var_dump($mem->get('lg'));
			    //exit;

                //if($mem->get($user.'lg')=="" || $mem->get($user.'lg')==null){

                    $shop_record = $table_fix."_record_shop";
                    $conversion_record = $table_fix."_record_conversion";
					$order_record = $table_fix."_order";
					$planting_record = $table_fix."_planting_record";
					$winning_record = $table_fix."_winning_record";

                    $nowtime = time();
                    $pre_week_time = time()-7*24*3600;
                    $Tool = new Tool();
                    $pre_week_time = $Tool->time($pre_week_time);
					
                    $shop_list = M("$shop_record")->where('buy_time>="'.$pre_week_time.'" and buy_time<="'.$nowtime.'" and user='.$user)->select();
                    $conversion_list = M("$conversion_record")->where('buy_time>="'.$pre_week_time.'" and buy_time<="'.$nowtime.'" and user='.$user." and type in ('m','d','f','g','c')")->select();
					$topup_list = M("$order_record")->field('pay_time as buy_time,money')->where('pay_time>="'.$pre_week_time.'" and pay_time<="'.$nowtime.'" and state=1 and pay_cash=1 and user='.$user)->select();
                    $planting_list = M("$planting_record")->field('harvest_time as buy_time,harvest_num,seed_type,user')->where('harvest_time>="'.$pre_week_time.'" and harvest_time<="'.$nowtime.'" and user='.$user)->select();
					$winning_list = M("$winning_record")->field('time as buy_time,name,seed,num')->where('time>="'.$pre_week_time.'" and time<="'.$nowtime.'" and user='.$user)->select();
					
					($shop_list!==null)?$shop_list:$shop_list=array();
					($conversion_list!==null)?$$conversion_list:$conversion_list=array();
					($topup_list!==null)?$topup_list:$topup_list=array();
					($planting_list!==null)?$planting_list:$planting_list=array();
                    ($winning_list!==null)?$winning_list:$winning_list=array();
				
                    //合并数组
                    $log_data = array_merge($shop_list,$conversion_list,$topup_list,$planting_list,$winning_list);
                    //排序
                    array_multisort(i_array_column($log_data,'buy_time'),SORT_DESC,$log_data);

                    //$mem->add($user.'lg',$log_data,7200);

                //}

				//$page_count = ceil(count($mem->get($user.'lg'))/6);
				//$res = array_slice($mem->get($user.'lg'),($page*6)-6,6);

				
				$page_count = ceil(count($log_data)/6);
				$res = array_slice($log_data,($page*6)-6,6);

				if($res){
					$str = '';
					for($i=0;$i<count($res);$i++){
						$str .= '<div class="market_list_body"><span>';
						$str .= date('n',$res[$i]['buy_time']).'/'.date('j',$res[$i]['buy_time']);
						if($res[$i]['type']=='b'){
							$str .= '&nbsp;&nbsp;购买了'.$res[$i]['name'].' x '.$res[$i]['num'];
						}else if($res[$i]['type']=='' || !isset($res[$i]['type'])){
								if(isset($res[$i]['harvest_num'])){
									$str .= '&nbsp;&nbsp;收获了'.$res[$i]['seed_type'].'x'.$res[$i]['harvest_num'];
								}else if(isset($res[$i]['seed'])){								
							        $str .= '&nbsp;&nbsp;'.$res[$i]['name'].'获得'.$res[$i]['seed'].'x'.$res[$i]['num'];
							    }else{
									$str .= '&nbsp;&nbsp;充值了'.$res[$i]['money'];
								}
						}else if($res[$i]['type']=='d'){							
							 $str .= '&nbsp;&nbsp;兑换了'.$res[$i]['diamond'].'个宝石';
						}else if($res[$i]['type']=='m'){
						  	 $str .= '&nbsp;&nbsp;兑换了'.$res[$i]['name'].' x '.$res[$i]['num'];
						}else if($res[$i]['type']=='f'){
							 $str .= '&nbsp;&nbsp;第'.$res[$i]['num'].'块地施了肥';
						}else if($res[$i]['type']=='g'){
							 $str .= '&nbsp;&nbsp;修改账户信息，扣除100金币';
						}else if($res[$i]['type']=='c'){
							 $str .= '&nbsp;&nbsp;重生消耗'.$res[$i]['name'].$res[$i]['num'].',获得种子'.$res[$i]['attach'];
						}else if($res[$i]['type']=='h'){
							 $str .= '&nbsp;&nbsp;'.$res[$i]['price']*$res[$i]['num'].'个'.$res[$i]['articles'].'兑换了'.$res[$i]['name'].'x'.$res[$i]['num'];
						}
						$str .= '</span></div>';
					}

					$str .= '<div class="market_body">';
                    switch($page <= 1){
                        case false:
                            $str .= '<div class="market_left" onclick="page(\'pre\')"></div>';
                            break;
                        default:
                            $str .= '<div class="market_left" ></div>';
                            break;
                    }           //  上一页
					$str .= '			<div class="market_center"><span>'.$page.'/'.$page_count.'</span></div>';
                    switch($page >= $page_count){
                        case true:
                            $str .= '<div class="market_left"></div>';
                            break;
                        default:
                            $str .= '<div class="market_left" onclick="page(\'next\')" style="margin-left: 0%"></div>';
                            break;
                    }//  下一页
					$str .= '</div>';


				}else{
					 $str = '<div class="market_list_body" ><p style="padding: 10px;">暂无日志</p></div>';
				}

				echo json_encode($str);
				die;
			}
		}
    }

    function service(){
		
		if(IS_AJAX && I('post.token')==session('token')){
			
			$fix = substr(session('user'),0,3);
		    $table = $fix.'_managed_to_record';
		    $service_data = array('草灾守护','虫灾守护','旱灾守护','丰收之心','稻草人');
            $res = M("$table")->where('user="'.$_SESSION['user'].'" and state=0 and end_time>'.time())->select();
            
            if($res){
				$str = '';

				for($i=0;$i<count($res);$i++){
				    $surplus_time = $res[$i]['end_time']-time();
					$hour = $surplus_time/3600;
					$hour = floor($hour);
					$min = ($surplus_time-$hour*3600)/60;
					$min = floor($min);
					$str.= '<p>'.$service_data[$res[$i]['service_type']-1].'：剩余'.$hour.'小时'.$min.'分<p>';
				}

				//echo json_decode($str);
				echo $str;
			}else{
			   echo  $str.= '<p>你还未购买任何服务<p>';;
			}			
		}else{
			echo '';
		}		 	
	}	
}
?>
