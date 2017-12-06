<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'Common.php';
class Book extends Common
{     
    public function index()
    {

        $this->load->view('admin/my_book');
    }

    public function my_book_data()
    {
        $page_num     = $this->input->get_post('page');
        $page_size    = $this->input->get_post('rows') < 1 ? $this->PAGE_SIZE : $this->input->get_post('rows');
        $start_num    = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;

        $user_id = $this->userInfo['id'];
        $my_sql = " SELECT *,unix_timestamp(book_date) as d,unix_timestamp(book_time) as t FROM user_book WHERE user_id={$user_id} ORDER BY d DESC,t DESC limit {$start_num},{$page_size}";
        $my_list = $this->loop_model->list_query($my_sql);

        $count_sql = "SELECT COUNT(*) as num FROM user_book WHERE user_id={$user_id}";
        $my_row = $this->loop_model->row_query($count_sql);


        foreach ($my_list as &$r)
        {
            $r['addtime'] = times($r['addtime'], 1);
            //查询咨询师
            $zxs = $this->loop_model->get_id('therapist', $r['therapist_id']);
            if($zxs){
                $r['therapist_name'] = $zxs['name'];
            }

            //判断是否过期
            $r['status'] = '正常';
            //是否可咨询
            $r['can_zx'] = 0;
            $book_time = explode('-', $r['book_time']);
            if(time() > strtotime( $r['book_date'] . ' ' .  $book_time[1]))
            {
                $r['status'] = '<b class="font-red">过期</b>';
            }

            if(time() > strtotime( $r['book_date'] . ' ' .  $book_time[0]) && time() < strtotime( $r['book_date'] . ' ' .  $book_time[1]))
            {
                $user = null;
                $r['is_online'] = 0;
                //如果有绑定就查询在线状态
                if($zxs['bind_uid'] > 0)
                {
                    $user = $this->loop_model->get_id('user', $zxs['bind_uid'], 'is_online');
                    $r['is_online'] = $user['is_online'];
                }


                $r['status'] = '<b class="font-green">在预约时间内</b>';
                $r['can_zx'] = 1;
            }


        }

        $data = array("total"=>$my_row['num'],"rows"=>$my_list);
        echo_en($data);
    }

    function online_book()
    {
        $name         = $this->input->get_post('name');
        $good_at_type = trim($this->input->get_post('good_at_type'), ',' );
        $page_num     = $this->input->get_post('page');
        $page_size    = $this->input->get_post('rows') < 1 ? $this->PAGE_SIZE : $this->input->get_post('rows');
        $start_num    = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;

        $where_str = '1=1 ';
        if($name != '')
        {
            $where_str .= " AND name like '%{$name}%' ";
        }
        if($good_at_type != '')
        {
            $good_at_type_arr   = explode(',', $good_at_type);
            $or_sql_arr = array();
            foreach ($good_at_type_arr as $val){
                $or_sql_arr[] = " FIND_IN_SET({$val},good_at_type_ids) ";
            }
            if(! empty($or_sql_arr)) {
                $or_sql_str = implode(' OR ', $or_sql_arr);
                $g_id_str   = " and ({$or_sql_str}) ";
            }
            $where_str .= $g_id_str;
        }

        $sql       = "SELECT * FROM therapist WHERE {$where_str} ORDER BY id DESC limit {$start_num},{$page_size}";
        $count_sql = "SELECT COUNT(*) as num FROM therapist WHERE {$where_str} ";

        $row_num  = $this->loop_model->row_query($count_sql);
        $list     = $this->loop_model->list_query($sql);

        foreach ($list as &$val)
        {
            $val['sex']          = $val['sex'] == 1 ? '男' : '女';
            $val['status_class'] = $val['status'] == 1 ? 'green' : 'reds';
            $val['status']       = $val['status'] == 1 ? '认证' : '未认证';
            if(! empty($val['good_at_type_ids']))
            {
                $row = $this->loop_model->row_query("SELECT GROUP_CONCAT(title) as titles FROM consultant_type WHERE id IN({$val['good_at_type_ids']})");
                $val['good_at_type'] = $row['titles'];
            }


        }
        $data['list'] = $list;
        $data['type'] = $this->loop_model->get_list('consultant_type');
        $data['selected_type'] = $good_at_type ? $good_at_type : '';
        $data['name'] = $name ? $name : '';
        $this->load->view('admin/online_book', $data);
    }

    /**
     * 开始预约
     */

