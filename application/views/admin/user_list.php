<table id="user_dgd" >
    <thead>
    <tr>
        <th data-options="field:'id',checkbox:true"></th>
        <th data-options="field:'pk_id',align:'center'" width="30">ID</th>
        <th data-options="field:'act_type',align:'center'" width="80">账号类型</th>
        <th data-options="field:'department'" width="120">所属部门</th>
        <th data-options="field:'username',align:'center'" width="80">用户名</th>
        <th data-options="field:'nickname'" width="120">姓名</th>
        <th data-options="field:'lastlogin_time',align:'center'" width="80">最近登录时间</th>
        <th data-options="field:'ip',align:'center'" width="100">最近登录IP</th>
        <th data-options="field:'status',formatter:user_status,width:80,align:'center'">状态</th>
        <th data-options="field:'beizhu'" width="120">备注</th>
        <th data-options="field:'button',width:120,align:'center',formatter:user_operate">操作</th>

    </tr>
    </thead>
</table>

<div id="ht" style="height:35px;padding:2px 5px;">
    <?php if(role_check(11)){?>
    <a href="#" onclick="edit_user()" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
    <?php } if(role_check(12)){?>
    <a href="#" onclick="edit_user(0)"class="easyui-linkbutton" iconCls="icon-edit" plain="true">编辑</a>
    <?php } if(role_check(13)){?>
    <a href="#" onclick="del_users()" class="easyui-linkbutton" iconCls="icon-remove" plain="true">批量删除</a>
    <?php }?>
</div>
<div id="tb">


    账号类型:
    <select class="easyui-combobox" panelHeight="auto" name="user_act_type" style="width:120px" data-options="
					url:'index.php?d=admin&c=User&m=user_role_list',
					method:'post',
					valueField:'rid',
					textField:'rname'
			">
    </select>
    部门或昵称:<input class="easyui-textbox" type="text" name="user_nkname" data-options="prompt:'模糊查找'" />
    &nbsp;&nbsp;用户名:<input class="easyui-textbox" type="text" name="user_uname" data-options="prompt:'精确查找'" />
    <a href="#" onclick="user_search()" class="easyui-linkbutton" iconCls="icon-search">搜索</a>

</div>

<div id="ft" style="padding:2px 8px;">

</div>

