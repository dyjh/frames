<?php

/*返回码*/
/*-2 封号状态*/
/*1 登陆成功*/
/*-1 请求错误*/
/*0 帐号密码有误*/

namespace Org\Our;
use Think\Autoadd;
use Think\Model;
//用户操作类
class Admin{
    public function Set_Level($user){
		//  2017年10月11日15:30:02 
		//  左联 用户可提现金币数
        // $date['_string']=' (user like "%'.$user.'%")  OR ( num_id like "%'.$user.'") OR ( name like "%'.$user.'")  OR ( nickname like "%'.$user.'")';
        $sta = M('statistical')->field('name')->filter('strip_tags')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_members';
				$date['_string']=' ('.$sqlname.'.user like "%'.$user.'%")  OR ( '.$sqlname.'.num_id like "%'.$user.'") OR ( name like "%'.$user.'")  OR ( nickname like "%'.$user.'%")';
				$join = " left join ".$v."_users_gold as ug on ug.user=$sqlname.user";
				$field = "$sqlname.* ,( ug.user_fees + ug.buy_and_sell ) as cash_coin";
			    $sqllist = M($sqlname)->order('level DESC')->field($field)->join($join)->where($date)->filter('strip_tags')->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }

    public function Set_Prop($user){
        $date['user']=array("LIKE",'%'.$user.'%');
        $sta = M('statistical')->field('name')->filter('strip_tags')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_prop_warehouse';
                $sqllist = M($sqlname)->where($date)->filter('strip_tags')->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }

    public function Set_Prop_Edit($data){
        $sqluser = substr($data['user'], 0, 3);

        $sqlname = ''.$sqluser.'_prop_warehouse';
        $sqllist = M($sqlname)->where($data)->select();
        return $sqllist;
    }

