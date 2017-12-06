<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>
<link rel="stylesheet" type="text/css" href="static/css/jscrollpane1.css?1" />
<script src="static/js/chat/jquery-1.4.2.min.js" type="text/javascript"></script>
<!--引用jquery-1.4.2.min.js会影响添加表情，不引用jquery-1.4.2.min.js就不支持IE、火狐浏览器的鼠标滚轮插件-->
<!-- the mousewheel plugin -->
<script type="text/javascript" src="static/js/chat/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="static/js/chat/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="static/js/chat/scroll-startstop.events.jquery.js"></script>
<!--讨论区滚动条end-->
<body>


<div class="talk">
    <div class="talk_title"><span>心理咨询</span></div>
    <div class="talk_record">
        <div id="jp-container" class="jp-container">

            <?php foreach ($list as $row){ ?>
                <?php if ($row['send_type'] == $send_type){ ?>
            <div class="talk_recordboxme">
                <div class="user"><img width="45" src="images/thumbs/<?=$me_avatar?>.jpg"/>我</div>
                <div class="talk_recordtextbg">&nbsp;</div>
                <div class="talk_recordtext">
                    <h3><?=$row['content']?></h3>
                    <span class="talk_time"><?=times($row['addtime'], 1)?></span>
                </div>
            </div>
                <?php } else{?>

                    <div class="talk_recordbox">
                        <div class="user"><img width="45" src="images/thumbs/<?=$fr_avatar?>.jpg"/><?=$from_name?></div>
                        <div class="talk_recordtextbg">&nbsp;</div>
                        <div class="talk_recordtext">
                            <h3><?=$row['content']?></h3>
                            <span class="talk_time"><?=times($row['addtime'], 1)?></span>
                        </div>
                    </div>

            <?php } } ?>


            <!--
                      <div class="talk_recordboxme">
                          <div class="user"><img src="images/thumbs/15.jpg"/>美美</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>我的问题是：1+1真的等于2吗？不会是等于3吧</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                   <div class="talk_recordbox">
                          <div class="user"><img src="images/thumbs/11.jpg"/>壮壮</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>对方的回答是：在错误的情况下是可以等于3的</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                      <div class="talk_recordboxme">
                          <div class="user"><img src="images/thumbs/15.jpg"/>美美</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>我的问题是1+1可以等于1吗？</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                      <div class="talk_recordbox">
                          <div class="user"><img src="images/thumbs/11.jpg"/>壮壮</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>对方的回答：理论上是不可以的，现在中是可以的。</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                      <div class="talk_recordboxme">
                          <div class="user"><img src="images/thumbs/15.jpg"/>美美</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>我的问题是：那这样说1+1到底等于几呢？</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                      <div class="talk_recordbox">
                          <div class="user"><img src="images/thumbs/11.jpg"/>壮壮</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>对方的回答是：可以等于任何数，你想等于几就等于几，你烦不烦啊！</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>

                      <div class="talk_recordboxme">
                          <div class="user"><img src="images/thumbs/15.jpg"/>美美</div>
                          <div class="talk_recordtextbg">&nbsp;</div>
                          <div class="talk_recordtext">
                              <h3>我在思考！</h3>
                              <span class="talk_time">2014-09-15 15:06</span>
                          </div>
                      </div>-->
        </div>

    </div>

    <div class="talk_word">
        <!--  &nbsp;
        <input class="add_face" id="facial" type="button" title="添加表情" value="" />
         <input class="messages emotion" autocomplete="off" value="在这里输入文字" onFocus="if(this.value=='在这里输入文字'){this.value='';}"  onblur="if(this.value==''){this.value='在这里输入文字';}"  />
         <input class="talk_send" type="button" title="发送" value="发送" />-->

        <textarea name="value[content]" id="content" style="width: 100%;height:80px;" class="messages emotion" ></textarea>

    </div>