<script>
    function user_operate(value,row,index){
        var btn_str  = '';
        <?php  if(role_check(13)){?>
            btn_str += "<a href='#' onclick='del_user("+row.id+")'  class='user-button-delete button-danger'>删除</a> &nbsp;&nbsp;";
        <?php } if(role_check(12)){?>
            btn_str += "<a href='#' onclick='edit_user("+row.id+")'  class='user-button-edit button-default'>编辑</a>";
        <?php }?>
            return btn_str;
    }

    function user_status(value,row,index){
        var status = row.status;
        var id = row.id;
        var html   = '';
        <?php if(role_check(12)){?>
            if(status == 1){
                html   = '<input data-id="'+id+'" checked class="user-switchbutton" onText="已锁定" offText="正常" >';
            }else{
                html   = '<input data-id="'+id+'" class="user-switchbutton" onText="已锁定" offText="正常" >';
            }
        <?php }else{?>
                html   = status == 1 ? '已锁定' : '正常';
    <?php }?>
        return html;
    }

    function user_search(){
        var user_act_type = $("input[name='user_act_type']").val().length > 0 ? $("input[name='user_act_type']").val() : "";
        var user_nkname = $("input[name='user_nkname']").val().length > 0 ? $("input[name='user_nkname']").val() : "";
        var user_uname = $("input[name='user_uname']").val().length > 0 ? $("input[name='user_uname']").val() : "";
        $('#user_dgd').datagrid('load', {
                'user_act_type':user_act_type,
                'user_nkname':user_nkname,
                'user_uname':user_uname
        });
    }
    function del_users() {
        var ids = [];
        var rows = $('#user_dgd').datagrid('getSelections');
        if(rows.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
        for(var i=0; i<rows.length; i++){
            ids.push(rows[i].id);
        }
        ids = ids.join(',');
        del_user(ids);
    }
    function del_user(ids) {
        if(ids){
            $.messager.confirm('操作提示','确定要删除吗？',function(res){
                if(res){
                    $.ajax({
                        url: 'index.php?d=admin&c=User&m=user_del',
                        type:"Post",
                        dataType:"json",
                        data:{
                            id_str : ids,
                        },
                        success: function (data) {
                            if(data.success){
                                $.messager.alert('操作提示',data.message,'info',function(){
                                    $('#user_dgd').datagrid("reload");
                                });
                            }else{
                                $.messager.alert('操作提示',data.message,'error');
                            }
                        }
                    });
                }
            });
        }
    }

    function edit_user(id){
        if(id == 0){
            var row = $('#user_dgd').datagrid('getSelections');
            if(row.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
            if(row.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
            var ids = [];
            $.each(row, function(index, item){
                ids.push(item.id);
            });
            id = ids[0];
        }

            $('#com_edit').dialog({
                title: '用户信息编辑',
                width: 600,
                height: 500,
                closed: false,
                cache: false,
                href: 'index.php?d=admin&c=User&m=user_edit&id='+id,
                modal: true,
                buttons: [{
                    text: ' 保 存 ',
                    iconCls: 'icon-ok',
                    handler: function () {
                        $.messager.progress();
                        $('#user_edit_tj').form('submit', {
                            url:'index.php?d=admin&c=User&m=user_save',
                            onSubmit: function(){
                                var isValid = $(this).form('validate');
                                if (!isValid){
                                    $.messager.progress('close');
                                }
                                return isValid;	// 返回false终止表单提交
                            },
                            success:function(data){
                                $.messager.progress('close');
                                var data = eval('(' + data + ')');
                                if(data.success){
                                    $.messager.alert('操作提示',data.message,'info',function () {
                                        $('#com_edit').dialog("close");
                                        $('#user_dgd').datagrid('reload');
                                    });
                                }else{
                                    $.messager.alert('操作提示',data.message,'error');
                                }
                            }
                        });
                    }
                },
                    {
                        text:'取消',
                        handler:function(){
                           $('#com_edit').dialog("close");
                        }}
                ]
            });


    }


    $(function(){

        var obj = $('#user_dgd').datagrid({

            rownumbers:true,
            fit:true,
            header:'#ht',
            footer:'#ft',
            toolbar:'#tb',
            pagination:true,
            checkOnSelect:false,
            fitColumns:true,
            method:'get',
            pageSize:20,
            url:'index.php?d=admin&c=User&m=user_list_data',
            onBeforeLoad: function (param) {
                $('#user_dgd').datagrid('loading');
            },
            onLoadSuccess:function(data){

                $('.user-button-delete').linkbutton({

                });
                $('.user-button-edit').linkbutton({
                });
                $('.user-switchbutton').switchbutton({
                    height:23,
                    onChange: function(checked){
                        var id = $(this).data('id');
                        var status = checked==false ? 0 : 1;
                        if (id == ''){
                            $.messager.alert('操作提示', '操作失败！','error');
                            return false;
                        }
                        $.ajax({
                            url: 'index.php?d=admin&c=User&m=change_status',
                            type:"Post",
                            dataType:"json",
                            data:{
                                id : id,
                                status : status
                            },
                            success: function (data) {
                                if(data.success){
                                    $.messager.alert('操作提示',data.message,'info',function(){

                                    });
                                }else{
                                    $.messager.alert('操作提示',data.message,'error');
                                }
                            }
                        });
                    }

                });



            },
            onLoadError: function (data) {

                console.log(data.responseText);
                $.messager.alert('系统提示','数据加载出错','error');
            }
        }).datagrid('getPager');
    });
</script>