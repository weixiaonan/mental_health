<div id="chat_heard" style="height:35px;padding:2px 5px;">

       <!-- <a href="#" onclick="my_chat()" class="easyui-linkbutton" iconCls="icon-add" plain="true">咨询</a>-->

</div>
<table id="my_book_dgd">
    <thead>
    <tr>
        <th data-options="field:'id',checkbox:true"></th>
        <th data-options="field:'book_date'" width="40">日期</th>
        <th data-options="field:'book_time'" width="30">时间</th>
        <th data-options="field:'therapist_name'" width="30">咨询师</th>
        <th data-options="field:'addtime'" width="40">预约时间</th>
        <th data-options="field:'status'" width="20">状态</th>
        <th data-options="field:'button',width:50,align:'left',formatter:zx_operate">操作</th>

    </tr>
    </thead>
</table>

<script>


        $(function(){
            var obj = $('#my_book_dgd').datagrid({
                rownumbers:true,
                fit:true,
                header:'#chat_heard',
              //  toolbar:'#therapist_toolbar',
                pagination:true,
                checkOnSelect:false,
                fitColumns:true,
                method:'get',
                pageSize:20,
                url:'index.php?d=admin&c=Book&m=my_book_data',
                onBeforeLoad: function (param) {
                    $('#my_book_dgd').datagrid('loading');

                },
                onLoadSuccess:function(data){
                    $('.start-chat').linkbutton({
                    });
                },
                onLoadError: function (data) {
                    $.messager.alert('系统提示','数据加载出错','error');
                }
            }).datagrid('getPager');

        });




function zx_operate(value,row,index) {
    if(row.can_zx == 1)
    {
        var btns = '<a href="#" onclick="my_chat('+ row.therapist_id +',\''+row.therapist_name+'\')" class="start-chat button-info"  plain="true">咨询</a>';
        if(row.is_online > 0)
        {
            btns += '(在线)';
        }else{
            btns += '(离线)';
        }
        return btns;
    }
    
}
</script>
