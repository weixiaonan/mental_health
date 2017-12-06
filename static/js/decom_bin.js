
$(function(){
    var obj = $('#decom_bin_dgd').datagrid({
        rownumbers:true,
        fit:true,
        header:'#decom_bin_heard',
        toolbar:'#decom_bin_toolbar',
        pagination:true,
        checkOnSelect:false,
        fitColumns:true,
        method:'get',
        pageSize:20,
        url:'index.php?d=admin&c=Decom_bin&m=list_data',
        onBeforeLoad: function (param) {
            $('#decom_bin_dgd').datagrid('loading');

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
        var row = $('#decom_bin_dgd').datagrid('getSelections');
        if(row.length < 1){$.messager.alert('操作提示',"请选择一行后再操作！",'warning');return false;}
        if(row.length > 1){$.messager.alert('操作提示',"只能选择一行操作！",'warning');return false;}
        var ids = [];
        $.each(row, function(index, item){
            ids.push(item.id);
        });
        id = ids[0];
    }
    var url = 'index.php?d=admin&c=Decom_bin&m=edit&id='+id;
    if(id == 'add'){
        url = 'index.php?d=admin&c=Decom_bin&m=add&id='+id;
    }

    $('#com_edit').dialog({
        title: '编辑',
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
                $('#decom_bin_edit_tj').form('submit', {
                    url:'index.php?d=admin&c=Decom_bin&m=save',
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
                                $('#decom_bin_dgd').datagrid('reload');
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