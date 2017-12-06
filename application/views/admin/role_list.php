<table id="role_dgd">
    <thead>
    <tr>
        <th data-options="field:'id',checkbox:true"></th>
        <th data-options="field:'pk_id',align:'center'" width="30">ID</th>
        <th data-options="field:'name'" width="120">角色名称</th>
        <th data-options="field:'status',align:'center'" width="80">状态</th>
        <th data-options="field:'remark'" width="120">备注</th>
        <th data-options="field:'button',width:120,align:'center',formatter:role_operate">操作</th>
    </tr>
    </thead>
</table>

<div id="role_heard" style="height:35px;padding:2px 5px;">
    <?php if(role_check(15)){?>
    <a href="#" onclick="edit_role()" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
    <?php } if(role_check(16)){?>
    <a href="#" onclick="edit_role(0)"class="easyui-linkbutton" iconCls="icon-edit" plain="true">编辑</a>
    <?php } if(role_check(18)){?>
    <a href="#" onclick="del_roles()" class="easyui-linkbutton" iconCls="icon-remove" plain="true">批量删除</a>
    <?php }?>
</div>

<div id="role_toolbar">
    状态:
    <select class="easyui-combobox" panelHeight="auto" name="role_status" style="width:120px">
        <option value="">&nbsp;</option>
        <option value="1">启用</option>
        <option value="2">禁用</option>
    </select>
    &nbsp;&nbsp;角色名称:<input class="easyui-textbox" type="text" name="role_name" data-options="prompt:'模糊查找'" />

    <a href="javascript:void(0);" style="margin:0px 10px 0px 15px;width: 78px;" class="easyui-linkbutton" iconCls="icon-search" onclick="role_search();"> 搜 索 </a>
</div>
<script>
    function role_operate(value,row,index){
        var btns  = '';
        <?php if(role_check(18)){?>
            btns += "<a href='#' onclick='del_role("+row.id+")'  class='role-button-delete button-danger'>删除</a>&nbsp;&nbsp;";
        <?php } if(role_check(16)){?>
            btns += "<a href='#' onclick='edit_role("+row.id+")'  class='role-button-edit button-default'>编辑</a>&nbsp;&nbsp;";
        <?php } if(role_check(17)){?>
            btns += "<a href='#' onclick='set_role("+row.id+")'  class='role-button-edit button-info'>设置权限</a>";
        <?php }?>
        return btns;
    }

    function role_search(){
        var role_status = $("input[name='role_status']").val().length > 0 ? $("input[name='role_status']").val() : "";
        var role_name = $("input[name='role_name']").val().length > 0 ? $("input[name='role_name']").val() : "";
        $('#role_dgd').datagrid('load',{
                'role_status':role_status,
                'role_name':role_name
            });
    }


    function edit_role(id){
        if(id == 0){
            var row = $('#role_dgd').datagrid('getSelections');
            if(row.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
            if(row.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
            var ids = [];
            $.each(row, function(index, item){
                ids.push(item.id);
            });
            id = ids[0];
        }

        $('#com_edit').dialog({
            title: '信息编辑',
            width: 600,
            height: 500,
            closed: false,
            cache: false,
            href: 'index.php?d=admin&c=Role&m=role_edit&id='+id,
            modal: true,
            buttons: [{
                text: ' 保 存 ',
                iconCls: 'icon-ok',
                handler: function () {
                    $.messager.progress();
                    $('#role_edit_tj').form('submit', {
                        url:'index.php?d=admin&c=Role&m=role_save',
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
                                    $('#role_dgd').datagrid('reload');
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


    function del_roles() {
        var ids = [];
        var rows = $('#role_dgd').datagrid('getSelections');
        if(rows.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
        for(var i=0; i<rows.length; i++){
            ids.push(rows[i].id);
        }
        ids = ids.join(',');
        del_role(ids);
    }
    function del_role(ids) {
        if(ids){
            $.messager.confirm('操作提示','确定要删除吗？',function(res){
                if(res){
                    $.ajax({
                        url: 'index.php?d=admin&c=Role&m=role_del',
                        type:"Post",
                        dataType:"json",
                        data:{
                            id_str : ids,
                        },
                        success: function (data) {
                            if(data.success){
                                $.messager.alert('操作提示',data.message,'info',function(){
                                    $('#role_dgd').datagrid("reload");
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


    function set_role(id) {
        if (id) {
            $('#com_edit').dialog({
                title: '设置权限',
                width: 600,
                height: 500,
                closed: false,
                cache: false,
                href: 'index.php?d=admin&c=Role&m=role_set&id=' + id,
                modal: true,
                buttons: [{
                    text: ' 保 存 ',
                    iconCls: 'icon-ok',
                    handler: function () {
                        role_getChecked();
                        $.messager.progress();
                        $('#role_set_tj').form('submit', {
                            url: 'index.php?d=admin&c=Role&m=role_set_save',
                            onSubmit: function () {
                                var isValid = $(this).form('validate');
                                if (!isValid) {
                                    $.messager.progress('close');
                                }
                                return isValid;	// 返回false终止表单提交
                            },
                            success: function (data) {
                                $.messager.progress('close');
                                var data = eval('(' + data + ')');
                                if (data.success) {
                                    $.messager.alert('操作提示', data.message, 'info', function () {
                                        $('#com_edit').dialog("close");
                                        $('#role_dgd').datagrid('reload');
                                    });
                                } else {
                                    $.messager.alert('操作提示', data.message, 'error');
                                }
                            }
                        });
                    }
                },
                    {
                        text: '取消',
                        handler: function () {
                            $('#com_edit').dialog("close");
                        }
                    }
                ]
            });


        }
    }


    $(function(){

        var obj = $('#role_dgd').datagrid({
            rownumbers:true,
            fit:true,
            header:'#role_heard',
            toolbar:'#role_toolbar',
            pagination:true,
            checkOnSelect:false,
            fitColumns:true,
            method:'get',
            pageSize:20,
            url:'index.php?d=admin&c=Role&m=role_list_data',
            onBeforeLoad: function (param) {
                $('#role_dgd').datagrid('loading');
            },
            onLoadSuccess:function(data){

                $('.role-button-delete').linkbutton({

                });
                $('.role-button-edit').linkbutton({
                });



            },
            onLoadError: function (data) {
                $.messager.alert('系统提示','数据加载出错','error');
            }
        }).datagrid('getPager');
    });

</script>
