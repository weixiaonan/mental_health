<table id="dg" title="Client Side Pagination"  style="width:auto;height:auto" data-options="">
    <thead>
    <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th data-options="field:'itemid',sortable:true,width:80">Item ID</th>
        <th data-options="field:'productid',width:100">Product</th>
        <th data-options="field:'listprice',sortable:true,width:80,align:'right'">List Price</th>
        <th data-options="field:'unitcost',width:80,align:'right'">Unit Cost</th>
        <th data-options="field:'attr1',width:240">Attribute</th>
        <th data-options="field:'status',width:60,align:'center'">Status</th>
        <th data-options="field:'button',width:120,align:'center',formatter:button">Button</th>
    </tr>
    </thead>
</table>

<div id="tb">
    Date From: <input class="easyui-datebox" style="width:110px">
    To: <input class="easyui-datebox" style="width:110px">
    Language:
    <select class="easyui-combobox" panelHeight="auto" style="width:100px">
        <option value="java">Java</option>
        <option value="c">C</option>
        <option value="basic">Basic</option>
        <option value="perl">Perl</option>
        <option value="python">Python</option>
    </select>
    <a href="#" class="easyui-linkbutton" iconCls="icon-search">Search</a>
</div>
<div id="ft" style="padding:2px 5px;">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true">编辑</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cut" plain="true">剪切</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true">删除</a>
</div>

<script>
    function button(value,row,index){

        return "<a href='#' data-options=id:'"+row.itemid+"' class='button-delete button-danger'>删除</a> <a href='#' class='button-edit button-default'>编辑</a>";
    }

    $(function(){

        $('#dg').datagrid({
            rownumbers:true,
            fit:true,
            toolbar:'#tb',
            footer:'#ft',
            pagination:true,
            method:'get',
            pageSize:20,
            url:'datagrid_data1.json',
            onBeforeLoad: function (param) {
                $('#dg').datagrid('loading');
            },
            onLoadSuccess:function(){
                $('.button-delete').linkbutton({

                });
                $('.button-edit').linkbutton({
                });

                $('.operate-edit').on('click', function(){
                    var a=$(this).linkbutton("options");
                    alert("edit "+a.value);
                });
                $('.button-delete').on('click', function(){
                    var a=$(this).linkbutton("options");
                    alert("delete "+a.id);
                });
            },
            onLoadError: function () {
                $.messager.alert('系统提示','数据加载出错','error');
            }
        }).datagrid('getPager');
    });
</script>