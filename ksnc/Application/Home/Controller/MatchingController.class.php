<?php
namespace Home\Controller;
use Think\Controller;

class MatchingController extends HomeController {

    public function _initialize(){

        $this->assign('nav_titels',"交易中心");

    }

    public function index()
    {

        $where['varieties'] = array("not in",array("摇钱树"));

        $data = D("Seeds")->select_all_seed($where);

        $data = $this->get_trade_data($data);

        $this->assign("datalist",$data);

        $this->assign('nav_titels',"交易中心");

        $this->display();

    }


    //  更新产品数据
    public function refresh_trade_data(){

        $where['varieties'] = array("not in",array("摇钱树"));

        $data = D("seeds")->select_all_seed($where);

        $data = $this->get_trade_data($data);

        $this->assign("datalist",$data);

        $this->assign('nav_titels',"交易中心");

        $data = $this->fetch('/Public/trade_tr');

        print_r($data);

        die;

    }

    private function get_trade_data($data){

        foreach( $data as $key=>$val){

            $MarketInfo = $this->GetFlushEntrust(0,$val['varieties']);

//            $trade_data[] = $MarketInfo;

            $trade_data[] = $MarketInfo['MarketInfo'];

        }

        return $trade_data;
    }




}