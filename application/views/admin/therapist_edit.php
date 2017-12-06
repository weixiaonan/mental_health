<style>

   .avatar img {
        border-radius: 100px;
        height: 120px;
        overflow: hidden;
        width: 120px;
    }
</style>
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="<?=$form_id?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?=$value['id']?>">
        <table cellpadding="5">
            <tr>
                <td class="input_tit60">头像:</td>
                <td class="avatar">
                    <img class="" src="<?=$value['avatar'] != '' ? $value['avatar']:'static/images/user.jpg'?>" alt="">
                    <br>
                    <input class="easyui-filebox" name="avatar_upfile" data-options="buttonIcon:'icon-folder-search',buttonText:'选择图片',accept:'image/*'" style="width:300px">
                </td>
            </tr>

            <tr>
                <td class="input_tit60">姓名:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="value[name]" value="<?=$value['name']?>"  data-options="required:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">性别:</td>
                <td>
                     <span class="radioSpan">
                         <input type="radio" name="value[sex]" <?php if($value['sex'] == 1) echo 'checked'; ?> value="1">男</input>
                         <input type="radio" name="value[sex]" <?php if($value['sex'] == 0) echo 'checked'; ?> value="0">女</input>

                     </span>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">出生年月:</td>
                <td>
                    <input  id="dd"  name="value[birth]" value="<?=$value['birth']?>" type= "text" class= "easyui-datebox" required ="required"> </input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">积分:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="value[points]" value="<?=$value['points']?>"  data-options=""></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">擅长类型:</td>
                <td>
                    <input style="width: 400px;"  id="good_at_type" class="easyui-combobox" name="good_at_type_ids[]"
                           data-options="multiple:true,editable:false,required:true,valueField:'id',textField:'title',url:'index.php?d=admin&c=Therapist&m=get_consult_type'" />

                </td>
            </tr>




            <tr>
                <td class="input_tit60">证书类型:</td>
                <td>
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="value[certificate_type]" value="<?=$value['certificate_type']?>" data-options=""></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">证书名称:</td>
                <td>
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="value[certificate_title]" value="<?=$value['certificate_title']?>" data-options=""></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">是否认证:</td>
                <td>
                     <span class="radioSpan">
                         <input type="radio" name="value[status]" <?php if($value['status'] == 1) echo 'checked'; ?> value="1">是</input>
                         <input type="radio" name="value[status]" <?php if($value['status'] == 0) echo 'checked'; ?> value="0">否</input>

                     </span>
                </td>
            </tr>


            <tr>
                <td class="input_tit60">经历:</td>
                <td>
                    <input style="width: 400px;" class="easyui-textbox" type="text" name="value[experience]" value="<?=$value['experience']?>" data-options=""></input>
                </td>
            </tr>
            <tr>
                <td class="input_tit60">简介:</td>
                <td>
                    <input style="width: 400px;height:80px;" class="easyui-textbox" type="text" data-options="multiline:true" name="value[info]" value="<?=$value['info']?>"></input>
                </td>
            </tr>
        </table>

    </form>
</div>

<script>
    $(function () {
        $('#good_at_type').combobox({
            onLoadSuccess: function () {
                var good_at_type_ids = "<?=$value['good_at_type_ids']?>";
                if(good_at_type_ids != ""){
                    var ids = good_at_type_ids.split(",");
                    $('#good_at_type').combobox('setValues', ids);
                }

            }
        })

    })
</script>