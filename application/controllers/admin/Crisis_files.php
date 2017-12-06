<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class Crisis_files extends Common
{
    public function __construct()
    {
        parent::__construct();
        $this->table        = 'crisis_files';
        $this->base_url     = 'index.php?d=admin&c=Crisis_files';
        $this->list_view    = 'crisis_files_list';
        $this->edit_view    = 'crisis_files_edit';
        $this->datagrid     = 'crisis_files';

        //档案状态
        $this->status = array(
            1=>'危机信息上报',
            2=>'日常行为观察',
            3=>'危机处理',
            4=>'追踪访谈'
        );
    }
    public function index()
    {

        $this->load->view('admin/' . $this->list_view);
    }

    public function list_data()
    {
        $title        = $this->input->get_post('title');

        $page_num     = $this->input->get_post('page');
        $page_size    = $this->input->get_post('rows') < 1 ? $this->PAGE_SIZE : $this->input->get_post('rows');
        $start_num    = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;

        $where_str = '1=1 ';


        if($title != '')
        {
            $where_str .= "AND ( name like '%{$title}%'  OR pe like '%{$title}%' ) ";
        }

        $sql       = "SELECT * FROM {$this->table} WHERE {$where_str} ORDER BY id DESC limit {$start_num},{$page_size}";
        $count_sql = "SELECT COUNT(*) as num FROM {$this->table} WHERE {$where_str} ";

        $row_num   = $this->loop_model->row_query($count_sql);
        $list      = $this->loop_model->list_query($sql);

        foreach ($list as &$val)
        {
            $val['sex']     = $val['sex'] == 1 ? '男' : '女';
            if($val['status'] != ''){
                $status = '';
                $val['status'] = explode(',', $val['status']);
                foreach ($val['status'] as $k=>$v){
                    $status .= $k == 0 ? $this->status[$v] :  ',&nbsp;' . $this->status[$v] ;
                }
                $val['status'] = $status;
            }

            $val['addtime'] = times($val['addtime'], 1);
        }
        $data = array("total"=>$row_num['num'],"rows"=>$list);
        echo_en($data);
    }

    public function add(){
        $data['form_id'] = $this->datagrid . '_form';
        $this->load->view('admin/' . $this->edit_view, $data);
    }

    public function edit()
    {
        $id    = $this->input->get('id');
        if($id){
            $data['value']  = $this->loop_model->get_id($this->table, $id);
            $data['status'] = explode(',', $data['value']['status']);
        }

        $data['form_id'] = $this->datagrid . '_form';
        $this->load->view('admin/' . $this->edit_view, $data);
    }

    public function save()
    {
        $value = $this->input->post('value');
        $id    = $this->input->post('id');

        if( isset( $value['status'] ) && $value['status'] != '' ){
            $value['status'] = implode(',', $value['status']);
        }

        if($id)
        {
            $query = $this->loop_model->update_id($this->table, $value, $id);
        }else{
            $value['addtime'] = time();
            $query = $this->loop_model->insert($this->table, $value);
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

        if($ids)
        {
            $ids = explode(',', $ids);
            $query = $this->loop_model->delete_id($this->table, $ids);
            if($query > 0)
            {
                json_msg(true, '操作成功！');
            }else{
                json_msg(false, '操作失败！');
            }
        }
    }

    /**
     * @return CI_Image_lib
     */
    public function statistics()
    {

        $this->load->view('admin/crisis_files_statistics');
    }

    public function statistics_data()
    {
        $count_sql = "SELECT COUNT(case when sex = '1' then sex end  ) as maleCount, COUNT(case when sex = '0' then sex end ) as femaleCount FROM {$this->table}  ";
        $row_num   = $this->loop_model->row_query($count_sql);
        $nan_num = $row_num['maleCount'];
        $nv_num  = $row_num['femaleCount'];
        $data['sex_data'] = array('data_title' => array('男','女'),'data_dt' => array(array('name'=>'男','value'=>$nan_num),array('name'=>'女','value'=>$nv_num)));

        $score_title_arr = array('60分以下', '60~69分', '70~79分', '80~89分', '90~100分');
        $score_sql_arr   = array(
            ' score<60 ',
            ' score between 60 AND 69 ',
            ' score between 70 AND 79 ',
            ' score between 80 AND 89 ',
            ' score > 89 '
        );

        foreach ($score_sql_arr as $k=>$v)
        {
            $count_sql = "SELECT COUNT(id) as num FROM {$this->table}  WHERE {$score_sql_arr[$k]}";
            $row_num   = $this->loop_model->row_query($count_sql);
            $data['score_data']['data_title'][] = $score_title_arr[$k];
            $data['score_data']['data_dt'][]    = array('name'=>$score_title_arr[$k],'value'=>$row_num['num']);
        }


        for ($i=1; $i<5; $i++)
        {
            $count_sql = "SELECT COUNT(id) as num FROM {$this->table}  WHERE find_in_set({$i}, status)";
            $row_num   = $this->loop_model->row_query($count_sql);
            $data['status_data']['data_title'][] = $this->status[$i];
            $data['status_data']['data_dt'][]    = array('name'=>$this->status[$i],'value'=>$row_num['num']);
        }


      ///  $data['score_data'] = array('data_title' => array('男','女'),'data_dt' => array(array('name'=>'男','value'=>$nan_num),array('name'=>'女','value'=>$nv_num)));

        echo_en($data);
    }

}