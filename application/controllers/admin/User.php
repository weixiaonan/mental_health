<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class User extends Common
{

    public function __construct()
    {
        parent::__construct();

    }


    public function index()
    {
      $this->load->view('admin/user_list');
    }

    public function user_role_list(){

        $role_tab = $this->config->item('role_tab')['tab_r'];
        $list = $this->loop_model->list_query("select id as rid,name as rname from $role_tab order by id desc ");
        echo_en($list);

    }

    public function user_list_data(){
        $act_type       = addslashes(trim($this->input->post_get('user_act_type',true)));
        $user_nkname    = addslashes(trim($this->input->post_get('user_nkname',true)));
        $username       = addslashes(trim($this->input->post_get('user_uname',true)));
        $act_type_str   = '';
        $department_str = '';
        $username_str   = '';
        if(!empty($act_type)){
            $act_type_str = " and act_type = '{$act_type}' ";
        }

        if(!empty($user_nkname)){
            $department_str = " and (department like '%{$user_nkname}%' or nickname like '%{$user_nkname}%') ";
        }

        if(!empty($username)){
            $username_str = " and username = '{$username}' ";
        }

        $where_str = " and id != 1 ".$act_type_str.$department_str.$username_str;
        $page_num = $_REQUEST['page'];
        $page_size = $_REQUEST['rows'] < 1 ? $this->PAGE_SIZE : $_REQUEST['rows'];
        $start_num = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;
        $list = $this->loop_model->list_query("select id,id as pk_id,act_type,department,username,nickname,ip,lastlogin_time,status,beizhu from user where 1=1 $where_str order by id desc limit {$start_num},{$page_size}");
        $role_tab = $this->config->item('role_tab')['tab_r'];
        foreach($list as &$val){
            switch($val['act_type']){
                case(is_numeric($val['act_type'])):
                    $row = $this->loop_model->row_query("select name from $role_tab where id='{$val['act_type']}' ");
                    $val['act_type'] = $row['name'];
                    break;
                default:
                    $val['act_type'] = '未设置';
                    break;
            }
            $val['lastlogin_time'] = $val['lastlogin_time'] > 0 ? timeFromNow($val['lastlogin_time']) : '';
        }
        $total = $this->loop_model->row_query("select count(*) as zs from user where 1=1 $where_str ")['zs'];
        $data = array("total"=>$total,"rows"=>$list);
        echo_en($data);
    }

    public function user_edit()
    {
        $id   = $this->input->get('id');
        $type = $this->input->get('type');

        if( $type != '' )
        {
            $id = $this->userInfo['id'];
            $data['type'] = $type;
        }

        $data['user'] = array();
        if($id){
            $data['user'] = $this->loop_model->get_id('user', $id);
        }

        $role_tab = $this->config->item('role_tab')['tab_r'];
        $list = $this->loop_model->list_query("select id as rid,name as rname from $role_tab order by id desc ");
        $role_list = array();
        if(!empty($list)){
            foreach($list as $val){
                $role_list[$val['rid']] = $val['rname'];
            }
        }
        $data['role_list'] = $role_list;
        $data['form_id'] = 'user_edit_tj';
        $this->load->view('admin/user_edit', $data);
    }

    function user_save()
    {
        $id        = $this->input->post('user_id');
        $user_data = $this->input->post('user');
        if($user_data['password'] == ''){
            if($id){
                unset($user_data['password']);
            }else{
                $user_data['password'] = get_password('123456');
            }

        } else{
            $user_data['password'] = get_password($user_data['password']);
        }

        $query = 0;
        if($id){
            $query = $this->loop_model->update_id('user', $user_data, $id);
        }else{
            $query = $this->loop_model->insert('user', $user_data);
        }
        if($query > 0){
            json_msg(true, '保存成功！');
        }else{
            json_msg(false, '保存失败！');
        }

    }

    public function user_del(){
        $ids = $this->input->post('id_str');
        if($ids){
            $ids = explode(',', $ids);
            $query = $this->loop_model->delete_id('user', $ids);
            if($query > 0){
                json_msg(true, '操作成功！');
            }else{
                json_msg(false, '操作失败！');
            }
        }
    }

    /**
     *
     */
    public function change_status()
    {
        $id     = $this->input->post('id');
        $status = $this->input->post('status');
        $query = $this->loop_model->update_id('user', array('status'=>$status), $id);
        if($query > 0){
            json_msg(true, '操作成功！');
        }else{
            json_msg(false, '操作失败！');
        }

    }

}