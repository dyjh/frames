<?php
namespace Home\Model;
use Think\Model;

class FruitRecordModel extends Model{


    public $trueTableName = "";

    public function __construct($trueTableName = '') {
		
		parent::__construct();

        if($trueTableName){
            $this->trueTableName = $trueTableName;
        }
    }

    /**
     * 查询用户种子信息
     * @param $member_where                 查询的用户
     * @param string $is_get_free           是否查询冻结了的果实
     * @return $res                         返回数组
     */
    public function get_user_seed_list($member_where,$is_get_free = '',$is_join = 0){

          $field = "*";
		  
		  $table_name = $this->trueTableName;
		 
          if($is_join){
              $join = " LEFT JOIN seeds on seeds.varieties = ".$table_name.".seeds ";
              $field = $table_name.".*, seeds.id as seeds_id";
          }
 
          $member_where['seeds'] = array("not in", "摇钱树，种子");					
		  
          $UserSeedList =  $this->where($member_where)->join($join)->group("seeds")->field($field)->select();		  

          $UserSeedList = $UserSeedList ? $UserSeedList : array();

          // if(! $AllSeedsLists = S("AllSeedsLists")){
              $AllSeedsList =  M("Seeds")->field("varieties,id")->select();
              foreach($AllSeedsList as $key=>$val){
                   $AllSeedsLists[$val['id']] = $val['varieties'];
              }
              S("AllSeedsLists",$AllSeedsLists,24*3600);
          // }

          $UserSeedList =  array_pad($UserSeedList , count($AllSeedsLists) ,array());
			
			
          foreach($UserSeedList as $key=>$val){
            $seeds = '';
            $seeds_id = '';
            if($seeds = array_search($val['seeds'],$AllSeedsLists)){
                $res[$key] = $val;
                $res[$key]['free_seed_num'] = $val['num'];
                unset($AllSeedsLists[$seeds]);
            }else{
                $seeds = reset($AllSeedsLists);
                $seeds_id = array_search($seeds,$AllSeedsLists);
                $UserNotHave['num'] = $res[$key]['free_seed_num'] = 0 ;
                $UserNotHave['seeds'] = $seeds ;
                $UserNotHave['seeds_id'] = $seeds_id ;
                unset( $AllSeedsLists[$seeds_id]  );
                $res[] = $UserNotHave;
            }
         }

			

//          if($is_get_free){
//              $prefix = substr($this->trueTableName,0,4);
//              $freeze_seed_table = $prefix."fruit_record";
//              $freeze_where['user'] = $member_where['user'];
//
//              foreach($res as $key=>$val){
//                  $freeze_where['seed'] = $val['seeds'];
//                  $res[$key]['free_seed_num'] = (int)M($freeze_seed_table)->where($freeze_where)->getField("num");
//              }
//          }
            return $res;
     }
}
