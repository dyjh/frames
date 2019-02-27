<extend name="Public/base"/>


<block name="content">

    <js file="__JS__/common.js" />

    <section id="content"  style="margin-left: 20px; margin-right: 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none">所有果实 收获信息</h3>
                </div>
                <section class="panel panel-default">

                        <!-- start Material list -->

                        <div class="table-responsive" id="listDiv">

                            <table class="table table-striped b-t b-light text-sm" id="table_user">
                                <thead>
                                <tr>
                                    <td  valign="middle" align="center">	        编号	        </td>
                                    <td  valign="middle" align="center">			果实名称		    </td>
                                    <td  valign="middle" align="center">			预计收获果实		    </td>
                                    <td  valign="middle" align="center">			预计受灾果实		    </td>
                                    <td  valign="middle" align="center">			已收获果实	          	</td>
                                    <td  valign="middle" align="center">			受灾损失果实	       	</td>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="$seed_list_info">
                                <volist name="seed_list_info" id="val">

                                    <tr id="all_material_1">

                                        <td  valign="middle" align="center">
                                            {$i}
                                        </td>

                                        <td  valign="middle" align="center">
                                            {$key}
                                        </td>

                                        <td  valign="middle" align="center">
                                            {$val.plan_harvest_num}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.plan_disasters_value}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.get_harvest_num}
                                        </td>
                                        <td  valign="middle" align="center">
                                            {$val.get_disasters_value}
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
                            </table>

                        </div>

                </section>
            </section>
        </section>
    </section>

</block>