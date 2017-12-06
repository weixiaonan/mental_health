<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class Consultant_type extends Common
{     
    public function index()
    {
      $this->load->view('admin/consultant_type_list');
    }

    public function list_data()
    {
        $page_num     = $this->input->get_post('page');
        $page_size    = $this->input->get_post('rows') < 1 ? $this->PAGE_SIZE : $this->input->get_post('rows');
        $start_num    = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;

        $where_str = '1=1 ';
        $sql       = "SELECT * FROM consultant_type WHERE {$where_str} ORDER BY id DESC limit {$start_num},{$page_size}";
        $count_sql = "SELECT COUNT(*) as num FROM consultant_type WHERE {$where_str} ";

        $row_num   = $this->loop_model->row_query($count_sql);
        $list      = $this->loop_model->list_query($sql);

        foreach ($list as &$val)
        {
            $val['addtime']    = times($val['addtime']);
        }
        $data = array("total"=>$row_num['num'],"rows"=>$list);
        echo_en($data);
    }

    public function add(){
        $data['form_id'] = 'consultant_edit_tj';
        $this->load->view('admin/consultant_type_edit', $data);
    }

    public function edit()
    {
        $id    = $this->input->get('id');
        if($id){
            $data['value'] = $this->loop_model->get_id('consultant_type', $id);
        }
        $data['form_id'] = 'consultant_edit_tj';
        $this->load->view('admin/consultant_type_edit', $data);
    }

    public function save()
    {
        $value = $this->input->post('value');
        $id    = $this->input->post('id');

        if($id)
        {
            $query = $this->loop_model->update_id('consultant_type', $value, $id);
        }else{
            $value['addtime'] = time();
            $query = $this->loop_model->insert('consultant_type', $value);
        }
        if($query > 0)
        {
            json_msg(true, '操作成功！');
        }else{
            json_msg(false, '操作失败！');
        }
    }

    public function del()
    {
        $ids = trim($_REQUEST['id_str']);

        $zs = $this->loop_model->row_query("select count(id) as zs from therapist where  FIND_IN_SET ({$ids}, good_at_type_ids)")['zs'];
        if($zs > 0){
              json_msg(false,'删除失败！原因：此类型下还有 '.$zs.' 个咨询师');
        }

        if($ids)
        {
            $ids = explode(',', $ids);
            $query = $this->loop_model->delete_id('consultant_type', $ids);
            if($query > 0)
            {
                json_msg(true, '操作成功！');
            }else{
                json_msg(false, '操作失败！');
            }
        }
    }
}