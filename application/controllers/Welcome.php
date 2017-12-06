<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public $PAGE_SIZE = 20;
	public function index()
	{
	    redirect("?d=admin&c=Login");

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
		$this->load->view('therapist', $data);
	}

	function test()
    {
        echo 'test';
    }
    public function _404()
    {

        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            // ajax 请求的处理方式
            echo '<script>$.messager.alert(\'操作提示\',"页面不存在！",\'error\',function () {});</script>';
        }else{
            // 正常请求的处理方式
            show_error('对不起，您访问的页面不存在',404,'Sorry');
        };

    }

    function autoJump2Url($time=3)
    {
        $str = <<<EOF
                <script>
                var oDiv = document.createElement('p');
                oDiv.setAttribute('id','time');
                document.getElementById('container').appendChild(oDiv);//动态生成p标签，container是CI框架这个404模板中自带的
                 
                function vcodeTick(count) {
                    if (count < 0) {
                        return;
                    }
                    if(count == 0){
                        location.href=history.back(-1);
                    }
                    document.getElementById('time').innerHTML=count + ' 秒后跳转';
                     
                    count--;
                    setTimeout('vcodeTick(' + count + ')', 1000);
                }
                vcodeTick({$time});//执行方法
                </script>
EOF;
        return $str;
    }
}
