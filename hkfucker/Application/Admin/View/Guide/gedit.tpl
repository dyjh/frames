<extend name="Public:base" />
<block name="content">
    <script type="text/javascript" src="__JS__/kd/kindeditor-min.js"></script>
    <div class="page-header">
        <h1>公告添加</h1>
    </div>
    <div class=".table-responsive">
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return cheack_form()">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" id="input_title" style="width: 90%;margin-left: 5%; margin-right: 5%;">
                <span class="input-group-addon" id="basic-addon1">标题</span>
                <input type="text" class="form-control" placeholder="标题" name="title" value="{$data.title}" aria-describedby="basic-addon1"/>
            </div>
            <div class="input-group" style="width: 90%;margin-left: 5%; margin-right: 5%;">
                <span class="input-group-addon" id="basic-addon1">内容</span>
                <input type="hidden" name="id" value="{$id}"/>
                <textarea id="tid" name="content"  class="form-control" style=" resize : none;height: 300px;">{$data.content}</textarea>
            </div>
            <div class="btn-group" role="group"  style="margin-left: 5%;">
                <button type="submit" class="btn btn-default" id="btn" >提交</button>
            </div>
        </form>
    </div>
    <script>
        var editor;
        KindEditor.ready(function(k){
            editor = k.create('#tid',{
                items:["source","|","undo","redo","|","preview","print","template","code","cut","copy","paste","plainpaste","wordpaste","|","justifyleft","justifycenter","justifyright","justifyfull","insertorderedlist","insertunorderedlist","indent","outdent","subscript","superscript","clearhtml","quickformat","selectall","|","fullscreen","/","formatblock",
                    "fontname","fontsize","|","forecolor","hilitecolor","bold","italic","underline","strikethrough","lineheight","removeformat","|","image","multiimage","flash","media","insertfile","table","hr","emoticons","baidumap","pagebreak","anchor","link","unlink","|","about"]

            });
        });
        function cheack_form(){
            if($('input[name="title"]').val() == ''){
                alert('标题不能为空');
                return false;
            }
            if($('input[name="content"]').val() == ''){
                alert('内容不能为空');
                return false;
            }

        }

    </script>
</block>