    public function change_book()
    {
        $times = array('09:00-10:00', '10:00-11:00', '11:00-12:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00' );

        $dateArray = array();

        $id = $this->input->get('id');
        if(empty($id)) return;

        $i = 1;
        $days = 0;
        while ($days < 7){
            $day = date('Y-m-d', strtotime('+'.$i.' day'));
            $week_day = $this->get_week( $day ) ;
            if($week_day == '周六' || $week_day == '周日')
            {
                // $i--;
            }else{
                $dateArray[] = $day . '(' . $week_day . ')';
                $dateArrays[] = $day;
                $days++;
            }
            $i++;
        }

        //查询这个老师的预约
        $start_time = strtotime($dateArrays[0]);
        $end_time   = strtotime($dateArrays[6]);

        //当前教师的预约情况
        $sql = " SELECT CONCAT_WS('',book_date,book_time) AS book_date,user_id  FROM user_book WHERE therapist_id={$id} AND unix_timestamp(book_date) BETWEEN {$start_time} AND {$end_time} ";
        $list = $this->loop_model->list_query($sql);
        $row = array();
        $user_book = array();
        foreach ($list as $r)
        {
            $row[] = $r['book_date'];
            $user_book[$r['user_id']][] = $r['book_date'];
        }

        $user_id = $this->userInfo['id'];
        $my_sql = " SELECT CONCAT_WS('',book_date,book_time) AS book_date,user_id  FROM user_book WHERE user_id={$user_id} AND unix_timestamp(book_date) BETWEEN {$start_time} AND {$end_time} ";
        $my_list = $this->loop_model->list_query($my_sql);
        $my_book = array();
        foreach ($my_list as $r)
        {
            $my_book[$r['user_id']][] = $r['book_date'];
        }




        $table_html = '';

        $table_html .= '<tr><th width="80" align="center"></th>';
        foreach ($dateArray as $v)
        {
            $table_html .= '<th>' . $v . '</th>';
        }
        $table_html .= '</tr>';

        foreach ($times as $val)
        {
            $table_html .= '<tr><th>' . $val . '</th>';
            foreach ($dateArrays as $v)
            {
                $class = 'useable';
                $me    = '';
                if(in_array($v . $val, $row))
                {
                    $class = 'occupied';
                    if(in_array($v . $val, $user_book[$this->userInfo['id']]))
                    {
                        $me    = '我';
                    }
                }else{
                    if(in_array($v . $val, $my_book[$this->userInfo['id']]))
                    {
                        $class = 'outtime';
                    }
                }



                $table_html .= '<th data-time="' . $v . '_' . $val . '" class="'.$class.'"> '.$me.' </th>';
            }
            $table_html .= '</tr>';

        }

        $data['table_html'] = $table_html;

        $data['id'] = $id;
        $this->load->view('admin/change_book', $data);
    }

    public function book_save()
    {
        $id    = $this->input->post('id');
        $times = $this->input->post('time_str');
        $times = explode(',', $times);
        $time  = time();
        foreach ($times as $t)
        {
            $t_arr = explode('_', $t);
            $book_data = array();
            $book_data['user_id']   = $this->userInfo['id'];
            $book_data['book_date'] = $t_arr[0];
            $book_data['book_time'] = $t_arr[1];
            $book_data['therapist_id'] = $id;
            $book_data['addtime']   = $time;

            $query = $this->loop_model->insert('user_book', $book_data);
        }

        if($query > 0)
        {
            json_msg(true, '预约成功！');
        }else{
            json_msg(false, '预约失败！');
        }


    }

    /**
     * 民警向咨询师发起会话
     */

    public function my_chat()
    {
        $zxs_id = $this->input->get('zxs_id');
        $data['zxs_id'] = $zxs_id;

        $zxs = $this->loop_model->get_id('therapist', $zxs_id, 'bind_uid');


        $sql = "SELECT * FROM chat WHERE to_uid={$this->userInfo['id']}  and  therapist_id={$zxs_id} ";
        $data['list'] = $this->loop_model->list_query($sql);
        $data['me_avatar'] = rand(1,4);
        $data['fr_avatar'] = rand(1,4);
        $data['bind_uid']  = $zxs['bind_uid'];
        $data['send_type'] = 2;
        $data['from_name'] = '咨询师';
        $this->load->view('admin/chat_view', $data);
    }

    /**
     * 咨询师接受民警的会话
     *
     */

    function to_police_chat()
    {
        $from_uid = $this->input->get('from_id');

        $zxs = $this->loop_model->get_where('therapist', array('bind_uid'=>$this->userInfo['id']));
        if($zxs)
        {
            $sql = "SELECT * FROM chat WHERE to_uid={$from_uid}  and  therapist_id={$zxs['id']} ";
            $data['list'] = $this->loop_model->list_query($sql);
            $data['me_avatar'] = rand(1,4);
            $data['fr_avatar'] = rand(1,4);
            $data['therapist_id']  = $zxs['id']; //谁发送
            $data['to_uid']    = $from_uid; //向谁发送
            $data['send_type'] = 1;
            $data['from_name'] = '民警';
            $this->load->view('admin/chat_view', $data);
        }
    }

    function get_week($date){
        $date_str=date('Y-m-d',strtotime($date));
        $arr=explode("-", $date_str);
        $year=$arr[0];
        $month=sprintf('%02d',$arr[1]);
        $day=sprintf('%02d',$arr[2]);
        $hour = $minute = $second = 0;
        $strap = mktime($hour,$minute,$second,$month,$day,$year);
        $number_wk=date("w",$strap);
        $weekArr=array("周日","周一","周二","周三","周四","周五","周六");
        return $weekArr[$number_wk];

    }
}