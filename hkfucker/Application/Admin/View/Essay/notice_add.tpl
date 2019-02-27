<extend name="Public:base" />

<block name="content">
    <script type="text/javascript" src="__JS__/kd/kindeditor-min.js"></script>
    <body style="height: 100%; margin: 0">
    <input type="hidden" class="id" value="{$id}"/>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>添加公告</h1>
        <div style="margin-left: 20%; margin-bottom: 20px;">
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <div class="input-group" style="width: 80%; float: left;margin-left: 10px;">
                    <div style="float: left;">
                        <lbale>标     题</lbale><input type="text" name="title" class="form-control" placeholder="输入商品名" aria-describedby="basic-addon1">
                    </div>
                    <div style="float: left; margin-left: 10%;">
                        <lbale>来     源</lbale><input type="text" name="source" class="form-control" placeholder="内容来源" aria-describedby="basic-addon1">
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <hr/>
                <hr/>

                <hr/>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>简     介</lbale>
                    <textarea name="content_title"  class="form-control" style=" resize : none; width: 650px;"></textarea>
                </div>
                <hr/>
                <hr/>
                <hr/>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>内     容</lbale>
                    <textarea id="tid" name="content"  class="form-control" style=" resize : none;"></textarea>

                </div>
                <hr/>
                <hr/>
                <hr/>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>图     片</lbale><input type="file" name="pic" id="pic" class="form" placeholder="输入数量" aria-describedby="basic-addon1">
                </div>
                <hr/>
                <hr/>
                <hr/>
                <hr/>
                <hr/>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>链接上传</lbale><input  type="text" name="link"  class="form" placeholder="输入链接地址" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>视屏上传</lbale><input  type="file" name="mv" id="mv" class="form" placeholder="输入注释" aria-describedby="basic-addon1">
                </div>
                <hr/><hr/><hr/><hr/><hr/><hr/>
                <div class="input-group" style="margin-left:10px;">
                    <button id="btn" type="submit" >添加</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>

        <div style="clear: both;"></div>
        <if condition="$state eq 1">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o-1,'id'=>$id))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$o eq $i">
                            <li><a class="active_na" style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o+1,'id'=>$id))}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <else/>
        </if>
    </div>
    <div style="clear: both;"></div>
    <script type="text/javascript" src="__JS__/echarts-all-3.js"></script>
    <script>
        var editor;
        KindEditor.ready(function(k){
            editor = k.create('#tid',{
                items:["source","|","undo","redo","|","preview","print","template","code","cut","copy","paste","plainpaste","wordpaste","|","justifyleft","justifycenter","justifyright","justifyfull","insertorderedlist","insertunorderedlist","indent","outdent","subscript","superscript","clearhtml","quickformat","selectall","|","fullscreen","/","formatblock",
                    "fontname","fontsize","|","forecolor","hilitecolor","bold","italic","underline","strikethrough","lineheight","removeformat","|","image","multiimage","flash","media","insertfile","table","hr","emoticons","baidumap","pagebreak","anchor","link","unlink","|","about"]

            });
        });
        var editor;
        KindEditor.ready(function(k){
            editor = k.create('#tid_title',{
                items:["source","|","undo","redo","|","preview","print","template","code","cut","copy","paste","plainpaste","wordpaste","|","justifyleft","justifycenter","justifyright","justifyfull","insertorderedlist","insertunorderedlist","indent","outdent","subscript","superscript","clearhtml","quickformat","selectall","|","fullscreen","/","formatblock",
                    "fontname","fontsize","|","forecolor","hilitecolor","bold","italic","underline","strikethrough","lineheight","removeformat","|","image","multiimage","flash","media","insertfile","table","hr","emoticons","baidumap","pagebreak","anchor","link","unlink","|","about"]

            });
        });

    </script>
</block>