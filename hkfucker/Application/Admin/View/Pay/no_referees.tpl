<extend name="Public:base" />
<block name="content">
   
    <div style="width:80%; margin:0 auto;" >
	<style>
		.card{padding:10px 15px; border:1px solid gray; cursor:pointer}
		.select{color: #FF0000;background-color:#ffffff}
		.detial{display:none;}
	</style>
		 
		<div style="clear: both; height:10px;"></div>
		
		<a class="field-validation-valid card select" href="javascript:void(0)">独立个人</a>
		 
		<a class="field-validation-valid card " href="{:U('Pay/groupuser')}">小团队</a>

		<div style="clear: both; height:10px;"></div>
		 		 
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>总人数</th>
                <th>总差额</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$count}</td>
                <td>{$balance}</td>
            </tr>
            </tbody>
        </table>

        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户姓名</th>
                <th>用户电话</th>
                <th>充值总金额</th>
                <th>提现总金额</th>
                <th>差额</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$Team_List_User eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="Team_List_User" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.name}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.pay_money}</td>
                        <td>{$vo.cash_money}</td>
                        <td>{$vo.balance}</td>
                      </tr>
                </volist>
            </if>
            </tbody>
       
        </table>
       
	 <div style="clear: both;"></div>
		
   
    </div>

</block>
