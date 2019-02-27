<?php
namespace Org\Our;
use Think\Model;

/**
 * Class FriendRecom  好友推荐
 * @package Org\Our
 */

class FriendRecom {

    public $trueTableName = "";

    public $TrueUserPhone = "";

    public $UserFriendRocemArray = array();

    protected $RecomFriendNum = 5;    //  初始推荐用户数量

    public function __construct($UserPhone = '') {

        if(! $UserPhone ){
            return false;
        }

        $this->trueTableName = substr($UserPhone,0,3)."_friendrecom";

        $this->TrueUserPhone = $UserPhone;

        //  判断 是否存在 该用户所在号段  的好友表
        if( ! S($this->TrueUserPhone) ){

            $table_is_exists = $this->TableIsExist();

            if(! $table_is_exists ){

                 $this->CreateTableFriendRecom();

            }

        }

    }

    /**
     * 判断 是否存在 该用户所在号段  的好友表
     * @return bool
     */
    private function TableIsExist(){

        $InforMation =  D("Tables");

        $where['TABLE_SCHEMA'] = C('DB_NAME');

        $where['TABLE_NAME'] = $this->trueTableName;

        $table_is_exists = $InforMation->where($where)->field("TABLE_NAME")->find();

        if($table_is_exists){

            S($this->TrueUserPhone,$this->TrueUserPhone) ;
            return true;

        }else{

            return false;

        }
    }

    /**
     * 创建用户所在 该用户所在号段  的好友表
     * @return bool
     */
    private function CreateTableFriendRecom(){

        $sql = " CREATE TABLE `".$this->trueTableName."` (
                              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                              `userphone`  char(11) NOT NULL,
                              `friendphone`  text NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

        $create_is_success = M()->execute($sql);

        if($create_is_success){
            S($this->TrueUserPhone,$this->TrueUserPhone) ;
            return true;

        }

    }

    /**
     *  获取用户所有推荐好友列表
     *
     *  单个用户好友列表存储方式为：
     *     UserPhone1/UserNickname1/UserLevel1 UserPhone2/UserNickname2/UserLevel2 UserPhone3/UserNickname3/UserLevel3
     *     以空格分割 每一个好友， 斜杠 / 连接 每一个用户的基本信息 ： 用户手机号，用户名称 ， 用户 等级。
     *
     * @return array
     */
    public  function GetFriendRecomArray(){

        if(! $UserFriendRocemArray = S($this->TrueUserPhone."FriendRecomArray") ){

            $where['userphone']  = $this->TrueUserPhone;

            $UserFriendRocemStr = M($this->trueTableName)->where($where)->field("friendphone")->select();

            $UserFriendRocemArray = array();

            foreach(explode(" ",$UserFriendRocemStr) as $key=>$val){
                $UserFriendRocemArray[explode("/",$val)[0]] = explode("/",$val);
            }

            S($this->TrueUserPhone."FriendRecomArray",array_unique($UserFriendRocemArray),array('type'=>'file'));

        }

        return $UserFriendRocemArray;

    }

    /**
     * 获取全部用户组
     * @return array|mixed
     */
    public function GetAllMembersArray(){

        if(S("AllMembers") && 1<>1 ){

            return S("AllMembers");

        }else {
			$AllMembers = array();
			
			$all_tables  =  M("statistical")->order("name asc")->select();
		
			$first_table = $all_tables[0]['name']."_members";
			
			unset($all_tables[0]);		

			$where = "1=1";
			
			$field   =  " user,tel,nickname,level,team,referees,num_id , id";

            foreach ($all_tables as $key => $val) {
				
				
				$table   =  $val['name']."_members ";
				
				$sql = "select " . $field . " from " . $table ." where  ".$where . " ";
									
				$union[] = $sql;			
									
            }
													
			$AllMembers =  M($first_table)->union($union,true)->field($field)->where($where)->select();

            return $AllMembers;
        }
    }
	
	public function test_all(){
		
			$AllMembers = array();
			
			$all_tables  =  M("statistical")->order("name asc")->select();
		
			$first_table = $all_tables[0]['name']."_members";
			
			unset($all_tables[0]);		

			$where = "1=1";
			
			$field   =  " user,tel,nickname,level,team,referees,num_id , id";

            foreach ($all_tables as $key => $val) {
				
				
				$table   =  $val['name']."_members ";
				
				$sql = "select " . $field . " from " . $table ." where  ".$where . " ";
									
				$union[] = $sql;			
									
            }
													
			$AllMembers =  M($first_table)->union($union,true)->field($field)->where($where)->select();

            return $AllMembers;
	}

    /**
     *  获取用户今日推荐用户
     * @return array|mixed
     */
    public function GetTodayRecom(){

        if(S($this->TrueUserPhone."TodayRecom")){

            return S($this->TrueUserPhone."TodayRecom");

        }else {

            $AllMembersArray =  $this->GetAllMembersArray();

            $UserFriendRocemArray = $this->GetFriendRecomArray();

            $UserCanRecomFriendArr = array_diff($AllMembersArray,$UserFriendRocemArray);

            for($i = 0 ; $i <$this->RecomFriendNum ; $i++ ){

                $this_recom_usser_key =  array_rand($UserCanRecomFriendArr);

                $TodayRecom[] = $UserCanRecomFriendArr[$this_recom_usser_key];

                unset($UserCanRecomFriendArr[$this_recom_usser_key]);

            }

            S($this->TrueUserPhone."TodayRecom", $TodayRecom, ( (strtotime(date('Ymd')) + 86400) - time() ) );

            return $TodayRecom;

        }


    }

    public function IncreaseUserFriend($FriendKey)
    {

        $UserFriendRocemArray = $this->GetFriendRecomArray();

        //  为此用户推荐已注册 一天以上的 用户 5 名
        $TodayFriendRecom = $this->GetTodayRecom();

        $IncreaseFriendInfo = $TodayFriendRecom[$FriendKey];

        //  去除今日推荐的用户
        unset($TodayFriendRecom[$FriendKey]);
        S($this->TrueUserPhone."TodayRecom", $TodayFriendRecom, ( (strtotime(date('Ymd')) + 86400) - time() ) );

        $UserFriendRocemArray[] = $IncreaseFriendInfo;

        // 更新用户好友列表
         S($this->TrueUserPhone."FriendRecomArray",array_unique($UserFriendRocemArray),array('type'=>'file'));

        $this->FlushUserFrienArr();

    }

    /**
     *  增加好友
     */
    private function FlushUserFrienArr(){

        $UserFriendRocemArray = $this->GetFriendRecomArray();

        foreach( $UserFriendRocemArray as $key=>$val){

            $UserFriendRocemStr[] = implode("/",$val);

        }

        $update_data['friendphone'] = implode(" ",$UserFriendRocemStr);

        $is_exist_info = M($this->trueTableName)->where('userphone="'.$this->TrueUserPhone.'"')->find();

        if($is_exist_info){

            M($this->trueTableName)->where('userphone="'.$this->TrueUserPhone.'"')->save($update_data);

        }else{

            $update_data['userphone'] = $this->TrueUserPhone;
            M($this->trueTableName)->add($update_data);

        }

    }


}