
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="<?=$form_id?>" method="post">
        <input type="hidden" name="user_id" value="<?=$user['id']?>">
        <table cellpadding="5">
            <tr>
                <td class="input_tit60">账号类型:</td>
                <td>
                    <select  <?php if($type != ''){echo 'disabled="disabled"'; }?> class="easyui-combobox" panelHeight="auto" name="user[act_type]" id="u_act_type" style="width: 120px;">
                        <?=getSelect($role_list, $user['act_type'],'key')?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">所属部门:</td>
                <td colspan="3">
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="user[department]" <?php if($type != ''){echo "readonly='readonly'";}?> value="<?=$user['department']?>" data-options="required:true"></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">用户名:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="user[username]" value="<?=$user['username']?>" <?php if($user['id']>0){echo "readonly='readonly'";}?> data-options="required:true,prompt:'保存后不可更改'"></input>
                </td>
                <td class="input_tit60">登录密码:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="user[password]" data-options="<?php if($user['id']>0){echo "prompt:'留空密码不变'";}else{echo "prompt:'默认123456'";}?>"></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">姓名:</td>
                <td colspan="3">
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="user[nickname]" value="<?=$user['nickname']?>" data-options="required:true"></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">备注:</td>
                <td colspan="3">
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="user[beizhu]" value="<?=$user['beizhu']?>"></input>
                </td>
            </tr>
        </table>

    </form>
</div>