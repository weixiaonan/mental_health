<link href="static/css/therapist.css" rel="stylesheet" type="text/css">


<div class="tabbox" id="tabbox">
    <div class="listmenu" style="display: none">
        <ul>
            <li id="one1" onclick="setTab('one',1)" class="off">英美外教</li>
            <li id="one2" onclick="setTab('one',2)" class="">资深中教</li>
        </ul>
    </div>

    <div class="listmenu" style="margin-top: 30px;">
    <!--    <ul><input class="easyui-textbox" id="query_name" data-options="" style="width:900px"></ul>-->
        <ul> <input id="search_type" class="easyui-searchbox" data-options="value:'',prompt:'请输入要找的咨询师',menu:'#type',searcher:doSearch" style="width:300px"></input>
        <div id="type">
            <div data-options="name:''">全部</div>
            <?php foreach ($type as $t) {?>
            <div data-options="name:'<?=$t['id']?>'<?php if($t['id'] == $selected_type) echo ',selected:true';?>"><?=$t['title']?></div>
            <?php } ?>
        </div>
        </ul>
    </div>

    <div class="listbox">
        <!--外教-->
        <div id="con_one_1" style="display: block;">
            <!--Monica-->

            <?php foreach ($list as $val){?>
                <div class="masterlist fix">
                    <p class="listimg avatar">
                        <a href="#" >
                            <img src="<?=$val['avatar'] != '' ? $val['avatar']:'static/images/user.jpg'?>"></a>
                    </p>
                    <dl class="mastertxt">
                        <dt>
                            <h2><a href="#"><?=$val['name']?></a><span class="<?=$val['status_class']?>"><?=$val['status']?></span></h2>
                        </dt>
                        <dd>
                            <ul class="fix">
                                <li>性别：<?=$val['sex']?></li>
                                <li><span></span>出生年月：<?=$val['birth']?></li>
                                <li></span>擅长类型：<?=$val['good_at_type']?></li>
                                <li></span>预约人数：168</li>
                            </ul>
                        </dd>
                        <dd>
                            <a href="#">老师简介：
                                <?=$val['info']?> </a>
                        </dd>
                    </dl>
                </div>
                <div class="listbottom fix" style="margin-bottom: 50px;">
                    <div class="audio">
                    </div>
                    <a class="details" onclick="book(<?=$val['id']?>, '<?= $val['name']?>')" href="javascript:" >预约</a>
                </div>

            <?php } ?>
            <a class="more" href="javascript:" target="_blank">更多老师</a>
        </div>
        <!--中教-->
    </div>
</div>
<script>
    
    $(function () {
        $("#query_name").textbox({
            icons: [{
                iconCls:'icon-search',
                handler: function(e){
                    var currentTab = $('#wu-tabs').tabs('getSelected');
                    var name =  $(e.data.target).textbox('getValue');
                    if(name == "") return;

                    var url = 'index.php?d=admin&c=Book&m=online_book&name='+name;
                    $('#wu-tabs').tabs('update', {
                        tab: currentTab,
                        options: {
                            href: url
                        }
                    });
                    currentTab.panel('refresh');
                }
            }]

        })

      //  $('#search_type').searchbox('selectName', '5');


    })

    function book(id, name) {
        if(id)
        {
            $('#com_edit').dialog({
                title: '预约咨询师——' + name,
                width: 900,
                height: 450,
                closed: false,
                cache: false,
                href: 'index.php?d=admin&c=Book&m=change_book&id='+id,
                modal: true,
                buttons: [
                    {
                        text:'取消',
                        handler:function(){
                            $('#com_edit').dialog("close");
                        }}
                ]
            });
        }

    }

    function doSearch(value,name){
        var currentTab = $('#wu-tabs').tabs('getSelected');
       // var name =  $(e.data.target).textbox('getValue');
        if(name == "全部") name = "";
        if(name == "" && value == "") return;

        var url = 'index.php?d=admin&c=Book&m=online_book&name='+value + '&good_at_type='+name;
        $('#wu-tabs').tabs('update', {
            tab: currentTab,
            options: {
                href: url
            }
        });
        currentTab.panel('refresh');

    }
    
</script>