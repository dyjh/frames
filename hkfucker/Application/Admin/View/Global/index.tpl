<extend name="Public/base"/>

<block name="style">

</block>


<block name="content">

    <js file="__JS__/common.js" />

    <section id="content" style="margin-left: 20px; margin-bottom: 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none"> 配置列表</h3>
                </div>
                <section class="panel panel-default">

                        <!-- start Material list -->

                        <div class="table-responsive" id="listDiv" style="">



                            <table class="table table-striped b-t b-light text-sm" id="table_user">
                                <thead>
                                <tr>
                                    <th  valign="middle" align="center">	        配置名称	</th>
                                    <th  valign="middle" align="center">			值		    </th>
                                </tr>
                                </thead>
                                <tbody>
                                  <volist name="all_conf" id="val">
                                    <tr id="all_material_{$val.id}">
                                        <td  valign="middle" align="center">
                                            {$val.note}
                                        </td>
                                        <td  valign="middle" align="center">
                                            <input  class="form-control" aria-describedby="basic-addon1" readonly="readonly" name="value"  value="{$val.value}" onblur="change_data('__URL__/update_ajax',{$val.id})" onclick="show_border('value_{$val.id}')" id="value_{$val.id}" />
                                        </td>
                                    </tr>
                                </volist>
                                </tbody>
                            </table>

                        </div>

                </section>
            </section>
        </section>
    </section>
    <div style="width: 200px; height: 50px;">
    </div>
</block>