    public function Set_Order($data){
        if($data['start_user'] ){
         $date['user']       =array("LIKE",'%'.$data['start_user'].'%');
				 //$date['_string']=' (user like "%'.$data['start_user'].'%") OR ( name like "%'.$data['start_user'].'")';
        }

        $date['pay_cash']  = $data['pay_cash'] ? $data['pay_cash'] : 1;

        switch ($data['state']){
            case "0":
                $date['state']   = 0;
                break;
            case "1,9":
                $date['state']   = array("in",(    explode(",",$data['state'] ) ));
                break;
            case "2":
                $date['state']   = 2;
                break;
            case "all":
                // $date['state']   = "";
				unset($date['stste']);
                break;
            default;
        }



        $sta = M('statistical')->field('name')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_order';
                $sqllist = M($sqlname)->order('id DESC')->where($date)->select();
                foreach ($sqllist as $a=>$b){
                    
										if($b['money']<=499){
											$b['hook'] = ($b['money']-($b['money']*0.029));
										}else if($b['money']<=999){
											$b['hook'] = ($b['money']-($b['money']*0.025));
										}else{
											$b['hook'] = ($b['money']-($b['money']*0.02));
										}
										$List[] = $b;
                }
            }
        }
		// ECHO M($sqlname)->getLastSql();
        return $List;
    }
	
	public function Set_ppg($user){
        $date['_string']=' (user like "%'.$user.'%")';
        $sta = M('statistical')->field('name')->filter('strip_tags')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_fruit_record';
                $sqllist = M($sqlname)->where($date)->order('seed asc')->filter('strip_tags')->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }
	
    public function Set_Zrsz($user){
        //$date['user']=array("LIKE",'%'.$user.'%');
		//$data['seed_state'] > 3;
        $sta = M('statistical')->field('name')->filter('strip_tags')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_planting_record';
                $sqllist = M($sqlname)->where('seed_state > 3')->filter('strip_tags')->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }
	
	public function seed_level($level){
		$f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m=M($cases)->where('level ='.$level)->order('level')->select();
			$count=M($cases)->where('level ='.$level)->order('level')->count();
            for($i=0;$i<$count;$i++){
				$user=$data_m[$i]['user'];
						$input[$f]=$data_m[$i];
						$f++;
            }
        }
		
        $columnKey='level';
        array_multisort(i_array_column($input,$columnKey),SORT_ASC,$input);   //数组排序
        $seeds=array();
        $arr=array();
		$guolv = array(18382050570,18228068397,18780164595,18768477519,18584084806,15802858094,13308081857,15140491373,15008210274,14747470001,14747470002,14747470003,14747470004,14747470005,14747470006,14747470007,14747470008,14747470009,14747470010,14747470011,14747470012,14747470013,14747470014,14747470015,14747470016);
		
		
		foreach($input as $key=>$val){
			if(in_array($val['user'],$guolv)){
					continue;
			}
			 $sqluser = substr($val['user'], 0, 3);
             $sqlname = ''.$sqluser.'_seed_warehouse';
			 $prop = ''.$sqluser.'_prop_warehouse';
             $caomei = M($sqlname)->where('user="'.$val['user'].'" AND seeds="草莓"')->sum('num');
             $tudou = M($sqlname)->where('user="'.$val['user'].'" AND seeds="土豆"')->sum('num');
             $daomi = M($sqlname)->where('user="'.$val['user'].'" AND seeds="稻米"')->sum('num');
             $yingtao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="樱桃"')->sum('num');
             $putao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="葡萄"')->sum('num');
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
		}
		$seeds['zongshu']=$seeds['caomei']+$seeds['tudou']+$seeds['daomi']+$seeds['yingtao']+$seeds['putao']+$seeds['fanqie']+$seeds['boluo'];
		return $seeds;
	}
	
	public function seed_level_gg($level){
		$f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_members';
			$data_m=M($cases)->where('level ='.$level)->order('level')->select();
			$count=M($cases)->where('level ='.$level)->order('level')->count();
            for($i=0;$i<$count;$i++){
				$user=$data_m[$i]['user'];
						$input[$f]=$data_m[$i];
						$f++;
            }
        }
		
        $columnKey='level';
        array_multisort(i_array_column($input,$columnKey),SORT_ASC,$input);   //数组排序
        $seeds=array();
        $arr=array();
		$guolv = array(18382050570,18228068397,18780164595,18768477519,18584084806,15802858094,13308081857,15140491373,15008210274,14747470001,14747470002,14747470003,14747470004,14747470005,14747470006,14747470007,14747470008,14747470009,14747470010,14747470011,14747470012,14747470013,14747470014,14747470015,14747470016,17628281862,18382077208,13882139257,18628282865,13086631981,15908144678,18502838021);
		
		
		foreach($input as $key=>$val){
			if(in_array($val['user'],$guolv)){
					continue;
			}
			 $sqluser = substr($val['user'], 0, 3);
             $sqlname = ''.$sqluser.'_seed_warehouse';
			 $prop = ''.$sqluser.'_prop_warehouse';
             $caomei = M($sqlname)->where('user="'.$val['user'].'" AND seeds="草莓"')->sum('num');
             $tudou = M($sqlname)->where('user="'.$val['user'].'" AND seeds="土豆"')->sum('num');
             $daomi = M($sqlname)->where('user="'.$val['user'].'" AND seeds="稻米"')->sum('num');
             $yingtao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="樱桃"')->sum('num');
             $putao = M($sqlname)->where('user="'.$val['user'].'" AND seeds="葡萄"')->sum('num');
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
		}
		$seeds['zongshu']=$seeds['caomei']+$seeds['tudou']+$seeds['daomi']+$seeds['yingtao']+$seeds['putao']+$seeds['fanqie']+$seeds['boluo'];
		return $seeds;
	}
	
	public function arr_num($arr){
		$count = count($arr);
		$f=0;
		for($i=0;$i<$count;$i++){
			switch ($arr[$i]['level']){
            case "1":
                $date['yi'] += 1;
                $date['yi_num'] += $arr[$i]['num'];
                break;
			case "2":
                $date['er'] += 1;
                $date['er_num'] += $arr[$i]['num'];
                break;
			case "3":
                $date['san'] += 1;
                $date['san_num'] += $arr[$i]['num'];
                break;
			case "4":
                $date['si'] += 1;
                $date['si_num'] += $arr[$i]['num'];
                break;
			case "5":
                $date['wu'] += 1;
				$date['wu_num'] += $arr[$i]['num'];
                break;
			case "6":
                $date['liu'] += 1;
				$date['liu_num'] += $arr[$i]['num'];
                break;
			case "7":
                $date['qi'] += 1;
				$date['qi_num'] += $arr[$i]['num'];
                break;
			case "8":
                $date['ba'] += 1;
				$date['ba_num'] += $arr[$i]['num'];
                break;
			case "9":
                $date['jiu'] += 1;
				$date['jiu_num'] += $arr[$i]['num'];
                break;
			case "10":
                $date['shi'] += 1;
				$date['shi_num'] += $arr[$i]['num'];
                break;
			case "11":
                $date['sy'] += 1;
				$date['sy_num'] += $arr[$i]['num'];
                break;
			case "12":
                $date['se'] += 1;
				$date['se_num'] += $arr[$i]['num'];
                break;
            default;
        }
			$f++;
            }
	$date['renshu'] = $date['yi']+$date['er']+$date['san']+$date['si']+$date['wu']+$date['liu']+$date['qi']+$date['ba']+$date['jiu']+$date['shi']+$date['sy']+$date['se'];
	$date['zongshu'] = $date['yi_num']+$date['er_num']+$date['san_num']+$date['si_num']+$date['wu_num']+$date['liu_num']+$date['qi_num']+$date['ba_num']+$date['jiu_num']+$date['shi_num']+$date['sy_num']+$date['se_num'];
	return $date;
	}
	
	public function Set_gudong($data){
        $sqluser = substr($data, 0, 3);

        $sqlname = ''.$sqluser.'_seed_warehouse';
        $proname = ''.$sqluser.'_prop_warehouse';
        $name = ''.$sqluser.'_members';
		$seeds=array();
		$tudou= M($sqlname)->where('user="'.$data.'" AND seeds="土豆"')->sum('num');
		$caomei = M($sqlname)->where('user="'.$data.'" AND seeds="草莓"')->sum('num');
		$yingtao = M($sqlname)->where('user="'.$data.'" AND seeds="樱桃"')->sum('num');
		$daomi = M($sqlname)->where('user="'.$data.'" AND seeds="稻米"')->sum('num');
		$fanqie = M($sqlname)->where('user="'.$data.'" AND seeds="番茄"')->sum('num');
		$putao = M($sqlname)->where('user="'.$data.'" AND seeds="葡萄"')->sum('num');
		$boluo = M($sqlname)->where('user="'.$data.'" AND seeds="菠萝"')->sum('num');
		$zhongzi = M($proname)->where('user="'.$data.'" AND props="种子"')->sum('num');
		$user = M($name)->where('user='.$data)->find();
		$seeds['user']=$user['nickname'];
		$seeds['caomei']=$seeds['caomei']+$caomei;
		$seeds['tudou']=$seeds['tudou']+$tudou;
		$seeds['daomi']=$seeds['daomi']+$daomi;
		$seeds['yingtao']=$seeds['yingtao']+$yingtao;
		$seeds['putao']=$seeds['putao']+$putao;
		$seeds['fanqie']=$seeds['fanqie']+$fanqie;
		$seeds['boluo']=$seeds['boluo']+$boluo;
		$seeds['zhongzi']=$seeds['zhongzi']+$zhongzi;
		$seeds['zongshu']=$seeds['caomei']+$seeds['tudou']+$seeds['daomi']+$seeds['yingtao']+$seeds['putao']+$seeds['fanqie']+$seeds['boluo'];
        return $seeds;
    }

}
