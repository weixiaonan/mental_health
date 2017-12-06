<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>咨询师列表</title>
    <link href="static/css/therapist.css" rel="stylesheet" type="text/css">
</head>


<body>

<div class="tabbox" id="tabbox">
    <div class="listmenu" style="display: none">
        <ul>
            <li id="one1" onclick="setTab('one',1)" class="off">英美外教</li>
            <li id="one2" onclick="setTab('one',2)" class="">资深中教</li>
        </ul>
    </div>
    <div class="listbox">
        <!--外教-->
        <div id="con_one_1" style="display: block;">
            <!--Monica-->

            <?php foreach ($list as $val){?>
            <div class="masterlist fix">
                <p class="listimg avatar">
                    <a href="http://www.hiknow.com/2014/teachers/monica.html" target="_blank">
                        <img src="<?=$val['avatar'] != '' ? $val['avatar']:'static/images/user.jpg'?>"></a>
                </p>
                <dl class="mastertxt">
                    <dt>
                        <h2><a href="#"><?=$val['name']?></a><span class="<?=$val['status_class']?>"><?=$val['status']?></span></h2>
                    </dt>
                    <dd>
                        <ul class="fix">
                            <li><span></span>性别：<?=$val['sex']?></li>
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
                <a class="details" href="http://www.hiknow.com/2014/teachers/monica.html" target="_blank">查看详情</a>
            </div>

            <?php } ?>












            <a class="more" href="http://www.hiknow.com/sessionhall.html" target="_blank">更多老师和课程</a>
        </div>
        <!--中教-->
    </div>
</div>

</body>
</html>


