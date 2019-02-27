<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 14:33
 */
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends AdminController
{
	/**
	*商品列表
	**/
    public function index(){
        $shop = M('shop');
        $where = "id>=1";
        $count = $shop->where($where)->count();
        $num =6;
        $pages = ceil($count/$num);
        $p=intval(I('get.p',1,'addslashes'));
        //$this->assign('pages',$pages+1);
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
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页

        $this->assign('p',$p);
        $list =$shop->field(true)->where($where)->order('id')->page($p.','.$num)->select();

        $this->assign('shop',$list);


        //var_dump($res);
        $this->display();
    }
	/**
	*商品删除
	**/
    public function del(){
        if(IS_AJAX){
            $id=intval(I('post.id',0,'addslashes'));
            if($id==0){
                //print_r($id);die;
                $seed=M('Shop');
                $data=$seed->where('id=%d',array($id))->find();
                //print_r($data);
                if($data['type']==1){
                    echo 2;
                }else{
                    if($seed->where('id=%d',array($id))->delete()){
                        echo 1;
                    }else{
                        echo 0;
                    }
                }
            }else{
                echo -1;
            }
        }else{
            echo -1;
        }
    }

	
	/**
	*商品数据修改
	**/
    public function cost(){
        if(!empty($_POST)){
            //echo 2;die;
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Good/index');
                return;
            }
            $data['num']=_safe(I('post.num'));
            $data['price']=_safe(I('post.price'));
            $data['note']=_safe(I('post.note'));
            $data['buy']=_safe(I('post.buy'));
            $data['id']=intval(I('post.id',0,'addslashes'));
            if($data['id']==0){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("参数错误！"); </script>';
                echo "<script> window.location.href='".U('Goods/index')."';</script>";
                exit();
            }
            if($data['buy']==''){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("参数错误！"); </script>';
                echo "<script> window.location.href='".U('Goods/index')."';</script>";
                exit();
            }
            //$id=intval(I('post.id',0,'addslashes'));
            if($data['num']=='' && $data['price']==''){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("添加失败！"); </script>';
                echo "<script> window.location.href='".U('Goods/index')."';</script>";
                exit();
            }else if($data['num']==''){
                //$conm['num'] = I('post.num');
                //echo $id,$conm['poundage_value'];die;
                //var_dump($conm['num']);die;
                $shop = M('shop');
                $shop->price=$data['price'];
                $shop->buy=$data['buy'];
                if($shop->where('id=%d',array($id))->save()!==false){
					//$shop->where('id=%d',array($id))->setInc('frequency');
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加成功！"); </script>';
                    echo "<script> window.location.href='".U('Goods/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加失败！"); </script>';
                    echo "<script> window.location.href='".U('Goods/index')."';</script>";
                    exit();
                }
            }else if($data['price']==''){

                $shop = M('shop');
                $shop->num=$data['num'];
                $shop->buy=$data['buy'];
                $shop->note=$data['note'];
                if($shop->where('id=%d',array($data['id']))->save()!==false){
					//$shop->where('id=%d',array($data['id']))->setInc('frequency');
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加成功！"); </script>';
                    echo "<script> window.location.href='".U('Goods/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加失败！"); </script>';
                    echo "<script> window.location.href='".U('Goods/index')."';</script>";
                    exit();
                }
            }else{
                //echo 1;die;
                $shop = M('shop');
                $shop->num=$data['num'];
                $shop->price=$data['price'];
                $shop->buy=$data['buy'];
                $shop->note=$data['note'];
                if($shop->where('id=%d',array($data['id']))->save()!==false){
					//$shop->where('id=%d',array($data['id']))->setInc('frequency');
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加成功！"); </script>';
                    echo "<script> window.location.href='".U('Goods/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                    echo '<script> alert("添加失败！"),history.back(); </script>';
                    exit();
                }
            }

        }else{
            $id = intval(I('get.id',0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("参数错误！"); </script>';
                echo "<script> window.location.href='".U('Goods/index')."';</script>";
                exit();
            }
            $shop = M('shop');
            $res = $shop->where('id=%d',array($id))->select();
            creatToken();
            $data=M('seeds')->distinct(true)->select();
            $this->assign('data',$data);
            $this->assign('shop',$res);
            $this->display('cost');
        }
    }
	
	/**
	*商品添加
	**/
    public function items_add(){
        if(empty($_POST)){
            creatToken();
            $data=M('seeds')->distinct(true)->select();
            $this->assign('data',$data);
            $this->display('items_add');
        }else{

            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Good/index');
                return;
            }
            $where['name'] = _safe(I('post.name'));
            $where['price'] = _safe(I('post.price'));
            $where['num'] = _safe(I('post.num'));
            $where['note'] = _safe(I('post.note'));
            $where['buy'] = _safe(I('post.buy'));
            $shop = M('shop');
            $shop->name=$where['name'];
            $shop->price=$where['price'];
            $shop->num=$where['num'];
            $shop->note=$where['note'];
            $shop->buy=$where['buy'];
            //$res = $shop->data($where)->add();
            if($shop->add()){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加成功！"); </script>';
                echo "<script> window.location.href='".U('Goods/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("添加失败！"),history.back(); </script>';
                exit();
            }
        }

    }

    public function up(){//上架
        if(!empty($_GET)){
            $id=intval(I('get.id',1,'addslashes'));
            $shop = M('shop');
            $con['type'] = 1;
            if($shop->where('id=%d',array($id))->save($con)!==false){
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

    public function down(){//下架
        if(!empty($_GET)){
            $id=intval(I('get.id',1,'addslashes'));
            $shop = M('shop');
            $con['type'] = 0;
            if($shop->where('id=%d',array($id))->save($con)!==false){
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




}