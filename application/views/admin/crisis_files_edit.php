
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="<?=$form_id?>" method="post">
        <input type="hidden" name="id" value="<?=$value['id']?>">
        <table cellpadding="5">

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
                         <input type="radio" name="value[sex]" <?php if($value['sex'] == 1) echo 'checked'; ?> value="1">男</input>&nbsp;&nbsp;
                         <input type="radio" name="value[sex]" <?php if($value['sex'] == 0) echo 'checked'; ?> value="0">女</input>
                     </span>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">职位:</td>
                <td>
                    <input class="easyui-textbox" type="text" name="value[position]" value="<?=$value['position']?>"  data-options=""></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">评分:</td>
                <td>
                    <input class="easyui-numberbox" type="text" name="value[score]" value="<?=$value['score']?>"  data-options=""></input>
                </td>
            </tr>



            <tr>
                <td class="input_tit60">测评记录分析:</td>
                <td>
                   <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[pe]" value="<?=$value['pe']?>"  data-options="multiline:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">在线咨询内容:</td>
                <td>
                    <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[online_consult]" value="<?=$value['online_consult']?>"  data-options="multiline:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">面询内容:</td>
                <td>
                    <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[interview_content]" value="<?=$value['interview_content']?>"  data-options="multiline:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">评价及分析:</td>
                <td>
                    <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[eval_and_analysis]" value="<?=$value['eval_and_analysis']?>"  data-options="multiline:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">同事评价:</td>
                <td>
                    <input style="width: 400px;height:80px;"  class="easyui-textbox" type="text" name="value[colleague_eval]" value="<?=$value['colleague_eval']?>"  data-options="multiline:true"></input>
                </td>
            </tr>

            <tr>
                <td class="input_tit60">档案状态:</td>
                <td>
                    <span class="radioSpan">
                        <?php foreach ($this->status as $k=>$v) {?>
                            <input type="checkbox" name="value[status][]" <?php if(in_array($k, $status)) echo 'checked'; ?> value="<?=$k?>"><?=$v?></input> &nbsp;&nbsp;
                        <?php } ?>
                     </span>
                </td>
            </tr>

        </table>

    </form>
</div>