Date.prototype.format_time = function (fmt) {
    var o = {
        "M+": this.getMonth()+1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}


$(function() {
	var today = new Date();
	$("input[name = btn-my]").click(function() {
		location.href = "myrecord.jsp";
	});
	$(".ckRadio2:first").addClass("btn-info");
	$(".ckRadio2").each(function(i) {
		var newdate = new Date();
		newdate.setDate(today.getDate() + i)
		var day = newdate.format_time("yyyy-MM-dd");
		$(this).attr("date",day);
		$(this).html(day);
	});
	$(".ckRadio2").click(function() {
		$(".ckRadio2").removeClass("btn-info");
		$(this).addClass("btn-info");
		getVenues();
	});
	
	$.post("getSportInfo.json", {}, function(data, status) {
		$("#sports ul").empty();
		$("#sports ul").append("<li><a sportId=0 class='ckRadio1'>不限</a></li>");
		var sportJson = data;
		$(sportJson).each(function(i) {
			$("#sports ul").append("<li><a sportId=" + sportJson[i].sportId + " class='ckRadio1'>" + sportJson[i].sportName + "</a></li>");
		});
		$(".ckRadio1:first").addClass("btn-info");
		$(".ckRadio1").click(function() {
			$(".ckRadio1").removeClass("btn-info");
			$(this).addClass("btn-info");
			var sportId = $(this).attr("sportId");
			$(".venuesItem").parent().hide().end()
				.removeClass("btn-info")
				.filter(sportId == 0? "[sportId!=0]": "[sportId=" + sportId + "]").parent().show().end()
				.eq(0).addClass("btn-info");
			getVenues();
		});
		getVenues();
	}, "json");
	
	$("[name='btn-reserve']").click(getReserve);
});	

function getReserve() {  
    $(".selected").each(function() {  
        /** 
        *   根据所在行，获得当前预约的时间段，和预约的日期，并进行相应的格式化，转化为预约开始时间和预约结束时间 
        *   根据所在的列数获得场地号 
        */  
        var time = $(this).parent().find("th").text();  
        var location = $(this).prevAll().length;  
        var hours = time.split("-")[0].split(":")[0];  
        var datetime = new Date($('.ckRadio2.btn-info').attr("date"));  
        datetime.setHours(parseInt(hours));  
        var startTime = datetime.format_time("yyyy-MM-dd hh:mm:ss");
        datetime.setHours(parseInt(hours) + 1);  
        var endTime = datetime.format_time("yyyy-MM-dd hh:mm:ss");
          
        //预约提交的参数  
        var params = {  
            "venuesId": $(".venuesItem.btn-info").attr("venuesId"),  
            "location": location,  
            "startTime": startTime,  
            "endTime": endTime  
        };  
          
        //为了使ajax回调函数中能访问到当前表格单元，将其存入变量$td中  
        var $td = $(this);  
          
        $.post("makeReserve.json", params, function(data, status) {  
            //如果预约成功，则将原来的“useable”变为“occupied”，并且解除点击事件，不可预约  
            if(data.message.result == "success") {  
                $td.removeClass().addClass("occupied").unbind("click");  
            }  
            var mesg = data.message.result == "success" ? "预约成功" : "预约失败";  
            //在表格下方展示此次预约的结果，包括详细信息  
            $(".reserveResult").append("<p>" + mesg + "：" + $(".venuesItem.btn-info").text() + "，场地"+ params.location   
                +"，时间：" + params.startTime +" 到 " + params.endTime + "</p>");  
        }, "json");  
    });  
} 

function submitQuery() {  
      
    /** 
    *   获取查询的日期和场馆id 
    */  
    var date = $(".ckRadio2").filter(".btn-info").attr("date");  
      
    var sportId = $(".ckRadio1.btn-info").attr("sportId");  
    var venuesId = $(".venuesItem.btn-info").attr("venuesId");  
      
    /** 
    *   ajax查询已经预约的列表 
    */  
    $.post("venues/getVenuesRecordList.action", {"venuesId": venuesId, "queryDay": date}, function(data, status) {  
          
        var venues = data.data.Venues;  
          
        /** 
        *   场馆的信息 
        *   @Param venuesNum 场地的总数 
        *   @Param venuesName 场馆的名称，表格中场地使用“场馆名” + “场地号”表示 
        *   @Param openTime 开放时间 
        *   @Param closeTime 结束时间 
        *   @Param record 指定日期，该场馆的预约列表 
        */  
        var venuesNum = parseInt(venues.venuesNum);  
        var venuesName = venues.venuesName;  
        var openTime = parseInt(venues.openTime.split(":")[0]);  
        var closeTime = parseInt(venues.closeTime.split(":")[0]);  
          
        var record = venues.Record;  
          
        //清空当前预约table  
        $("#resourceTable").empty();  
          
        //设置表头：场地  
        var tbRow = "<tr><th></th>";  
        for (var i = 1;i <= venuesNum; i ++)  
            tbRow += "<th>" + venuesName + i + "</th>";  
        tbRow += "</tr>";  
        $("#resourceTable").append(tbRow);  
          
        //从开放时间到关闭时间，每一小时为一个预约时间段  
        for (var time = openTime; time < closeTime; time ++) {  
            var show_time = time + ":00-" + (time + 1) + ":00";  
            tbRow = "<tr><th>" + show_time + "</th>";  
            for (var i = 1;i <= venuesNum;i ++)  
                tbRow += "<td></td>";  
            tbRow += "</tr>";  
            $("#resourceTable").append(tbRow);  
        }  
  
        var first_row = openTime;  
        var today = new Date();  
        //判断预约的时间是否为今天，如果是今天就将过期的时间设置为当前时间（小时）  
        var end_row = today.getDate() < (new Date(date)).getDate()? first_row - 1: parseInt((new Date()).getHours());  
        //对table的每一行，如果是在过期时间之前的时间段，就将这一行所有td的class设置为“outtime”，表示不可预约，否则class设置为“useable”表示可预约。  
        $("#resourceTable tr").each(function(i) {  
                $(this).find("td").addClass(i + first_row <= end_row? "outtime": "useable");  
        });  
          
        //开始遍历，根据每条预约记录的开始时间和场地，生成对应的行坐标和列坐标，找到表格中对应位置的td删除本身的class，增加“occupied”表示对应时段该场地已经被预约  
        $(record).each(function(i) {  
            var row = parseInt(record[i].startTime.split(":")[0]) - openTime + 1;  
            var col = parseInt(record[i].location) - 1;  
            console.log("row:"+row+",col:"+col);  
            $("#resourceTable tr").eq(row).find("td").eq(col).removeClass().addClass("occupied");  
        });  
          
        //为所有可以预约的td绑定一个click事件，即点击表示选中，再点一次恢复原来状态，直到点击预约按钮预约成功后，解除click事件，并由“useable”变为“occupied”  
        $("tr .useable").bind("click", function() {  
            $(this).toggleClass("selected");  
        });  
  
    }, "json");  
}  

function getVenues() {
	if ($(".search").find(".venuesItem")[0]) {
		submitQuery();
		return;
	}
	$.post("sport/getSportVenues.action", {"dataType":"json"} ,function(data, status) {
		var $ul = $("<ul></ul>");
		var sport = data.data.Sport;
		$(sport).each(function(i) {
			var sportId = sport[i].sportId;
			var venues = sport[i].Venues;
			$(venues).each(function(k) {
				var $li = $("<li><a>" + venues[k].venuesName + "</a></li>")
					.find("a").addClass("venuesItem").attr("sportId",sportId).attr("venuesId",venues[k].venuesId)
					.end();
				$ul.append($li);
			});
		});
		$(".search").addClass("choose").append($ul);
		
		// $(".venues_item:first").addClass("btn-info");
		var sportId = $(".ckRadio1:first").attr("sportId");
		$(".venuesItem").parent().hide().end()
			.removeClass("btn-info")
			.filter(sportId == 0? "[sportId!=0]": "[sportId=" + sportId + "]").parent().show().end()
			.eq(0).addClass("btn-info")
			.end().end()
			.click(function() {
				$(".venuesItem").removeClass("btn-info");
				$(this).addClass("btn-info");
				submitQuery();
			});
		submitQuery();
	});
}
