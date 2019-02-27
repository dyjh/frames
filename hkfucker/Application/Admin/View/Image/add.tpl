<extend name="Public:base" />
<block name="content">
    <link rel="stylesheet" href="__COM__/kindeditor/themes/default/default.css" />
    <script src="__COM__/kindeditor/kindeditor.js"></script>
    <script src="__COM__/kindeditor/lang/zh_CN.js"></script>
    <script src="__COM__/kindeditor/kindeditor-all-min.js"></script>
    <div class="page-header">
        <h1>图片添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">所属页面</span>
                <select name="model" id="id" style="height:30px; "  onfocus="display_id()">
                    <option value="">全部</option>
                    <volist name="data_page" id="val">
                        <option value="{$val.name}">{$val.ch}</option>
                    </volist>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <img src="" style="max-width:100px;">
                <input type="hidden" name="url" value="" />
                <input type="button" class="upload_img" value="选择图片" />
                <!--<input type="file" name="img" id="pstimg"/>
                <img src="" id="loc_img" width="40" height="40" /> -->
            </div>

            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
<script>
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true
        });
        $(".upload_img").each(function(k,v){ //循环上传按钮、
            $(v).click(function(){ //每个上传按钮的点击事件
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        imageUrl:$(v).prev().val(),
                        clickFn : function(url, title, width, height, border, align) {
                            $(v).prev().val(url); //为他之前的input赋值
                            $(v).prev().prev().attr("src",url); //为他之前的之前的img赋值
                            editor.hideDialog();
                        }
                    });
                });
            });
        })
    });
    function cheack_form(){
         if($('input[name="model"]').val() == ''){
         alert('图片分类不能为空');
         return false;
         }
         if($('input[name="url"]').val() == ''){
         alert('图片不能为空');
         return false;
         }
     }
    $(function(){
        $("#pstimg").change(function(){
            var file = this.files[0];
            if (window.FileReader) {
                var reader = new FileReader();
                reader.readAsDataURL(file);
                //监听文件读取结束后事件
                reader.onloadend = function (e) {
                    showXY(e.target.result,file.fileName);
                };
            }
        });
    });
    function showXY(source){
        var img = document.getElementById("loc_img");
        img.src = source;
    }
</script>

</block>