<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Alert;
use Org\Our\Pay;
use Org\Our\Exchange;
use Org\Our\Deal;
class GameController extends HomeController {

    public function _initialize(){
        //先运行一次父类的构造方法
        $this->assign('nav_titels',"攻略资料");
    }

    public function raiders(){
        //echo $_SERVER['HTTP_REFERER'];die;

        //进入页面
        $raiders = M('raiders');

        $label=M('label');
		
        // 所有标签
        $ros = $label->where('label_status=0')->select();
		
        $num = $raiders->distinct(true)->where("num_id>0")->field('num_id')->select();
        $this->assign('label',$ros);
        $this->assign('num_id',$num);
	// print_r($num);
        //////////////////////

        $NewType = intval($_GET['addtime']);
		
		$order_rule['listorder']	   = "desc";

        switch($NewType){
            case 1:
                $order_rule['addtime'] = "asc";
                break;
			case 2:
                $order_rule['addtime'] = "desc";
                break;
            default: 
				$order_rule['addtime'] = "desc";
                break;
        }

        $RewardType = intval($_GET['reward_type']);
		//var_dump($RewardType);die;
        switch($RewardType){
            case 1:
                $order_rule['reward'] = "asc";
                break;
			case 2:
                $order_rule['reward'] = "desc";
                break;
            default: 
				$order_rule['reward'] = "desc";
                break;
        }

        if($_GET['is_free']){
            $form_where['is_free'] =  intval(lib_replace_end_tag($_GET['is_free']));
        }

        if($_GET['lablelist']){
            $form_where['lablelist'] = array("like","%".lib_replace_end_tag(I('get.lablelist','','strip_tags')."%"));
        }

        if($_GET['num_id']){
            $form_where['num_id'] = lib_replace_end_tag($_GET['num_id']);
        }

        $legal_parameter = array("is_free","lablelist","reward","num_id");

        $get_url_str = "&";
        foreach($_GET as $key=>$val){
            if(in_array($key,$legal_parameter) && $val){
                $get_url[$key] .= $key."=".lib_replace_end_tag($val);
            }
        }

        $get_url_str .= implode("&",$get_url);
        $form_where['is_show'] = 1;
        $count = $raiders->where($form_where)->count();

        $p = getpage($count,5);

        $list = $raiders
			->join('raiders_content on raiders.rid=raiders_content.rid',"left")
			->where($form_where)
			->order($order_rule)
			->group("raiders.rid")
			->limit($p->firstRow, $p->listRows)
			->select();
		
        $this->assign('tip',$list);

        $this->assign('page', $p->show()); // 赋值分页输出

        if($_GET['lablelist']){
            $form_where['label_name'] = lib_replace_end_tag(I('get.lablelist','','strip_tags'));
        }

        $this->assign('get_url_str',$get_url_str);

        $this->assign('order_rule',$order_rule);

        $this->assign('form_where',$form_where);

        $this->assign('RewardType',$RewardType);

        $this->assign('NewType',$NewType);

        $this->display('raiders');

    }

    public function latest(){
        if(intval($_POST['type'])==1){
            $raiders = M('raiders');
            $tip = $raiders
                ->join('raiders_content on raiders.rid=raiders_content.rid')
                ->order('addtime asc')
                ->select();
            $this->ajaxReturn($tip);
        }
    }

