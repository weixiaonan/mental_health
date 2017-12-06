
$.extend($.fn.datagrid.methods, {
    editCell: function(jq,param){
        return jq.each(function(){
            var opts = $(this).datagrid('options');
            var fields = $(this).datagrid('getColumnFields',true).concat($(this).datagrid('getColumnFields'));
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor1 = col.editor;
                if (fields[i] != param.field){
                    col.editor = null;
                }
            }
            $(this).datagrid('beginEdit', param.index);
            var ed = $(this).datagrid('getEditor', param);
            if (ed){
                if ($(ed.target).hasClass('textbox-f')){
                    $(ed.target).textbox('textbox').focus();
                } else {
                    $(ed.target).focus();
                }
            }
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor = col.editor1;
            }
        });
    },
    enableCellEditing: function(jq){
        return jq.each(function(){
            var dg = $(this);
            var opts = dg.datagrid('options');
            opts.oldOnClickCell = opts.onClickCell;
            opts.onClickCell = function(index, field){
                if (opts.editIndex != undefined){
                    if (dg.datagrid('validateRow', opts.editIndex)){
                        dg.datagrid('endEdit', opts.editIndex);
                        opts.editIndex = undefined;
                    } else {
                        return;
                    }
                }
                dg.datagrid('selectRow', index).datagrid('editCell', {
                    index: index,
                    field: field
                });
                opts.editIndex = index;
                opts.oldOnClickCell.call(this, index, field);

            }
        });
    }
});

$(function(){
    var obj = $('#consult_dgd').datagrid({
        rownumbers:true,
        fit:true,
        header:'#consult_heard',
     //   toolbar:'#therapist_toolbar',
        pagination:true,
      //  checkOnSelect:false,
        fitColumns:true,
        method:'get',
        pageSize:20,
        url:'index.php?d=admin&c=Consultant_type&m=list_data',
        onBeforeLoad: function (param) {
            $('#consult_dgd').datagrid('loading');

        },
        onLoadSuccess:function(data){

        },
        onLoadError: function (data) {
            $.messager.alert('系统提示','数据加载出错','error');
        },
        onAfterEdit:function(index,row,changes) {
            console.log(index);
            console.log(row);

            console.log(changes);
        }
    }).datagrid('getPager');//enableCellEditing
});




function edit_consultant(id) {

    if(id == 0){
        var row = $('#consult_dgd').datagrid('getSelections');
        if(row.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
        if(row.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
        var ids = [];
        $.each(row, function(index, item){
            ids.push(item.id);
        });
        id = ids[0];
    }
    var url = 'index.php?d=admin&c=Consultant_type&m=edit&id='+id;
    if(id == 'add'){
        url = 'index.php?d=admin&c=Consultant_type&m=add&id='+id;
    }

    $('#com_edit').dialog({
        title: '咨询类型编辑',
        width: 300,
        height: 150,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        buttons: [{
            text: ' 保 存 ',
            iconCls: 'icon-ok',
            handler: function () {
                $.messager.progress();
                $('#consultant_edit_tj').form('submit', {
                    url:'index.php?d=admin&c=Consultant_type&m=save',
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
                                $('#consult_dgd').datagrid('reload');
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
function del_consultants() {
    var ids = [];
    var rows = $('#consult_dgd').datagrid('getSelections');
    if(rows.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
    if(rows.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
    for(var i=0; i<rows.length; i++){
        ids.push(rows[i].id);
    }
    ids = ids.join(',');
    del_consultant(ids);
}

function del_consultant(ids) {
    if(ids){
        $.messager.confirm('操作提示','确定要删除吗？',function(res){
            if(res){
                $.ajax({
                    url: 'index.php?d=admin&c=Consultant_type&m=del',
                    type:"Post",
                    dataType:"json",
                    data:{
                        id_str : ids,
                    },
                    success: function (data) {
                        if(data.success){
                            $.messager.alert('操作提示',data.message,'info',function(){
                                $('#consult_dgd').datagrid("reload");
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
