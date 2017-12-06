<div id="consult_heard" style="height:35px;padding:2px 5px;">
    <?php if(role_check(36)){?>
        <a href="#" onclick="edit_consultant('add')" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
    <?php } if(role_check(37)){?>
        <a href="#" onclick="edit_consultant(0)"class="easyui-linkbutton" iconCls="icon-edit" plain="true">编辑</a>
    <?php } if(role_check(39)){?>
        <a href="#" onclick="del_consultants()" class="easyui-linkbutton" iconCls="icon-remove" plain="true">批量删除</a>
    <?php } ?>
</div>

<table id="consult_dgd">
    <thead>
    <tr>
        <th data-options="field:'id',checkbox:true"></th>
        <th data-options="field:'title',editor:'text'" width="80">类型名称</th>

        <th data-options="field:'addtime'" width="120">添加时间</th>

    </tr>
    </thead>
</table>

<script type="text/javascript" src="static/js/consultant_type.js"></script>
