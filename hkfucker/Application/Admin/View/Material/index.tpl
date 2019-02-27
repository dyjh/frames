<extend name="Public/base"/>

<block name="content">

    <js file="__JS__/common.js" />

    <button class="btn btn-default" ondblclick="$('#more_meterial').show()" style="margin-left: 20px;">增加更多材料</button>

    <section id="content" style="margin-left: 20px; margin-bottom: 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none"> 材料列表</h3>
                </div>

                <section class="panel panel-default">
                        <!-- start Material list -->
                        <div class="table-responsive" id="listDiv">
                            <table class="table table-striped b-t b-light text-sm" style="display: none;" id="more_meterial">
                                <thead>
                                <tr>
                                    <th  valign="middle" align="center">
                                        材料名称
                                    </th>

                                    <volist name="all_seed" id="val">
                                        <th  valign="middle" align="center">	{$val.varieties}		</th>
                                    </volist>
                                    <!-- <th  valign="middle" align="center"> -->
                                        <!-- 金额 -->
                                    <!-- </th> -->
                                    <th  valign="middle" align="center">
                                        <button type="submit" class="btn btn-default" onclick="$('#more_meterial').hide()">关闭</button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="all_material_a1">

                                    <td valign="middle" align="center">
                                        <input name="name" style="border: 1px solid #cccccc;" class="form-control" aria-describedby="basic-addon1" onclick="show_border('name_a1')" id="name_a1">
                                    </td>


                                    <volist name="all_seed" id="seed_val">
                                        <td  valign="middle" align="center">
                                            <input style="border: 1px solid #cccccc;" name="{$seed_val.id}" class="form-control" aria-describedby="basic-addon1"  onclick="show_border('seed_{$seed_val.id}')" id="seed_{$seed_val.id}" />
                                        </td>
                                    </volist>

                                    <!-- <td  valign="middle" align="center"> -->
                                        <!-- <input style="border: 1px solid #cccccc;" name="price" class="form-control" aria-describedby="basic-addon1" onclick="show_border('price_a1')" id="price_a1" /> -->
                                    <!-- </td> -->

                                    <td  valign="middle" align="center">
                                        <button type="submit" class="btn btn-default" onclick="change_data('__URL__/add_meterial','a1')">提交</button>
                                    </td>

                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-striped b-t b-light text-sm" id="table_user">
                                <thead>
                                <tr>
                                    <th  valign="middle" align="center">	材料名称	</th>

                                    <volist name="all_seed" id="val">
                                        <th  valign="middle" align="center">	{$val.varieties}		</th>
                                    </volist>
                                    <!-- <th  valign="middle" align="center">			金额		</th> -->
                                    <th  valign="middle" align="center">			操作		</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <volist name="all_material" id="val">
                                    <tr id="all_material_{$val.id}">
                                        <td  valign="middle" align="center">
                                            <input  class="form-control" aria-describedby="basic-addon1" name="name" readonly="readonly" id="{$val.id}_{$seed_val.id}" onblur="change_data('__URL__/update_ajax',{$val.id})" onclick="show_border('{$val.id}_{$seed_val.id}')" value="{$val.name}" size="4"/>
                                        </td>
                                        <volist name="all_seed" id="seed_val">
                                            <td  valign="middle" align="center">
                                                <input  class="form-control" aria-describedby="basic-addon1" name="{$seed_val.id}" readonly="readonly" id="{$val.id}_seed_{$seed_val.id}" onblur="change_data('__URL__/update_ajax',{$val.id})" onclick="show_border('{$val.id}_seed_{$seed_val.id}')" value="{$val[$seed_val['id']]}"/>
                                            </td>
                                        </volist>
                                        <!-- <td  valign="middle" align="center"> -->
                                            <!-- <input  class="form-control" aria-describedby="basic-addon1" readonly="readonly" name="price" value="{$val.price}" onblur="change_data('__URL__/update_ajax',{$val.id})" onclick="show_border('price_{$val.id}')" id="price_{$val.id}" /> -->
                                        <!-- </td> -->
                                        <td  valign="middle" align="center">
                                            <button style="width: 40px;" class="btn btn-default" onclick="delete_confirm('{$val.id}','__URL__')" style="float: right;width: 20%">X</button>
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

</block>