</div>
<script type="text/javascript">
    $(function(){

        panel();

    });
    
    //添加对方发来的信息
    function add_from_chat(content) {
        var html  = '<div class="talk_recordbox"><div class="user"><img width="45" src="images/thumbs/<?=$fr_avatar?>.jpg"/><?=$from_name?></div>';
        html += '<div class="talk_recordtextbg">&nbsp;</div><div class="talk_recordtext">';
        html += '<h3>' + content +'</h3>';
        html += '<span class="talk_time">'+ getNowFormatDate() +'</span></div></div>';

        $(".jspPane").append(html);

        panel();
    }

    function say()
    {

       var content = $("#content").val();
       if(content != "")
       {
           var url = 'index.php?d=admin&c=Gateway_msg&m=save_chat&zxs_id=<?=$zxs_id?>&bind_uid=<?=$bind_uid?>';
           <?php if($to_uid != ''){ ?>
               url = 'index.php?d=admin&c=Gateway_msg&m=save_chat_to_police&therapist_id=<?=$therapist_id?>&to_uid=<?=$to_uid?>';
           <?php }?>
           $.ajax({
               url: url,
               type:"Post",
               dataType:"json",
               data:{
                   zxs_id : '<?=$zxs_id?>',
                   bind_uid : '<?=$bind_uid?>',
                   content : content
               },
               success: function (data) {
                   if(data.success){

                       var html  = '<div class="talk_recordboxme"><div class="user"><img  width="45" src="images/thumbs/<?=$me_avatar?>.jpg"/>我</div>';
                       html += '<div class="talk_recordtextbg">&nbsp;</div><div class="talk_recordtext">';
                       html += '<h3>' + content +'</h3>';
                       html += '<span class="talk_time">'+ getNowFormatDate() +'</span></div></div>';

                       $(".jspPane").append(html);

                       panel();
                       $("#content").val("");

                   }else{
                       $.messager.alert('操作提示',data.message,'error');
                   }
               }
           });




       }else{
            alert('信息不能为空')
       }
    }
    function getNowFormatDate() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
        return currentdate;
    }

    function panel() {
        // the element we want to apply the jScrollPane
        var $el= $('#jp-container').jScrollPane({
                verticalGutter 	: 4,
                stickToBottom:true,
                autoReinitialise:true
            }),

            // the extension functions and options
            extensionPlugin 	= {

                extPluginOpts	: {
                    // speed for the fadeOut animation
                    mouseLeaveFadeSpeed	: 500,
                    // scrollbar fades out after hovertimeout_t milliseconds
                    hovertimeout_t		: 1000,
                    // if set to false, the scrollbar will be shown on mouseenter and hidden on mouseleave
                    // if set to true, the same will happen, but the scrollbar will be also hidden on mouseenter after "hovertimeout_t" ms
                    // also, it will be shown when we start to scroll and hidden when stopping
                    useTimeout			: true,
                    // the extension only applies for devices with width > deviceWidth
                    deviceWidth			: 980
                },
                hovertimeout	: null, // timeout to hide the scrollbar
                isScrollbarHover: false,// true if the mouse is over the scrollbar
                elementtimeout	: null,	// avoids showing the scrollbar when moving from inside the element to outside, passing over the scrollbar
                isScrolling		: false,// true if scrolling
                addHoverFunc	: function() {

                    // run only if the window has a width bigger than deviceWidth
                    if( $(window).width() <= this.extPluginOpts.deviceWidth ) return false;

                    var instance		= this;

                    // functions to show / hide the scrollbar
                    $.fn.jspmouseenter 	= $.fn.show;
                    $.fn.jspmouseleave 	= $.fn.fadeOut;

                    // hide the jScrollPane vertical bar
                    var $vBar			= this.getContentPane().siblings('.jspVerticalBar').hide();

                    /*
                     * mouseenter / mouseleave events on the main element
                     * also scrollstart / scrollstop - @James Padolsey : http://james.padolsey.com/javascript/special-scroll-events-for-jquery/
                     */
                    $el.bind('mouseenter.jsp',function() {

                        // show the scrollbar
                        $vBar.stop( true, true ).jspmouseenter();

                        if( !instance.extPluginOpts.useTimeout ) return false;

                        // hide the scrollbar after hovertimeout_t ms
                        clearTimeout( instance.hovertimeout );
                        instance.hovertimeout 	= setTimeout(function() {
                            // if scrolling at the moment don't hide it
                            if( !instance.isScrolling )
                                $vBar.stop( true, true ).jspmouseleave( instance.extPluginOpts.mouseLeaveFadeSpeed || 0 );
                        }, instance.extPluginOpts.hovertimeout_t );


                    }).bind('mouseleave.jsp',function() {

                        // hide the scrollbar
                        if( !instance.extPluginOpts.useTimeout )
                            $vBar.stop( true, true ).jspmouseleave( instance.extPluginOpts.mouseLeaveFadeSpeed || 0 );
                        else {
                            clearTimeout( instance.elementtimeout );
                            if( !instance.isScrolling )
                                $vBar.stop( true, true ).jspmouseleave( instance.extPluginOpts.mouseLeaveFadeSpeed || 0 );
                        }

                    });

                    if( this.extPluginOpts.useTimeout ) {

                        $el.bind('scrollstart.jsp', function() {

                            // when scrolling show the scrollbar
                            clearTimeout( instance.hovertimeout );
                            instance.isScrolling	= true;
                            $vBar.stop( true, true ).jspmouseenter();

                        }).bind('scrollstop.jsp', function() {

                            // when stop scrolling hide the scrollbar (if not hovering it at the moment)
                            clearTimeout( instance.hovertimeout );
                            instance.isScrolling	= false;
                            instance.hovertimeout 	= setTimeout(function() {
                                if( !instance.isScrollbarHover )
                                    $vBar.stop( true, true ).jspmouseleave( instance.extPluginOpts.mouseLeaveFadeSpeed || 0 );
                            }, instance.extPluginOpts.hovertimeout_t );

                        });

                        // wrap the scrollbar
                        // we need this to be able to add the mouseenter / mouseleave events to the scrollbar
                        var $vBarWrapper	= $('<div/>').css({
                            position	: 'absolute',
                            left		: $vBar.css('left'),
                            top			: $vBar.css('top'),
                            right		: $vBar.css('right'),
                            bottom		: $vBar.css('bottom'),
                            width		: $vBar.width(),
                            height		: $vBar.height()
                        }).bind('mouseenter.jsp',function() {

                            clearTimeout( instance.hovertimeout );
                            clearTimeout( instance.elementtimeout );

                            instance.isScrollbarHover	= true;

                            // show the scrollbar after 100 ms.
                            // avoids showing the scrollbar when moving from inside the element to outside, passing over the scrollbar
                            instance.elementtimeout	= setTimeout(function() {
                                $vBar.stop( true, true ).jspmouseenter();
                            }, 100 );

                        }).bind('mouseleave.jsp',function() {

                            // hide the scrollbar after hovertimeout_t
                            clearTimeout( instance.hovertimeout );
                            instance.isScrollbarHover	= false;
                            instance.hovertimeout = setTimeout(function() {
                                // if scrolling at the moment don't hide it
                                if( !instance.isScrolling )
                                    $vBar.stop( true, true ).jspmouseleave( instance.extPluginOpts.mouseLeaveFadeSpeed || 0 );
                            }, instance.extPluginOpts.hovertimeout_t );

                        });

                        $vBar.wrap( $vBarWrapper );

                    }

                }

            },

            // the jScrollPane instance
            jspapi 			= $el.data('jsp');

        // extend the jScollPane by merging
        $.extend( true, jspapi, extensionPlugin );
        jspapi.addHoverFunc();

    }
</script>

</body>
</html>



