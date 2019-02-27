<extend name="Public/base"/>

<block name="style">

</block>


<block name="content">

    <js file="__JS__/common.js" />

    <section id="content" style="margin:0 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none">攻略文章审核</h3>
                </div>
                <section class="panel panel-default">

                    <!-- start Material list -->

                    <div class="table-responsive" id="listDiv">

                        <table class="table table-striped b-t b-light text-sm" id="table_user">
                            <thead>
                            <tr>
                                <td  valign="middle" align="center">	        排序	        </td>
                                <td  valign="middle" align="center">			攻略作者	    </td>
                                <td  valign="middle" align="center">			攻略标题	    </td>
                                <td  valign="middle" align="center">			打赏人数	    </td>
                                <td  valign="middle" align="center">			阅读人数	    </td>
                                <td  valign="middle" align="center">			攻略描述		</td>
                                <td  valign="middle" align="center">			审核状态		</td>
                                <td  valign="middle" align="center">			免费阅读		</td>
                                <td  valign="middle" align="center">			上传时间		</td>
                                <td  valign="middle" align="center">			操作		    </td>
                            </tr>
                            </thead>
                            <div style="clear: both;"></div>
                            <tbody>
                            <if condition="$all_raiders">
                                <volist name="all_raiders" id="val">
                                    <tr id="all_material_{$val.rid}">
                                        <td  valign="middle" align="center">
                                            <input class="form-control" style="width: 40px; min-width: 40px;" aria-describedby="basic-addon1" type="text" readonly="readonly" onblur="change_data('__URL__/update_ajax',{$val.rid})" onclick="show_border('listorder_{$val.rid}')" id="listorder_{$val.rid}" value="{$val.listorder}" name="listorder" >
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.mname}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.title}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.reward}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.readtime}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.description}
                                        </td>
                                        <td  valign="middle" align="center">
                                            <if condition="$val['is_show'] eq 1">
                                                <i class="glyphicon glyphicon-ok "></i>已通过
                                                <elseif condition="$val['is_show']  eq 0"/>
                                                <input type="hidden" value="{$val.is_show}" name="is_show" id="is_show_{$val.rid}" >
                                                <button class="btn btn-default" onclick="change_status(this,'__URL__/update_ajax',{$val.rid},'refuse')" title="点击拒绝该攻略" >×</button>
                                                <button class="btn btn-default" onclick="change_status(this,'__URL__/update_ajax',{$val.rid},'pass')" title="点击通过该攻略" >√</button>
                                                <else/>
                                                <input type="hidden" value="{$val.is_show}" name="is_show" id="is_show_{$val.rid}" >
                                                <i class="glyphicon glyphicon-ban-circle " onclick="change_status(this,'__URL__/update_ajax',{$val.rid},'pass')" title="点击图标更改状态"></i>已拒绝
                                            </if>
                                        </td>


                                        <td  valign="middle" align="center">
                                            <if condition="$val['is_free'] eq 1">

                                                <input type="hidden" value="{$val.is_free}" name="is_free" id="is_free_{$val.rid}" >
                                                <i class="glyphicon glyphicon-star-empty" onclick="change_status(this,'__URL__/update_ajax',{$val.rid},'is_free')" title="点击图标更改状态"></i>免费阅读

                                                <elseif condition="$val['is_free']  eq 2"/>
                                                <input type="hidden" value="{$val.is_free}" name="is_free" id="is_free_{$val.rid}" >
                                                <i class="glyphicon glyphicon-star " onclick="change_status(this,'__URL__/update_ajax',{$val.rid},'is_free')" title="点击图标更改状态"></i>付费阅读
                                             </if>
                                        </td>



                                        <td  valign="middle" align="center">
                                            {$val.addtime|date="Y-m-d",###}
                                        </td>
                                        <td  valign="middle" align="center">
                                            <button style="width: 40px;" class="btn btn-default" onclick="delete_confirm('{$val.rid}','__URL__')" title="删除该攻略?" style="float: right;width: 20%">X</button>
                                        </td>
                                    </tr>
                                </volist>
                                <else/>
                                <tr>
                                    <td colspan="6" align="center">
                                        <p style="padding: 15px;">暂无数据信息</p>
                                    </td>
                                </tr>
                            </if>
                            </tbody>
                            <div style="clear: both;"></div>
                        </table>
                        <div style="clear: both;"></div>
                        <if condition="$all_raiders">
                            <nav aria-label="Page navigation">
                                <ul style="margin-left: 35%;" class="pagination">
                                    <li>
                                        <a href="{:U('Raiders/index',array('k'=>$k-1))}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <for start="$start" end="$end">
                                        <if condition="$k eq $i">
                                            <li><a class="active_na" style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Raiders/index',array('k'=>$i))}">{$i}</a></li>
                                            <else />
                                            <li><a href="{:U('Raiders/index',array('k'=>$i))}">{$i}</a></li>
                                        </if>
                                    </for>
                                    <li>
                                        <a href="{:U('Raiders/index',array('o'=>$o+1,'id'=>$id))}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <else/>
                        </if>
                        <div style="clear: both;"></div>
                        <script>
                            function refuse_confirm(_id,_url){
                                if(_id){
                                    if(confirm('是否拒绝此攻略？')){
                                        $.post(_url+"/refuse_raiders",{id:_id});
                                    }
                                }
                            }

                            function change_status(_this,_url,id_num,type){

                                if(type=='refuse'){
                                    confirm_content = "是否拒绝此攻略？";
                                    var change_val = -1 ;
                                }else  if(type=='pass'){
                                    confirm_content = "是否通过此攻略？"
                                    var change_val = 1 ;
                                }else  if(type=='is_free'){
                                    confirm_content = "是否更改此攻略阅读状态？"
                                    $is_free_val = $("#is_free_"+id_num).val();
                                    var change_val = 0;
                                    var change_free_val = 3 - $is_free_val * 1 ;

                                    if(change_free_val == 1){
                                        var  is_free_content = '免费阅读';
                                        var    free_glyphicon_icon = 'glyphicon-star-empty'
                                    }else if(change_free_val == 2){
                                        var   is_free_content = '付费阅读';
                                        var   free_glyphicon_icon = 'glyphicon-star'
                                    }
                                }

                                if(confirm(confirm_content)){

                                    $("#is_show_"+id_num).val(change_val);
                                    if(change_val == 1){
                                        var  _html =  '<input type="hidden" value="1" name="is_show" id="is_show_'+ id_num +'">'+' <i class="glyphicon glyphicon-ok "></i>已通过';
                                    }else  if(change_val == -1){
                                        var  _html =  '<input type="hidden" value="-1" name="is_show" id="is_show_'+ id_num +'">'+
                                                ' <i class="glyphicon glyphicon-ban-circle " onclick="change_status(this,\'__URL__/update_ajax\','+ id_num +',\'pass\')" title="点击图标更改状态"></i>已拒绝'
                                    }else  if(change_val == 0){
                                        var  _html =  '<input type="hidden" value="'+change_free_val+'" name="'+type+'" id="'+type+'_'+ id_num +'">'+
                                                ' <i class="glyphicon '+free_glyphicon_icon+' " onclick="change_status(this,\'__URL__/update_ajax\','+ id_num +',\'is_free\')" title="点击图标更改状态"></i>'+is_free_content
                                    }
//                                    alert(change_free_val);
//                                    alert( _html);
                                    $(_this).parent("td").html(_html);

                                    change_data(_url,id_num)
                                }
                            }
                        </script>
                    </div>
                    <div style="clear: both;"></div>
                </section>
            </section>
            <div style="clear: both;"></div>
        </section>
        <div style="clear: both;"></div>
    </section>
    <div style="clear: both;"></div>
</block>