<?php
namespace Admin\Controller;
use Think\Controller;
use \Think\Log;
use Think\Material;
class MaterialController extends AdminController{
    
    protected $cache = "0";//   缓存时间

    protected $table = "house_material";
    
    public function index(){
        //  查询所有作物
		$where['varieties']  = array("not in",['种子','摇钱树']);
		
        $all_seed = M("seeds")->field("id,varieties")->where($where)->distinct(true)->select();

        //查询所有材料
        $all_material = M($this->table)->distinct(true)->select();

        $all_material = material_handle_list($all_material);
        
        $this->assign("all_seed",$all_seed);
        $this->assign("all_material",$all_material);
        $this->display();
    }

    /**
     *   异步修改 兑换材料 所需的成本价格
     * @param  $material_id  材料ID
     * @param  $object       成本价格数组
     * @return $data         返回消息
     */
    public function update_ajax($material_id,$object){

        $Material = new Material();

        $data = $Material->update_ajax($material_id,$object);

        $this->ajaxReturn($data,'json');

    }

    /**
     *  增加升级房屋所需的材料
     *
     */
    public function add_meterial($material_id,$object){

        $Material = new Material();

        $data = $Material->add_meterial($object);

        $this->ajaxReturn($data,'json');

    }

    public function delete_data(){
        $id=intval(I('post.id',0,'addslashes'));
        if($id==0){
            $data['status']  = 40099;
            $data['content'] = 'delete data error';
        }else{
            $res = M($this->table)->delete($id);
            if($res > 0){
                $data['status']  = 0;
                $data['content'] = 'delete success';
                $data['remove_tr'] = $id;
            }elseif($res == 0){
                $data['status']  = -1;
                $data['content'] = 'not data delete';
            }else{
                $data['status']  = 40099;
                $data['content'] = 'delete data error';
            }
        }
        $this->ajaxReturn($data,'json');
    }
}