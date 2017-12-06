<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class Role extends Common
{
    function __construct()
    {
        $this->name = '角色管理模块';
        parent::__construct();
        $this->tab_a = $this->config->item('role_tab')['tab_a'];
        $this->tab_g = $this->config->item('role_tab')['tab_g'];
        $this->tab_n = $this->config->item('role_tab')['tab_n'];
        $this->tab_r = $this->config->item('role_tab')['tab_r'];
    }
    public function index(){
        $this->load->view("admin/role_list");
    }
    public function role_list_data(){
        $status = addslashes(trim($this->input->get_post('role_status',true)));
        if(!empty($status)){
            $status_str = " and status = '{$status}' ";
        }
        $name = addslashes(trim($this->input->get_post('role_name',true)));
        if(!empty($name)){
            $name_str = " and name like '%{$name}%' ";
        }

        $where_str = $status_str.$name_str;
        $page_num = $_REQUEST['page'];
        $page_size = $_REQUEST['rows'] < 1 ? $this->PAGE_SIZE : $_REQUEST['rows'];
        $start_num = (($page_num-1)*$page_size) < 0 ? 0 : ($page_num-1)*$page_size;
        $list = $this->loop_model->list_query("select id,id as pk_id,name,status,remark from $this->tab_r where 1=1 $where_str order by id desc limit {$start_num},{$page_size}");
        foreach($list as &$val){
            switch($val['status']){
                case('1'):
                    $val['status'] = '启用';
                    break;
                case('2'):
                    $val['status'] = '禁用';
                    break;
            }
        }
        $total = $this->loop_model->row_query("select count(*) as zs from $this->tab_r where 1=1 $where_str ")['zs'];
        $data = array("total"=>$total,"rows"=>$list);
        echo_en($data);
    }

    public function role_add(){
        $data['dt'] = array();
        $data['show_status'] = '_edit';
        $this->load->view("admin/role_add_show",$data);
    }
    public function role_edit(){
        $id = intval($_REQUEST['id']);
        if($id >0){
            $data['dt'] = $this->loop_model->get_id($this->tab_r, $id);
        }else{
            $data['dt'] = array();
        }
        $data['show_status'] = '_edit';
        $this->load->view("admin/role_edit",$data);
    }
    public function role_save()
    {

        $dt_role = $_REQUEST['role'];
        if($dt_role['status'] == ""){
            json_msg(false, '请选择状态');
        }
        if($dt_role['name'] == ""){
            json_msg(false, '请输入角色名称');
        }

        $id = intval($_REQUEST['role_id']);
        if ($id > 0) {
            $rs = $this->loop_model->update_id($this->tab_r, $dt_role, $id);
        } else {
            $rs = $this->loop_model->insert($this->tab_r, $dt_role);
        }

        if($rs > 0){
            json_msg(true,'保存成功');
        }else{
            json_msg(false,'保存失败');
        }
    }
    public function role_del(){

        $ids = trim($_REQUEST['id_str']);

        $zs = $this->loop_model->row_query("select count(id) as zs from user where act_type in ({$ids})")['zs'];
        if($zs > 0){
            json_msg(false,'删除失败！原因：此角色下还有 '.$zs.' 个账号');
        }

        if($ids){
            $ids = explode(',', $ids);
            $query = $this->loop_model->delete_id('ci_role', $ids);
            if($query > 0){
                json_msg(true, '操作成功！');
            }else{
                json_msg(false, '操作失败！');
            }
        }
    }

    protected function node_list($gid,$pid = 0,$level = 1){
        $list = $this->loop_model->list_query("select id as nid,title from $this->tab_n where pid=$pid and gid=$gid and status=1 and level=$level order by sort asc ");
        if(empty($list)){
            return '';
        }else{
            return $list;
        }
    }
    public function role_node(){
        $role_id = intval($_REQUEST['role_id']);
        if($role_id > 0){
            $role_nid_list = $this->loop_model->list_query("select n_id from $this->tab_a where r_id='{$role_id}' ");
            $role_sel_nid = array_column($role_nid_list,'n_id');
        }
        $list_g = $this->loop_model->list_query("select id as gid,title from $this->tab_g where status=1 order by sort asc ");
        $node_arr = $child_n1_arr = $child_n2_arr = $child_n3_arr = array();
        foreach($list_g as $key_g => $val_g){
            $node_arr[$key_g]['text'] = $val_g['title'];
            $list_n1 = $this->node_list($val_g['gid'],0,1);
            if(!empty($list_n1)){
                foreach($list_n1 as $key_n1 => $val_n1){
                    $child_n1_arr[$key_n1]['text'] = $val_n1['title'];
                    $list_n2 = $this->node_list($val_g['gid'],$val_n1['nid'],2);
                    if(!empty($list_n2)){
                        foreach($list_n2 as $key_n2 => $val_n2){
                            $child_n2_arr[$key_n2]['text'] = $val_n2['title'];
                            $list_n3 = $this->node_list($val_g['gid'],$val_n2['nid'],3);
                            if(!empty($list_n3)){
                                foreach($list_n3 as $key_n3 => $val_n3){
                                    $child_n3_arr[$key_n3]['text'] = $val_n3['title'];
                                    $child_n3_arr[$key_n3]['checked'] = in_array($val_n3['nid'],$role_sel_nid) ? true : false;
                                    $child_n3_arr[$key_n3]['attributes']['id_str'] = $val_n1['nid'].'_'.$val_n2['nid'].'_'.$val_n3['nid'];
                                }
                                $child_n2_arr[$key_n2]['children'] = $child_n3_arr;
                                $child_n3_arr = array();
                            }
                        }
                    }

                    $list_n3 = $this->node_list($val_g['gid'],$val_n1['nid'],3);
                    if(!empty($list_n3)){
                        foreach($list_n3 as $key_n3 => $val_n3){
                            $child_n3_arr[$key_n3]['text'] = $val_n3['title'];
                            $child_n3_arr[$key_n3]['checked'] = in_array($val_n3['nid'],$role_sel_nid) ? true : false;
                            $child_n3_arr[$key_n3]['attributes']['id_str'] = $val_n1['nid'].'_0_'.$val_n3['nid'];
                        }
                    }

                    if(!empty($list_n2)){
                        $child_n1_arr[$key_n1]['children'] = array_merge($child_n2_arr,$child_n3_arr);
                    }else{
                        $child_n1_arr[$key_n1]['children'] = $child_n3_arr;
                    }
                    $child_n2_arr = $child_n3_arr = array();

                }
                $node_arr[$key_g]['children'] = $child_n1_arr;
            }
            $child_n1_arr = $child_n2_arr = $child_n3_arr = array();
        }
        echo_en($node_arr);
    }

    public function role_set(){
        $id = intval($_REQUEST['id']);
        $data['id'] = $id;
        if($id > 0){
            $data['show_status'] = '_set';
            $this->load->view("admin/role_set",$data);
        }
    }

    public function role_set_save(){

        $r_id = intval($_REQUEST['role_id']);
        if($r_id < 1){
            json_msg(false, '请选择要设置权限的角色');
        }
        $n_id_str = trim($_REQUEST['role_id_str']);
        $n_id_arr = explode(',',$n_id_str);
        $arr_n1_new = $arr_n2_new = $arr_n3_new = array();
        for($i=0;$i<count($n_id_arr);$i++){
            $n_arr = explode('_',$n_id_arr[$i]);
            $arr_n1 = array($n_arr[0]);
            $arr_n2 = array($n_arr[1]);
            $arr_n3 = array($n_arr[2]);

            $arr_n1_new = (count($arr_n1_new) > 0) ? array_merge($arr_n1_new, $arr_n1) : $arr_n1;
            $arr_n2_new = (count($arr_n2_new) > 0) ? array_merge($arr_n2_new, $arr_n2) : $arr_n2;
            $arr_n3_new = (count($arr_n3_new) > 0) ? array_merge($arr_n3_new, $arr_n3) : $arr_n3;
        }

        $dt_n1['r_id'] = $dt_n2['r_id'] = $dt_n3['r_id'] = $r_id;
        $this->db->query("delete from $this->tab_a where r_id='{$r_id}' ");

        if(count($arr_n1_new) > 0){
            $arr_n1_new = array_keys(array_flip($arr_n1_new));
            for($i=0;$i<count($arr_n1_new);$i++){
                if($arr_n1_new[$i] > 0){
                    $dt_n1['n_id'] = $arr_n1_new[$i];
                    $rs1 = $this->loop_model->insert($this->tab_a,$dt_n1);
                }
            }
        }
        if(count($arr_n2_new) > 0) {
            $arr_n2_new = array_keys(array_flip($arr_n2_new));
            for($i=0;$i<count($arr_n2_new);$i++){
                if($arr_n2_new[$i] > 0){
                    $dt_n2['n_id'] = $arr_n2_new[$i];
                    $rs2 = $this->loop_model->insert($this->tab_a,$dt_n2);
                }
            }
        }
        if(count($arr_n3_new) > 0) {
            $arr_n3_new = array_keys(array_flip($arr_n3_new));
            for($i=0;$i<count($arr_n3_new);$i++){
                if($arr_n3_new[$i] > 0){
                    $dt_n3['n_id'] = $arr_n3_new[$i];
                    $rs3 = $this->loop_model->insert($this->tab_a,$dt_n3);
                }
            }
        }

        json_msg(true,'保存成功');
    }

}