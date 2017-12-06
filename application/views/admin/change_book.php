


<div>
    <div class="book_table">

        <div class="notice">
            注：<div class="occupied">&nbsp;&nbsp;</div>为已被预约
            <div class="outtime">&nbsp;&nbsp;</div>为已预约其他咨询师
            <div class="useable">&nbsp;&nbsp;</div>为可预约
        </div>
        <table>
            <tbody id="resourceTable">
            <!-- 以下部分应由js动态生成 -->
            <!--    <tr>
                 <th></th>
                 <th>羽毛球馆1</th>
                 <th>羽毛球馆2</th>
                 <th>羽毛球馆3</th>
                 <th>羽毛球馆4</th>
                 <th>羽毛球馆5</th>
                 <th>羽毛球馆6</th>
                 <th>羽毛球馆7</th>
                 <th>羽毛球馆8</th>
                 <th>羽毛球馆9</th>
             </tr>
               <tr>
                      <th>9:00-10:00</th>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="occupied"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                  </tr>
                  <tr>
                      <th>10:00-11:00</th>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="occupied"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                      <td class="outtime"></td>
                  </tr>
                  <tr>
                      <th>11:00-12:00</th>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="useable"></td>
                      <td class="occupied"></td>
                      <td class="occupied"></td>
                  </tr>-->
            <?=$table_html?>
            </tbody>
        </table>
        <div class="btn-area">
            <a class="easyui-linkbutton button-success save-book" name="btn-my" >提交预约</a>
           <!-- <a class="easyui-linkbutton button-success" name="btn-my" >查看我的预约</a>-->
        </div>
        <div class="reserveResult">
        </div>
    </div>
</div>
<link href="static/css/bookview.css" rel="stylesheet" type="text/css">

<script>
    $(function () {
        //为所有可以预约的td绑定一个click事件，即点击表示选中，再点一次恢复原来状态，直到点击预约按钮预约成功后，解除click事件，并由“useable”变为“occupied”
        $("tr .useable").bind("click", function() {
            $(this).toggleClass("selected");
        });
        
        $(".save-book").click(function () {
            var time_count = $(".selected").length;
            if(time_count < 1)
            {
                $.messager.alert('操作提示', '未选中一个预约时间','error');
                return false;
            }
            var times = [];
            var time_str = '';
            $(".selected").each( function (k,v) {
                var data = $(this).data("time");
                times.push(data)
                time_str += data + '<br>';
            })
            $.messager.confirm('操作提示','确定要预约选中的这'+time_count+'个时间吗？',function(res){
                if(res){
                    $.ajax({
                        url: 'index.php?d=admin&c=Book&m=book_save',
                        type:"Post",
                        dataType:"json",
                        data:{
                            time_str : times.join(','),
                            id:'<?=$id?>'
                        },
                        success: function (data) {
                            if(data.success){
                                $.messager.alert('操作提示',data.message,'info',function(){
                                    $('#com_edit').dialog('refresh', 'index.php?d=admin&c=Book&m=change_book&id='+'<?=$id?>');
                                });
                            }else{
                                $.messager.alert('操作提示',data.message,'error');
                            }
                        }
                    });
                }
            });

        })


    })
</script>