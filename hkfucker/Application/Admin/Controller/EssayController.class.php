<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 18:13
 */
namespace Admin\Controller;
use Think\Controller;
class EssayController extends AdminController
{
	/**
	*列表页
	**/
    public function index(){
        $notice = M('notice');
        $id=1;
        $count = $notice->where("id>=%d",array($id))->count();
        $p=intval(I('get.p',1,'addslashes'));
        $num =6;
        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
//        if(IS_POST){
//            $p =I('post.p','','int');
//        }else{
//            $p =I('get.p',1,'int');
//        }
        if($p!==null){
            $p=$p;
        }else{
            $p=1;
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
        $this->assign('start',$start);//分页
        $this->assign('end',$end+1);//分页

        $this->assign('p',$p);
        $list =$notice->field(true)->where('id>=%d',array($id))->order('time DESC')->page($p.','.$num)->filter('strip_tags')->select();
        $this->assign('notice', $list);// 赋值数据集
        //var_dump($count);
		//var_dump($list[0]['type']);
        $this->display();

    }
    
    /**
	*内容页
	**/
    public function index_content(){
        $id = intval(I("get.id"));
        if($id==0){
            echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
            echo '<script> alert("不要搞事情！"),history.back(); </script>';
            exit();
        }else{
            $notice = M('notice');
            $res = $notice
                ->where('id=%d',array($id))
                ->field(true)
                ->filter('strip_tags')
                ->select();
            $this->assign('notice',$res);
            $this->display('index_content');
        }
    }

	/**
	*删除公告
	**/
    public function del(){
        if(IS_AJAX){
            $id = intval(I("post.id"));
            //print_r($id);die;
            if($id==0){
                echo 0;
            }else{
                $notice = M("notice");
                if($notice->where('id= %d',array($id))->delete()){
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }else{
            echo -1;
        }
    }

	
    public function audit(){
        if(!empty($_GET)){
            $id = intval(I("get.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("不要搞事情！"),history.back(); </script>';
                exit();
            }else{
                //$id = I('get.id');
                //var_dump($id);die;
                //echo $id,$conm['poundage_value'];die;
                $notice = M('notice');
                //$conm['type'] = 1;
                $notice->type = 1;
                if($notice->where('id=%d',array($id))->filter('strip_tags')->save()!==false){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加成功！"); </script>';
                    echo "<script> window.location.href='".U('Essay/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加失败！"),history.back(); </script>';
                    exit();
                }
            }
        }
    }

	/**
	*添加公告
	**/
    public function notice_add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Essay/index');
                return;
            }
            if($_POST['link']==''){
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize   =     20971115200 ;// 设置附件上传大小
				$upload->exts      =     array('mkv','mp4','jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath  =     'Public/Home/Uploads/'; // 设置附件上传根目录
				//$upload->rootPath  =     'ksnc/Public/Home/Upload/'; // 设置附件上传根目录
				$upload->savePath  =     ''; // 设置附件上传（子）目录
				// 上传文件
				$info   =   $upload->upload();
                //echo $info;die;
				if($info==false){
					$notice=M('notice');
					$notice->create();
					$notice->title=I('post.title');
					$notice->content=htmlspecialchars(I('post.content'));
					$notice->mv=I('post.link');
					//var_dump($info['mv']['savename']);die;
					$notice->time=time();
					if($notice->add()){
                        //echo $notice->GetLastsql();die;
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加成功！"),history.back(); </script>';
						exit();
					}else{
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加失败！"),history.back(); </script>';
						exit();
					}
				}else{
					if(!$info) {// 上传错误提示错误信息
						$this->error($upload->getError());
					}else{// 上传成功
						$info;
					}

					$notice=M('notice');
					$notice->create();
					$notice->title=I('post.title');
					$notice->content=htmlspecialchars(I('post.content'));
					$notice->pic=$info['pic']['savename'];

					$notice->mv=$info['mv']['savename'];
					//var_dump($info['mv']['savename']);die;
					$notice->time=time();
					$notice->day=trim( $info['pic']['savepath'],'/');
					if($notice->add()){
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加成功！"),history.back(); </script>';
						exit();
					}else{
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加失败！"),history.back(); </script>';
						exit();
					}
				}


			}else{
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize   =     31457288 ;// 设置附件上传大小
				$upload->exts      =     array('mkv','mp4','jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath  =     'Public/Home/Uploads/'; // 设置附件上传根目录
				$upload->savePath  =     ''; // 设置附件上传（子）目录
				// 上传文件
				$info   =   $upload->upload();
				if($info==false){
					$notice=M('notice');
					$notice->create();
					$notice->title=I('post.title');
					//var_dump(I('post.title'));die;
					$notice->content=htmlspecialchars(I('post.content'));
					$notice->mv=I('post.link');
					$notice->time=time();
					if($notice->add()){
					    //echo $notice->GetLastsql();die;
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加成功！"),history.back(); </script>';
						exit();
					}else{
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加失败！"),history.back(); </script>';
						exit();
					}
				}else{
					if(!$info) {// 上传错误提示错误信息
						$this->error($upload->getError());
					}else{// 上传成功
						$info;
					}

					$notice=M('notice');
					$notice->create();
					$notice->title=I('post.title');
					//var_dump(I('post.title'));die;
					$notice->content=htmlspecialchars(I('post.content'));
					$notice->pic=$info['pic']['savename'];
					$notice->mv=I('post.link');
					$notice->time=time();
					$notice->day=trim( $info['pic']['savepath'],'/');
					if($notice->add()){
                        //echo $notice->GetLastsql();die;
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加成功！"),history.back(); </script>';
						exit();
					}else{
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("添加失败！"),history.back(); </script>';
						exit();
					}
				}
			}
        }else{
            creatToken();
            $this->display('notice_add');
        }
    }

    public function upload(){

    }
}