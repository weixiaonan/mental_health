<style>
    .input_tit{
        min-width: 30px;
        max-width: 180px;
        text-align: right;
    }
    .input_tit60{
        min-width: 60px;
        max-width: 180px;
        text-align: right;
    }
    .dialog-button{ text-align: center; }
</style>
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="role<?=$show_status?>_tj" method="post">
        <input type="hidden" name="role_id" value="<?=$dt['id']?>">
        <table cellpadding="5">
            <tr>
                <td class="input_tit60">状态:</td>
                <td>
                    <select class="easyui-combobox" panelHeight="auto" name="role[status]" style="width: 120px;">
                        <option value="1" <?php if($dt['status']=="1"){echo " selected ";}?> >启用</option>
                        <option value="2" <?php if($dt['status']=="2"){echo " selected ";}?> >禁用</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">角色名称:</td>
                <td colspan="3">
                    <input style="width: 300px;" class="easyui-textbox" type="text" name="role[name]" value="<?=$dt['name']?>" data-options="required:true"></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">备注:</td>
                <td colspan="3">
                    <input style="width: 300px;" class="easyui-textbox" type="text" name="role[remark]" value="<?=$dt['remark']?>"></input>
                </td>
            </tr>
        </table>

        </form>
</div>