    public function raiders_content(){
        //进入页面
		if(IS_GET){
			//var_dump(addslashes($_GET['rid']));die;
        $raiders = M('raiders');
        $list = $raiders
            ->where('rid='.intval($_GET['rid']).'')
            ->select();
        //var_dump($list);die;
        $raiders_content = M('raiders_content');
        $cont = $raiders_content
            ->where('rid='.intval($_GET['rid']).'')
            ->select();
        //var_dump($cont);
        $this->assign('cont',$cont);
        $this->assign('tip',$list);
        //阅读次数
        $where['readtime'] = $list[0]['readtime']+1;
        $raiders->where('rid='.intval($_GET['rid']).'')->save($where);
        /////
        $mname = $raiders
            ->field(true)
			->where('is_show=1 and mname="'.$_GET['mname'].'"')
            ->limit(4)
            ->select();
        $this->assign('mname',$mname);
        $seeds = M('seeds');
        $fruit = $seeds
            ->field(true)
            ->limit(6)
            ->select();
        $this->assign('fruit',$fruit);

        $raiders_reward = M('raiders_reward');
        $count = $raiders_reward->where('mid="'.lib_replace_end_tag($_GET['mname']).'"')->count();
        $p = getpage($count,10);
        $list = $raiders_reward
				->where('rid='.intval($_GET['rid']).'')
				->field(true)
				->limit($p->firstRow, $p->listRows)
				->order('addtime desc')
				->select();	
				
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->assign('record',$list);
        $this->display('raiders_content');
        //var_dump($list[0]['addtime']);
		}
    }

    public function reward(){
        if(!session('?login')){
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            echo "<script> alert('请先登录');</script>";
            $this->redirect('User/lyologin');
        }else {
			$model=new Model;
            $model->startTrans();
			
            if (substr(lib_replace_end_tag(I('post.Pwd','','strip_tags')),0,11) == $_SESSION['login']['user'] && I('post.source') !== $_SESSION['login']['user']) {
                $pr = substr($_SESSION['login']['user'], 0, 3);
                $members = M('' . $pr . '_members');
                $mem = $members
                    ->field('diamond,headimg')
                    ->where('user=' . $_SESSION['login']['user'] . '')
                    ->select();
				//
                if ($mem[0]['diamond'] >= lib_replace_end_tag(I('post.Number','','strip_tags'))) {
                    //$this->ajaxReturn(200);
                    $raiders_reward = M('raiders_reward');
                    $cond['rid'] = I('post.rid',0,'intval');
                    $cond['mid'] = lib_replace_end_tag(I('post.source','','strip_tags'));
                    $cond['raward_id'] = $_SESSION['login']['user'];
                    $cond['raward_num_id'] = $_SESSION['login']['num_id'];
                    $cond['money'] = lib_replace_end_tag(I('post.Number','','strip_tags'));
                    $cond['addtime'] = time();
                    $cond['top_pic'] = $mem[0]['headimg'];
					//
					$mond['diamond'] = $mem[0]['diamond']-lib_replace_end_tag(I('post.Number','','strip_tags'));//
					//
					$per = substr(lib_replace_end_tag(I('post.source','','strip_tags')), 0, 3);
					$minSql = M('' . $per . '_members')->field('diamond')->where('user='.lib_replace_end_tag(I('post.source','','strip_tags')).'')->select();
					$jpop['diamond'] = $minSql[0]['diamond']+lib_replace_end_tag(I('post.Number','','strip_tags'));
                    if ($raiders_reward->filter('strip_tags')->data($cond)->add() !== false 
					&& $members->where('user=' . $_SESSION['login']['user'] . '')->save($mond)!==false
					&& M('' . $per . '_members')->where('user='.lib_replace_end_tag(I('post.source','','strip_tags')).'')->save($jpop)!==false
					){
						$model->commit();
                        $raiders = M('raiders');
                        $res = $raiders->field('reward')->where('rid=' . I('post.rid',0,'intval') . '')->select();
                        $where['reward'] = $res[0]['reward'] + 1;
                        if ($raiders->filter('strip_tags')->where('rid=' . I('post.rid',0,'intval') . '')->save($where) !== false) {
                            $this->ajaxReturn(200);
                        } else {
                            $this->ajaxReturn(400);
                        }
                    } else {
						$model->rollback();
                        $this->ajaxReturn(400);
                    }
                } else {
                    $this->ajaxReturn(500);
                }

            } else {
                $this->ajaxReturn(300);
            }
        }
    }

