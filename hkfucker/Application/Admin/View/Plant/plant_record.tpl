<extend name="Public/base"/>

<block name="style">

</block>


<block name="content">

    <js file="__JS__/common.js" />

    <section id="content" style="margin-left: 20px; margin-right: 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none">用户：{$user_info.user}的土地的列表</h3>
                    <a class="btn btn-default" href="{:U('Plant/plant_harvest',array('user'=>$user_info['user'],'id'=>$user_info['id']))}">种植和收获果实列表</a>
                </div>
                <section class="panel panel-default">

                        <!-- start Material list -->

                        <div class="table-responsive" id="listDiv">

                            <table class="table table-striped b-t b-light text-sm" id="table_user">
                                <thead>
                                <tr>
                                    <td  valign="middle" align="center">	        土地编号	        </td>
                                    <td  valign="middle" align="center">			种植植物		    </td>
                                    <td  valign="middle" align="center">			种植阶段		    </td>
                                    <td  valign="middle" align="center">			是否受灾		    </td>
                                    <td  valign="middle" align="center">			预计收获果实		</td>
                                    <td  valign="middle" align="center">			是否收获		    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <for start="1" end="13">
                                    <tr id="all_material_1">
                                        <td  valign="middle" align="center">
                                            {$i} {$all_land_info.$i.land_note}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$all_land_info.$i.seed_type}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$all_land_info.$i.status_note}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$all_land_info.$i.disasters_note}
                                        </td>
                                        <td  valign="middle" align="center">
                                            <if condition="$all_land_info[$i]['seeds_id']">
                                            <input type="hidden" value="{$all_land_info.$i.id}" name="id">
                                            <input type="hidden" value="{$user_info.tel}" name="tel">
                                            <input
                                                    style="width: 20px; float:inherit;" class="form-control" type="number"
                                                    aria-describedby="basic-addon1" readonly="readonly"
                                                    name="harvest_num"   value="{$all_land_info.$i.harvest_num}"
                                                    onblur="check_num(this,'__URL__/update_ajax',{$i});"
                                                    onclick="show_border('note_{$i}')" id="note_{$i}"
                                                    min="{$all_land_info.$i.seeds_min}" max="{$all_land_info.$i.seeds_max}" />
                                            </if>
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$all_land_info.$i.harvest_note}
                                        </td>
                                    </tr>
                                </for>
                                </tbody>
                            </table>

                        </div>

                </section>
            </section>
        </section>
    </section>

</block>