<div id="therapist_heard" style="height:35px;padding:2px 5px;">
    <?php if(role_check(51)){?>
    <a href="#" onclick="edit_therapist()" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
    <?php } if(role_check(53)){?>
    <a href="#" onclick="del_therapists()" class="easyui-linkbutton" iconCls="icon-remove" plain="true">批量删除</a>
    <?php } ?>
</div>
<div id="therapist_toolbar">
    姓名:<input class="easyui-textbox" type="text" id="therapist_name" name="name" data-options="prompt:'模糊查找'" />
    擅长类型:
    <input id="good_at_type_ids" class="easyui-combobox" name="good_at_type[]"
           data-options="multiple:true,editable:false,valueField:'id',textField:'title',url:'index.php?d=admin&c=Therapist&m=get_consult_type'" />


    <a href="javascript:void(0);" style="margin:0px 10px 0px 15px;width: 78px;" class="easyui-linkbutton" iconCls="icon-search" onclick="therapists_search();"> 搜 索 </a>

</div>
<table id="therapist_dgd">
    <thead>
    <tr>
        <th data-options="field:'id',checkbox:true"></th>
        <th data-options="field:'name'" width="80">姓名</th>
        <th data-options="field:'sex'" width="30">性别</th>
        <th data-options="field:'birth'" width="60">出生年月</th>
        <th data-options="field:'good_at_type'" width="150">擅长类型</th>
        <th data-options="field:'certificate_type'" width="150">证书类型</th>
        <th data-options="field:'certificate_title'" width="150">证书名称</th>
        <th data-options="field:'info'" width="150">简介</th>
        <th data-options="field:'status',align:'center'" width="50">状态</th>
        <th data-options="field:'experience'" width="120">经历</th>
        <th data-options="field:'points'" width="30">积分</th>
        <th data-options="field:'button',width:150,align:'center',formatter:therapist_operate">操作</th>
    </tr>
    </thead>
</table>

<script>
    function therapist_operate(value,row,index){
        var btns  = '';
        <?php if(role_check(53)){?>
            btns += "<a href='#' onclick='del_therapist("+row.id+")'  class='therapist-delete button-danger'>删除</a>&nbsp;&nbsp;";
        <?php } if(role_check(52)){?>
            btns += "<a href='#' onclick='edit_therapist("+row.id+")'  class='therapist-edit button-default'>编辑</a>&nbsp;&nbsp;";
        <?php } if(role_check(58)){?>

        if(row.bind_uid > 0){
            btns += "<a href='#' onclick='bind_therapist("+row.bind_uid+", "+row.id+")'   class='therapist-edit button-default'>解绑</a>&nbsp;&nbsp;";
        }else{
            btns += "<a href='#' onclick='bind_therapist("+row.bind_uid+", "+row.id+")'  class='therapist-edit button-info'>绑定</a>&nbsp;&nbsp;";
        }

        <?php } ?>

        return btns;
    }
</script>

<script type="text/javascript" src="static/js/therapist.js"></script>
