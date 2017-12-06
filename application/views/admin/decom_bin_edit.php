
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="<?=$form_id?>" method="post">
        <input type="hidden" name="id" value="<?=$value['id']?>">
        <table cellpadding="5">

            <tr>
                <td class="input_tit60">名称:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="value[title]" value="<?=$value['title']?>"  data-options="required:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">内容:</td>
                <td>
                  <!--  <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[content]" value="<?/*=$value['content']*/?>"  data-options="multiline:true"></input>
                 -->
                    <textarea name="value[content]" style="width: 250px;height:80px;" id="<?=$this->datagrid?>content" class="form-control"><?=$value['content']?></textarea>
                </td>
            </tr>

        </table>

    </form>
</div>

<script>
    KindEditor.ready(function(K) {
        K.create('#<?=$this->datagrid?>content',{
            urlType :'relative',
            width:"100%",
            items : [
                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'image', 'link']
            ,afterChange:function(){
                this.sync();
            },afterBlur:function(){
                this.sync();
            }
        });
    });
</script>


