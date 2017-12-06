<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class Therapist extends Common
{     
    public function index()
    {
          $this->load->view('admin/therapist_list');
    }

    public function list_data()
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
            $val['sex']    = $val['sex'] == 1 ? '男' : '女';
            $val['status'] = $val['status'] == 1 ? '是' : '否';
            if(! empty($val['good_at_type_ids']))
            {
                $row = $this->loop_model->row_query("SELECT GROUP_CONCAT(title) as titles FROM consultant_type WHERE id IN({$val['good_at_type_ids']})");
                $val['good_at_type'] = $row['titles'];
            }

        }
        $data = array("total"=>$row_num['num'],"rows"=>$list);
        echo_en($data);
    }

    public function therapist_edit()
    {
        $data = array();
        $id = $this->input->get('id');
        if($id)
        {
            $data['value'] = $this->loop_model->get_id('therapist', $id);
        }else{

        }

        $data['form_id'] = 'therapist_edit_tj';
        $this->load->view('admin/therapist_edit', $data);
    }


    public function therapist_save()
    {
        $value = $this->input->post('value');
        $id    = $this->input->post('id');
        $good_at_type_ids = $this->input->post('good_at_type_ids');

        if($_FILES ['avatar_upfile'] ['name'])
        {
            $allowed_types = 'jpg|gif|bmp|jpeg|png';//
            $this->load->model('file_model');
            $datasss = $this->file_model->file_upload('avatar/', 'avatar_upfile', $allowed_types);
            if (!empty($datasss['error'])) {
                $this->json_msg(false, strip_tags($datasss['error']));
            } else if (!empty($datasss['upload_data'])) {
                $value['avatar'] = $datasss['upload_path'] . $datasss['upload_data']['file_name'];
            }
        }

        $value['good_at_type_ids'] = implode(',', $good_at_type_ids);
        if($id)
        {
            $query = $this->loop_model->update_id('therapist', $value, $id);
        }else{
            $query = $this->loop_model->insert('therapist', $value);
        }
        if($query > 0)
        {
            json_msg(true, '操作成功！');
        }else{
            json_msg(false, '操作失败或没做任何修改！');
        }
    }

    public function therapist_del()
    {
        $ids = trim($_REQUEST['id_str']);

       // $zs = $this->loop_model->row_query("select count(id) as zs from user where act_type in ({$ids})")['zs'];
      //  if($zs > 0){
      //      json_msg(false,'删除失败！原因：此角色下还有 '.$zs.' 个账号');
      //  }

        if($ids)
        {
            $ids = explode(',', $ids);
            $query = $this->loop_model->delete_id('therapist', $ids);
            if($query > 0)
            {
                json_msg(true, '操作成功！');
            }else{
                json_msg(false, '操作失败！');
            }
        }
    }

    /**
     * 获取咨询类型
     */

    public function get_consult_type()
    {
        $list = $this->loop_model->get_list('consultant_type');
        echo_en($list);
    }

    /**
     * 绑定与解绑
     */
    public function bind_therapist()
    {
        $this->table = 'therapist';
        $tid = $this->input->post('tid');
        $uid = $this->input->post('uid');
        //绑定
        if($uid == 0)
        {
            //判断是否绑定过
            $row = $this->loop_model->get_where($this->table, array('bind_uid'=>$this->userInfo['id']));
            if($row)
            {
                json_msg(false, '当前登录账户已经绑定过！');
            }
            $query = $this->loop_model->update_id($this->table, array('bind_uid'=>$this->userInfo['id']), $tid);
            if($query > 0)
            {
                json_msg(true, '绑定成功！');
            }else{
                json_msg(false, '绑定失败！');
            }
        }else
        {
            //判断是否解绑的是当前用户
            $row = $this->loop_model->get_where($this->table, array('bind_uid'=>$this->userInfo['id'], 'id'=>$tid));
            if($row)
            {
                $query = $this->loop_model->update_id($this->table, array('bind_uid'=>0), $tid);
                if($query > 0)
                {
                    json_msg(true, '解绑成功！');
                }else{
                    json_msg(false, '解绑失败！');
                }
            }else{
                json_msg(false, '解绑用户不对！');
            }
        }
    }




}