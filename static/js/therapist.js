$(function(){
    var obj = $('#therapist_dgd').datagrid({
        rownumbers:true,
        fit:true,
        header:'#therapist_heard',
        toolbar:'#therapist_toolbar',
        pagination:true,
        checkOnSelect:false,
        fitColumns:true,
        method:'get',
        pageSize:20,
        url:'index.php?d=admin&c=Therapist&m=list_data',
        onBeforeLoad: function (param) {
            $('#therapist_dgd').datagrid('loading');

            // fit:true,
        },
        onLoadSuccess:function(data){
           // $('#therapist_dgd').datagrid({fit:true});
            $('.therapist-delete').linkbutton({

            });
            $('.therapist-edit').linkbutton({
            });

        },
        onLoadError: function (data) {
            $.messager.alert('系统提示','数据加载出错','error');
        }
    }).datagrid('getPager');
});




function edit_therapist(id) {
    if(id == 0){
        var row = $('#therapist_dgd').datagrid('getSelections');
        if(row.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
        if(row.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
        var ids = [];
        $.each(row, function(index, item){
            ids.push(item.id);
        });
        id = ids[0];
    }

    $('#com_edit').dialog({
        title: '咨询师编辑',
        width: 600,
        height: 650,
        closed: false,
        cache: false,
        href: 'index.php?d=admin&c=Therapist&m=therapist_edit&id='+id,
        modal: true,
        top: 150,
        buttons: [{
            text: ' 保 存 ',
            iconCls: 'icon-ok',
            handler: function () {
                $.messager.progress();
                $('#therapist_edit_tj').form('submit', {
                    url:'index.php?d=admin&c=Therapist&m=therapist_save',
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
                                $('#therapist_dgd').datagrid('reload');
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
function del_therapists() {
    var ids = [];
    var rows = $('#therapist_dgd').datagrid('getSelections');
    if(rows.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
    for(var i=0; i<rows.length; i++){
        ids.push(rows[i].id);
    }
    ids = ids.join(',');
    del_therapist(ids);
}

function del_therapist(ids) {
    if(ids){
        $.messager.confirm('操作提示','确定要删除吗？',function(res){
            if(res){
                $.ajax({
                    url: 'index.php?d=admin&c=Therapist&m=therapist_del',
                    type:"Post",
                    dataType:"json",
                    data:{
                        id_str : ids,
                    },
                    success: function (data) {
                        if(data.success){
                            $.messager.alert('操作提示',data.message,'info',function(){
                                $('#therapist_dgd').datagrid("reload");
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

function therapists_search() {
    var therapist_name = $("#therapist_name").val().length > 0 ? $("#therapist_name").val() : "";
    var good_at_type = '';
    $("input[name='good_at_type[]']").each(function(i){
        if($(this).val() != "") good_at_type += $(this).val() + ",";
    });
    $('#therapist_dgd').datagrid('load',{
        'name':therapist_name,
        'good_at_type':good_at_type
    });
}

function bind_therapist(uid, tid) {
    var show_txt = "确定要绑定当前登录账户吗？";
    if( uid>0 )
    {
        show_txt = "确定要解除绑定吗？";
    }
    $.messager.confirm('操作提示',show_txt,function(res){
        if(res){
            $.ajax({
                url: 'index.php?d=admin&c=Therapist&m=bind_therapist&tid='+tid,
                type:"Post",
                dataType:"json",
                data:{
                    uid : uid,
                    tid : tid
                },
                success: function (data) {
                    if(data.success){
                        $.messager.alert('操作提示',data.message,'info',function(){
                            $('#therapist_dgd').datagrid("reload");
                        });
                    }else{
                        $.messager.alert('操作提示',data.message,'error');
                    }
                }
            });
        }
    });
    
}
