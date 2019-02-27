<?php
namespace Org\Our;
use Think\Model;
use Org\Our\Record;
use Org\Our\Pay;
use Org\Our\archive;
//金币、宝石消费类
class Consume{

	//金币兑换宝石
	public function Gem_For($list){
		
        $model = new Model();
        $model->startTrans();
		
        $table_fix = substr($_SESSION['user'],0,3);
        $table = $table_fix.'_members';
        $user_message = M("$table")->where('user="'.$_SESSION['user'].'"')->field('user,coin,diamond')->find();
        if($user_message['coin']>=$list['coins']){
            if(M("$table")->where('user="'.$_SESSION['user'].'"')->setDec('coin',$list['coins']) && M("$table")->where('user="'.$_SESSION['user'].'"')->setInc('diamond',$list['coins']*100)){
                      $array['user'] = session('user');
                      $array['coin'] = $list['coins'];
                      $array['diamond'] = $list['coins']*100;
                      $array['type'] = 'd';
                      $record = new Record();
					  $archive = new archive();
					  $archive->store(session('user'),2,$list['coins']);
					  //返佣
					  $charge = new Pay();
					  $tel = session('user');
					  $sum = $list['coins'];
					  $exchange = $charge->recharge($tel,$sum);
					  if($record->Record_Conversion($array)){
						$model->commit();
						$data['state'] = 60011;
						$data['content'] = '兑换成功';
						echo json_encode($data);
						exit;
                      }else{
                          $model->rollback();
                          $data['state'] = 60010;
                          $data['content'] = '记录修改失败';
                          echo json_encode($data);
                          exit;
                      }
            }else{
                $model->rollback();
                $data['state'] = 60007;
                $data['content'] = '兑换失败，金币修改失败';
                echo json_encode($data);
                exit;
            }
        }else{
            $data['state'] = 60006;
            $data['content'] = '金币不足！';
            echo json_encode($data);
            exit;
        }
    }

    //宝石购买道具
    public function Gem_Consume($data,$id){
		
        $saveuser = substr($data['user'],0,3);
        $savetable = $saveuser.'_members';
		$saveznote = $data['num']*$data['price'];
		$note = $id == 6?$saveznote/1000:$saveznote;
		$user_shop = $saveuser.'_record_shop';
		
		/*if($id==4){
		$start = 1503898200;
		$end = 1504071000;
		$count = M("$user_shop")->where('user='.$data['user'].' and buy_time>='.$start.' and buy_time<='.$end.' and name="肥料"')->sum('num');
		if($count>=777){
			$note = $saveznote;
			}else{
			$note = $saveznote/2;
			}
		}*/
        //扣去宝石，交易完成
        if(M("$savetable")->where('user="'.$data['user'].'"')->setDec('diamond',$note)){
            return true;
        }else{
            return false;
        }
    }


}
