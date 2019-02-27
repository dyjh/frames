/**
 * Created by Administrator on 2017/5/4 0004.
 * ````````` QHP `````````
 */
/**
 *  材料兑换成本修改
 * @param _url      POST URL
 * @param id_num    要修改的材料ID
 */
function change_data(_url,id_num){
    var material = document.getElementById("all_material_"+id_num);

    var SonInput = material.getElementsByTagName("input");

    var AjaxObj = new Object();

    for(var i=0; i<SonInput.length; i++){
        var _name = SonInput[i].name;
        AjaxObj[_name] = SonInput[i].value;
        SonInput[i].style.border = "0px solid #cccccc";
        SonInput[i].readOnly = true;
    }

    $.post(
        _url,
        {material_id:id_num,object:AjaxObj},
        function (data) {
            if(data.status > 0){

            }else{
                if(data.status == '-1'){ window.location = window.location;}
            }
        },
        "json"
    )
}

/**
 *  显示边框
 * @param _obj
 */
function show_border(_obj) {
    $("#"+_obj).css("border","1px solid #cccccc");
    $("#"+_obj).removeAttr("readOnly");
}

/**
 * 增加DIV
 * @param first_div   要增加的DIV
 * @param _add_div
 * @param _number
 * @param _most
 * @returns {boolean}
 */
function add_default_div(first_div,_add_div,_number,_most){
    var val = document.getElementById(_number).value;
    var add_html = document.getElementById(first_div).innerHTML;
    var q=val*1+1;
    if(_most){
        if(q>_most){
            alert("仅能增加一次");
            return false;
        }
    }

    $("#"+_number).val(q);
    var btn=document.getElementById(_add_div);
    //					for($i=a;a<=9999;$i++){
    var div=document.createElement("div");
    var str = "";
    str  +=  add_html;
    div.innerHTML=str;
    btn.appendChild(div);
}

//  减少DIV
function cc(_add_div,_number){
    var val = document.getElementById(_number).value;
    var q=val*1-1;
    if(q>0)
    {
        $("#"+_number).val(q);
    }
    $('#'+_add_div).children().last().remove()
}

var judge_eng = /^[A-Za-z]+$/;    // 英文字母 正则式

function judge_string(_this,_type){
    // TODO ：暂时用于后台 global_conf 验证 是否为英文

    var _string = $(_this).val();

    switch (_type){
        case "ENG":
            var do_fun = judge_eng ;
            var alert_content = " 填入内容需要为英文字母";
            break;
        case "NUM":
            break;
    }
    if(!do_fun.test(_string)){
        $(_this).next().next().html(alert_content)
        $(_this).next().next().show();
        return false;
    }else{
        $(_this).next().next().hide();

    }
}

/**
 *  异步删除数据
 * @param _id  数据ID
 */
function delete_confirm(_id,_url){
    if(_id){
        if(confirm('是否删除，删除后无法恢复')){
            $.post(_url+"/delete_data",{id:_id},delete_callback,"json");
        }
    }
}

/**
 *   删除数据 回调。
 */
function delete_callback(data){
    if(data.status == 0){
        $("#all_material_"+data.remove_tr).remove();
    }else if(data.status == 40099){
        alert("删除失败");
    }
}

/**
 * 检查数值是否符合最大最小值
 *
 */
function check_num(_this,_url,id_num){
    var min_num = $(_this).attr("min")
    var max_num = $(_this).attr("max")
    var now_val = $(_this).val();
    if(now_val > max_num ||  now_val < min_num ){
        alert("预计果实须在"+min_num +"至"+max_num+"之间");
        $(_this).focus();
        return false;
    }else{
        change_data(_url,id_num)
    }
}



