    public function upload(){
        if(!session('?login')){
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            echo "<script> alert('请先登录');</script>";
            $this->redirect('User/lyologin');
        }else{
            if(IS_POST){
                //防止重复提交 如果重复提交跳转至相关页面
                if (!checkToken($_POST['TOKEN'])) {
                    $this->redirect('index/index');
                }
                if ( ! M()->autoCheckToken($_POST)){
                    // 令牌验证错误
                    $this->redirect("index/index");
                }
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     31457288 ;// 设置附件上传大小
                $upload->exts      =     array('jpg','gif','png','jpeg','bpm','tiff','psd');// 设置附件上传类型
                $upload->rootPath  =     'Public/Home/Upload/'; // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                // 上传文件
                $info   =   $upload->upload();				
                if($info!==false){
                    if($info['video']==null){
                        $up = count($info);
                    }else{
                        $up = count($info)-1;
                    }
                }else{
                    $up = 0;
                }				
                //print_r($_POST);die;
                //print_r($info);die;
                $text = count($_POST)-4;
                $count = $up+$text;
				
                $raiders=M('raiders');
                $conw['mname']=$_SESSION['login']['user'];
                $conw['title']=lib_replace_end_tag(I('post.title'));
                $conw['lablelist']=lib_replace_end_tag(I('post.lablelist'));
				if(I('post.text1')!=''){
					$conw['description']=lib_replace_end_tag(I('post.text1'));
				}
                $conw['addtime']=time();
                $conw['num_id']=$_SESSION['login']['num_id'];
				
				// $raiders->data($conw)->add();
				
				// var_dump($raiders->getDbError());die;
				
                if($raiders->data($conw)->add()!=false){
                    $last_insert_id = $raiders->getLastInsID();                   
                    $s_rid = $raiders->getLastInsID();                  
                    for($i=1;$i<=$count;$i++){
                        if($_POST['text'.$i.'']!=null || $info['img'.$i.'']['savename']!=null){
                            if($_POST['text'.$i.'']!=null){
                                $raiders_content=M('raiders_content');
                                $raiders_content->create();
                                $raiders_content->mid=$_SESSION['login']['user'];
                                $raiders_content->rid=$s_rid;
                                $raiders_content->content=lib_replace_end_tag(I('post.text'.$i.'','','strip_tags'));
                                if($raiders_content->filter('strip_tags')->add()!==false){
                                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                                }else{
                                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                                    echo '<script> alert("添加失败！"),history.back(); </script>';
                                }
                            }else{								
                                //var_dump($info['img'.$i.'']['savename']);die;
                                $raiders_content=M('raiders_content');
                                $raiders_content->create();
                                $raiders_content->mid=$_SESSION['login']['user'];
                                $raiders_content->rid=$s_rid;
                                $raiders_content->img=$info['img'.$i.'']['savename'];
                                $raiders_content->day=trim( $info['img'.$i.'']['savepath'],'/');
                                if($raiders_content->filter('strip_tags')->add()!==false){
                                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                                }else{
                                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                                    echo '<script> alert("添加失败！"),history.back(); </script>';
                                }
                            }
                        }

                    }
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加成功！");location="'.U('Game/raiders_content','rid='.$last_insert_id.'&mname='.$_SESSION['login']['user']).'"; </script>';
                    die;
                }else{
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加失败！"),history.back(); </script>';
                    exit();
                }
            }
            creatToken();
            $label=M('label');
            $res = $label->where('label_status=0')->select();
            $this->assign('label',$res);
            $this->display('upload');
        }
    }

    public function transform(){
        //$tel = '18046987982';
        $user = '18768477519';
		$num = 1000;
		$type = '土豆';
		$crit = 2;
		//
		$tel = '18768477519';
		//$sum =  '1.234';
		$sum =  '20';
        //$yii = new Exchange();
        //$hi = $yii->test_exchange($user,$num,$type,$crit);
        //$hi = $yii->exchange($tel,$sum);
        //$hi = $yii->conductor($tel,$sum);
		
		//$yii = new Deal();
		//$hi = $yii->deal($tel,$sum);
		
		$yii = new Pay();
		//$hi = $yii->manager($tel);
		$hi = $yii->recharge($tel,$sum);
		
		
		var_dump($hi);

    }


 

}