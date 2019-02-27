<extend name="Public/base"/>

<block name="content">

    <section id="content" style="margin-left: 20px; margin-bottom: 20px;">
        <section class="vbox">
            <section class="scrollable padder">
                <div class="m-b-md">
                    <h3 class="m-b-none"> 返佣配置列表</h3>
                </div>

                <section class="panel panel-default">
                    <!-- start Material list -->
                    <div class="table-responsive" id="listDiv">
                        <form method="post" enctype="multipart/form-data" action="">
                        <table class="table table-striped b-t b-light text-sm" id="table_user">
                            <thead>
                            <tr>
                                <th  valign="middle" align="center">	配置名称	</th>
                                <th  valign="middle" align="center">			值		</th>
                            </tr>
                            </thead>
                            <tbody>
                            <volist name="data" id="val">
                                <tr id="tr{$val.id}">
                                    <td  valign="middle" align="center">
                                        <input  class="form-control" aria-describedby="basic-addon1" name="" readonly="readonly" id="" value="{$val.name}" size="4"/>
                                    </td>
                                    <td  valign="middle" align="center">
                                        <input  class="form-control" aria-describedby="basic-addon1"  name="{$val.id}" value="{$val.poundage_value}"  id="" />
                                    </td>
                                </tr>
                            </volist>
                            </tbody>
                        </table>
                            <div class="btn-group" role="group" aria-label="..." style="margin-bottom: 20px; margin-left: 35%;">
                                <button type="submit" class="btn btn-default">提交</button>
                            </div>
                        </form>
                    </div>

                </section>
            </section>
        </section>
    </section>
    <div style="height: 20px;"></div>
</block>