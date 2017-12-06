<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=config_item('site_title')?></title>

    <link href="themes/insdep/easyui.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="themes/jquery.min.js"></script>
    <script type="text/javascript" src="themes/jquery.easyui.min.js"></script>
    <link href="themes/insdep/icon.css" rel="stylesheet" type="text/css">
    <style>
        body{font-family: 'Roboto', "PingFang SC","Lantinghei SC","Microsoft Yahei",微软雅黑,"Hiragino Sans GB",'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;}
        .panel{
            margin: 0px auto;
        }
        .login_main{width: 960px; height: 485px; margin: 0 auto; background:url(static/images/login_bg.png) no-repeat 0 0; position: relative;}
        .login_box{ position: absolute; top:40px; right: 15px; width: 360px; height: 380px; background:url(static/images/login_box.png) no-repeat}
        .login_box_body{ padding:25px;}
        .login_box_body table td{ padding: 10px 0;}
        .l-btn-text{font-size: 16px; color: #444}
        .textbox .textbox-text{font-size: 16px;}
    </style>
</head>
<body>
<h2 align="center" style=" padding: 50px  0 0 0;"><img src="static/images/login_title.png"/></h2>
<div class="login_main">
    <div class="login_box">
        <div class="login_box_body">
            <h3 style="margin: 0 0 30px 0; background: url(static/images/login_line.png) no-repeat 0 bottom; font-size: 16px; color: #285397; height: 36px; line-height: 32px;">用户登录</h3>
            <form id="login_form" method="post">
                <table cellpadding="5">
                    <tr height="50px">
                        <td><b>用户名：</b></td>
                        <td><input class="easyui-textbox" type="text" style="height: 42px;line-height: 42px;width: 235px;" name="unm" id="unm" data-options="iconCls:'icon-man',iconAlign:'left'"></input></td>
                    </tr>
                    <tr height="50px">
                        <td><b>密&nbsp;&nbsp;&nbsp;&nbsp;码：</b></td>
                        <td><input   class="easyui-textbox" type="password" style="height: 42px;line-height: 42px;width: 235px;" name="pwd" id="pwd" data-options="iconCls:'icon-lock',iconAlign:'left'"></input></td>
                    </tr>
                </table>
            </form>
            <div style="text-align:left;padding:5px;margin-top: 15px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()" style="width: 100px; height: 36px; margin: 0 0 0 67px; font-size: 16px;"> 登&nbsp;&nbsp;&nbsp;&nbsp;录 </a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()" style="margin-left:15px;width: 100px;height: 36px; font-size: 16px;"> 重&nbsp;&nbsp;&nbsp;&nbsp;置 </a>
            </div>
        </div>
    </div>
    <p style="text-align: center; color: #999; font-weight: normal; font-size: 12px; margin: 15px 0 0 0 ; position: absolute; bottom: 0; width: 100%;">Copyright © 2017  All Rights Reserved</p>
</div>

<script>
    function submitForm(){
        var unm = $("#unm").val(), pwd = $("#pwd").val();
        if(unm.length == 0){
            $.messager.alert('操作提示','请输入用户名','warning');
            return false;
        }
        if(pwd.length == 0){
            $.messager.alert('操作提示','请输入密码','warning');
            return false;
        }
        $.ajax({
            url: 'index.php?d=admin&c=Login&m=login_check',
            type:"Post",
            dataType:"json",
            data:{
                unm : unm,
                pwd : pwd,
            },
            cache:false,
            success: function (data) {
                //console.info(data);
                if(data.success){
                    $.messager.alert('系统提示',data.message,'info',function(){
                        window.top.location.href = data.dt;
                    });
                }else{
                    $.messager.alert('系统提示',data.message,'error');
                }
            }
        });
    }
    function clearForm(){
        $('#login_form').form('clear');
    }
</script>
<script>
    $(document).ready(function(){
        $("#main_div").css("margin-top",($(window).height()-400)/2);
        $('#pwd').textbox('textbox').keydown(function (e) {
            if (e.keyCode == 13) {
                submitForm();
            }
        });
    })


</script>
</body>
</html>