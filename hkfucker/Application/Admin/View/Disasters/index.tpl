<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>灾难降临</h1>
    </div>
    <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="margin-top: 20px; margin-bottom: 50px;">
        <div class="table-responsive" id="listDiv" style="background-color: #00ca6d;height: 200px; float: left; min-width: 200px;">
            <h4 style="margin-left: 35%;">回本玩家<small><a style="color:red" href="{:U('Disasters/clear_disaster',array('type'=>1))}">清空</a></small></h4>
            <div style="text-align: center;">可蹂躏的土地数量为：<span id="old">{$num_old}</span>。<if condition="$state_old eq 1">当前灾难轮数剩余为{$count_old}轮 <else/>当前无灾难</if></div>
            <div style="margin: 20px auto">
                <form method="post" enctype="multipart/form-data" action="" onsubmit="return cheack_form_old()">
                    <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                    <input type="hidden" name="zhi" class="old" value="1"/>
                    <input type="hidden" name="state_old" value=""/>
                    <div class="input-group" style="width: 30%; float: left;margin-left: 5%;">
                        <input type="text" id="lun_old" name="lun" onfocus="old()" class="form-control" placeholder="灾难轮数" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group" style="width: 30%; float: left;margin-left: 5%;">
                        <input type="text" id="k_old" name="k" class="form-control" placeholder="灾难次数" aria-describedby="basic-addon1">
                    </div>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="submit" class="btn btn-default">蹂躏</button>
                    </div>
                </form>
				<div style="">
					<a href="http://lyogame.cn/farms/Plan/Disaster_generate?type=old" style="color:#000; text-align:center; margin-left:45%; margin-top:5%;" target=_blank >立即执行</a>
				</div>
            </div>
        </div>
        <div class="table-responsive" id="listDiv" style="background-color: #f0c040;height: 200px; float: left;min-width: 200px;">
            <h4 style="margin-left: 35%;">未回本玩家<small><a style="color:red" href="{:U('Disasters/clear_disaster',array('type'=>0))}">清空</a></small></h4>
            <div style="text-align: center;">可蹂躏的土地数量为：<span id="new">{$num_new}</span>。<if condition="$state_new eq 1">当前灾难轮数剩余为{$count_new}轮<else/>当前无灾难</if></div>
            <div style="margin: 20px auto">
                <form method="post" enctype="multipart/form-data" action="" onsubmit="return cheack_form_new()">
                    <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                    <input type="hidden" name="state_new" value=""/>
                    <input type="hidden"name="zhi" class="new" value="0">
                    <div class="input-group" style="width: 30%; float: left;margin-left: 5%;">
                        <input type="text" id="lun_new" name="lun" onfocus="new_1()" class="form-control" placeholder="灾难轮数" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group" style="width: 30%; float: left;margin-left: 5%;">
                        <input type="text" name="k" id="k_new" class="form-control" placeholder="灾难次数" aria-describedby="basic-addon1">
                    </div>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="submit" class="btn btn-default">蹂躏</button>
                    </div>
                </form>
				<div style="">
					<a href="http://lyogame.cn/farms/Plan/Disaster_generate?type=new" style="color:#000; text-align:center; margin-left:45%; margin-top:5%;" target=_blank >立即执行</a>
				</div>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>




    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">


            <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>用户</th>
                    <th>果实</th>
                    <th>种植时间</th>
                    <th>种子期</th>
                    <th>灾难值</th>
                    <th>预计成熟数量</th>
                </tr>
                </thead>
                <tbody>
                <if condition="$state eq 1">
                    <volist name="data" id="val">
                        <tr>
                            <td>{$val.id}</td>
                            <td>{$val.user}</td>
                            <td>{$val.seed_type}</td>
                            <td>{$val.time|date="Y-m-d H:i:s",###}</td>
                            <if condition ="$val['seed_state'] eq 0">
                                <td style="color: gray;">种子期</td>
                                <elseif condition ="$val['seed_state'] eq 1" />
                                <td style="color: red">发芽期</td>
                                <elseif condition ="$val['seed_state'] eq 2" />
                                <td style="color: green">成株期</td>
                                <else/>
                                <td style="color: green">成熟期</td>
                            </if>
                            <td>{$val.disasters_value}</td>
                            <td>{$val.harvest_num}</td>
                        </tr>
                    </volist>
                    <else/>
                    <tr><td>暂无</td></tr>
                </if>
                </tbody>
            </table>
            <div style="clear: both;"></div>
            <if condition="$state eq 1">
                <nav aria-label="Page navigation">
                    <ul style="margin-left: 35%;" class="pagination">
                        <li>
                            <a href="{:U('Disasters/index',array('o'=>$o-1))}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <for start="$start" end="$end">
                            <if condition="$o eq $i">
                                <li><a class="active_nav"  style="background:#337ab7 !important;color:#FFFFFF !important; " href="{:U('Disasters/index',array('o'=>$i))}">{$i}</a></li>
                                <else />
                                <li><a href="{:U('Disasters/index',array('o'=>$i))}">{$i}</a></li>
                            </if>
                        </for>
                        <li>
                            <a href="{:U('Disasters/index',array('o'=>$o+1))}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <else/>
            </if>

    </div>


    <div style="clear: both;"></div>
    <script>
        function old() {
            var type=$('.old').val();
            $.post("{:U('Disasters/check')}",{type:type},function(msg){
                if(msg == 0){
                    $('input[name="state_old"]').val('1');
                }else {
                    $('input[name="state_old"]').val('0');
                }
            })
        }
        function new_1() {
            var type=$('.new').val();
            $.post("{:U('Disasters/check')}",{type:type},function(msg){
                if(msg == 0){
                    $('input[name="state_new"]').val('1');
                }else {
                    $('input[name="state_new"]').val('0');
                }
            })
        }
        function cheack_form_old(){
            if($('input[name="state_old"]').val() == '1'){
                alert('当前灾难正在分配请于完成之后添加');
                return false;
            }
            if($('#lun_old').val() == ''){
                alert('轮数不能为空');
                return false;
            }
            if($('#k_old').val() == ''){
                alert('次数不能为空');
                return false;
            }
            if($('#old').text() == '0'){
                alert('当前无土地可蹂躏');
                return false;
            }
        }
        function cheack_form_new(){
            if($('input[name="state_new"]').val() == '1'){
                alert('当前灾难正在分配请于完成之后添加');
                return false;
            }
            if($('#lun_new').val() == ''){
                alert('轮数不能为空');
                return false;
            }
            if($('#k_new').val() == ''){
                alert('次数不能为空');
                return false;
            }
            if($('#new').text() == '0'){
                alert('当前无土地可蹂躏');
                return false;
            }
        }
    </script>
</block>