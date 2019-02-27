<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <if condition="$type eq 'one'">
            <h1>个人设置：{$user}</h1>
            <else/>
            <h1>全部设置</h1>
        </if>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 30%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <input type="hidden" name="user" value="{$user}"/>
            <input type="hidden" name="level" value="{$level}"/>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">果实控制</span>
                <input type="text" name="allow_seed" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">开始时间</span>
                <select name="start" id="start" style="height:30px; ">
                    <option value="0">周日</option>
                    <option value="1">周一</option>
                    <option value="2">周二</option>
                    <option value="3">周三</option>
                    <option value="4">周四</option>
                    <option value="5">周五</option>
                    <option value="6">周六</option>
                </select>
            </div>
            <input type="hidden" name="type" value="{$type}"/>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">结束时间</span>
                <select name="end" id="end" style="height:30px; ">
                    <option value="0">周日</option>
                    <option value="1">周一</option>
                    <option value="2">周二</option>
                    <option value="3">周三</option>
                    <option value="4">周四</option>
                    <option value="5">周五</option>
                    <option value="6">周六</option>
                </select>
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
    <script>
        function cheack_form(){
            var start=$('#start option:selected') .val();
            var end=$('#end option:selected') .val();
            if(end<=start){
                alert('结束日必须大于起始日');
                return false;
            }
        }
    </script>
</block>
