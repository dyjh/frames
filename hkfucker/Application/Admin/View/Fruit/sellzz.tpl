<extend name="Public:base" />
<block name="content">
    <js file="__JS__/md5.min.js" />
    <div class="page-header">
        <h1>种子收购</h1>
    </div>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
        <div style=" margin: 0 auto;">
            <form method="post" enctype="multipart/form-data" action="" >
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <div class="input-group" style="margin-top: 20px;">
                    <span class="input-group-addon" id="basic-addon1">系统账号</span>
                    <input type="text" name="user" class="form-control" placeholder="18382077208" autocomplete="off" value="18382077208" aria-describedby="basic-addon1">
                </div>
				<div class="input-group" style="margin-top: 20px;">
                    <span class="input-group-addon" id="basic-addon1">收购单价</span>
                    <input type="text" name="yes" class="form-control" placeholder="不输入则为默认" autocomplete="off" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary" id="btn" style="margin-left: 40%;;" >提交</button>
            </form>
        </div>
    </div>
       
</block>