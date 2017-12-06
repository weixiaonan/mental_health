<script type="application/javascript" src="static/js/echarts.js"></script>

<table class="show_data" id="pie_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
    <tr>
        <td>
            <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
            <div id="mom_tj_main1" style="width: 500px;height:400px;"></div>
        </td>
        <td>
            <div id="mom_tj_main2" style="width: 500px;height:400px;"></div>
            <script type="text/javascript">
                function mem_tongji(){


                    var myChart1 = echarts.init(document.getElementById('mom_tj_main1'));
                    var myChart2 = echarts.init(document.getElementById('mom_tj_main2'));
                    var myChart3 = echarts.init(document.getElementById('mom_tj_main3'));
              //     var myChart4 = echarts.init(document.getElementById('mom_tj_main4'));
                    myChart1.showLoading();
                    myChart2.showLoading();
                    myChart3.showLoading();
                  //  myChart4.showLoading();
                    var url = 'index.php?d=admin&c=Crisis_files&m=statistics_data';
                    $.get(url).done(function(data){
                        var data = eval('('+data+')');
                        myChart1.hideLoading();
                        myChart2.hideLoading();
                        myChart3.hideLoading();
                    //    myChart4.hideLoading();

                        option1 = {
                            title : {
                                text: '男女比例统计情况',
                                subtext: '人数(人)/比例(%)',
                                x:'center'
                            },
                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },
                            legend: {
                                orient: 'vertical',
                                left: 'left',
                                data: data.sex_data.data_title
                            },
                            series : [
                                {
                                    name: '男女比例统计情况:',
                                    type: 'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:data.sex_data.data_dt,
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }
                            ]
                        };

                        option2 = {
                            title : {
                                text: '心理分数段统计情况',
                                subtext: '数量(人)/比例(%)',
                                x:'center'
                            },
                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },
                            legend: {
                                orient: 'vertical',
                                left: 'left',
                                data: data.score_data.data_title
                            },
                            series : [
                                {
                                    name: '心理分数段统计：',
                                    type: 'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:data.score_data.data_dt,
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }
                            ]
                        };

                    option3 = {
                            title : {
                                text: '档案状态统计',
                                subtext: '数量(人)/比例(%)',
                                x:'center'
                            },
                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },
                            legend: {
                                orient: 'vertical',
                                left: 'left',
                                data: data.status_data.data_title
                            },
                            series : [
                                {
                                    name: '档案状态统计：',
                                    type: 'pie',
                                    radius : '55%',
                                    center: ['50%', '60%'],
                                    data:data.status_data.data_dt,
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }
                            ]
                        };

                        /*          option4 = {
                                  title : {
                                      text: '人员安置年龄统计',
                                      subtext: '数量(人)/比例(%)',
                                      x:'center'
                                  },
                                  tooltip : {
                                      trigger: 'item',
                                      formatter: "{a} <br/>{b} : {c} ({d}%)"
                                  },
                                  legend: {
                                      orient: 'vertical',
                                      left: 'left',
                                      data: data.nianling_data.data_title
                                  },
                                  series : [
                                      {
                                          name: '人员安置年龄：',
                                          type: 'pie',
                                          radius : '55%',
                                          center: ['50%', '60%'],
                                          data:data.nianling_data.data_dt,
                                          itemStyle: {
                                              emphasis: {
                                                  shadowBlur: 10,
                                                  shadowOffsetX: 0,
                                                  shadowColor: 'rgba(0, 0, 0, 0.5)'
                                              }
                                          }
                                      }
                                  ]
                              };*/

                        myChart1.setOption(option1);
                        myChart2.setOption(option2);
                        myChart3.setOption(option3);
                   //     myChart4.setOption(option4);



                      //  fill_table(data.gjfuli_data.data_dt, 'mom_tj_main5');
                      //  fill_table(data.azqktj_data.data_dt, 'mom_tj_main6');
                       // fill_table(data.sex_data.data_dt, 'mom_tj_main7');
                      //  fill_table(data.nianling_data.data_dt, 'mom_tj_main8');







                    });
                }
                //setTimeout(mem_tongji,1000);



                function export_tj_xls(type) {

                }



                //填充表格
                function fill_table(data, id) {

                    var table1_html = '';
                    var count_sum = 0;
                    var colspan_num = 1;

                    $('#'+id+'  tr:eq(1) td:gt(0)').remove();
                    $('#'+id+'  tr:eq(2) td:gt(0)').remove();
                    $('#'+id+'  tr:eq(3) td:gt(0)').remove();

                    $.each(data, function (i,v) {

                        $('#'+id+'  tr:eq(1)').append('<td>'+v.name+'</td>');
                        $('#'+id+'  tr:eq(2)').append('<td>'+v.value+'</td>');

                        count_sum += Number(v.value);
                        colspan_num++;

                    })

                    $.each(data, function (i,v) {
                        var num = GetPercent(v.value, count_sum);
                        $('#'+id+'  tr:eq(3)').append('<td>'+num+'</td>');

                    })

                    $('#'+id+'  tr:eq(0) td').attr('colspan', colspan_num);


                }


                ///计算两个整数的百分比值
                function GetPercent(num, total) {
                    num = parseFloat(num);
                    total = parseFloat(total);
                    if (isNaN(num) || isNaN(total)) {
                        return "-";
                    }
                    return total <= 0 ? "0%" : (Math.round(num / total * 10000) / 100.00 + "%");
                }

                $(function () {
                    mem_tongji();
                    $('#look_type').change(function(){
                        var curr_val = $(this).val();
                        $('.show_data').hide();
                        $('#'+curr_val).show();
                    })

                })
            </script>
        </td>
    </tr>
    <tr>
        <td><div id="mom_tj_main3" style="width: 500px;height:400px;"></div>
        </td>
        <td><div id="mom_tj_main4" style="width: 500px;height:400px;"></div></td>
    </tr>
</table>