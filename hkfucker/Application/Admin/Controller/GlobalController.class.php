<?php
namespace Admin\Controller;
use Think\Controller;
use \Think\Log;

class GlobalController extends AdminController {

    /**
     *   retrun data status
     *      40033 update global_conf error  修改表 global_conf 出错
     *      40034 Invalid global_conf name  无效的 global_conf 名称
     *      40035 global_conf cases exists  global_conf 名称已存在
     *      40036 insert global_conf error  新增 global_conf 配置项 失败
     */
    protected $cache = "0";//   缓存时间

    protected $table = "global_conf";
    
    public function index(){
        //  查询所有系统参数
        $all_conf = M($this->table)->field()->select();

        $this->assign("all_conf",$all_conf);

        $this->display();
    }

    /**
     *   异步修改 兑换材料 所需的成本价格
     * @param  $material_id  配置ID
     * @param  $object       配置信息
     * @return $data         返回消息
     */
    public function update_ajax($material_id,$object,$type="update"){
       // if(IS_AJAX){


            $object = I("post.object") ;

            $updata_arr = array(
                //"cases"    =>   $object['cases'],
                "value"    =>   $object['value'],
                //"note"     =>   $object['note'],
            );
            $dd=M($this->table);
            $dd->value=$updata_arr['value'];
            $res = $dd->where("id=%d",array($material_id))->save();

            if($res == 1){
                if($type=="update"){
                    $data['status']  = 0;
                    $data['content'] = 'OK';
                }elseif($type=="insert"){
                    $data['status']  = -1;
                    $data['content'] = 'OK';
                }

            }elseif($res == 0){
                $data['status']  = 0;
                $data['content'] = 'global_conf not update';
            }else{
    //            $this->ajaxReturn(M($this->table)->getLastSql(),'json');
                $data['status']  = 40033;
                $data['content'] = 'update global_conf error';
            }

            $this->ajaxReturn($data,'json');
   //     }
    }

    /**
     *  增加升级房屋所需的材料
     *
     */
    public function add_meterial($material_id,$object){

        if( $object['cases']){
            $map['cases'] = $object['cases'];
        }else{
            $data['status']  = 40034;
            $data['content'] = 'Invalid global_conf name';
            $this->ajaxReturn($data,'json');
        }

        $all_conf = M($this->table)->where($map)->find();

        if($all_conf){
            if($all_conf['value'] || $all_conf['note']){
                $data['status']  = 40035;
                $data['content'] = 'global_conf cases exists';
                $this->ajaxReturn($data,'json');
            }else{
                 $this->update_ajax($all_conf['id'],$object,"insert");
            }
        }else{
            $insert_id = M($this->table)->add($map);
            if($insert_id > 0 ){
                 $this->update_ajax($insert_id,$object,"insert");
            }else{
                $data['status']  = 40036;
                $data['content'] = 'insert global_conf error';
                $this->ajaxReturn($data,'json');
            }
        }
    }
}