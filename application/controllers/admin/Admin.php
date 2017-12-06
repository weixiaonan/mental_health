<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/24
 * Time: 10:14
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Common.php';
class Admin extends Common
{

    private $admin_data;//后台用户登录信息



    /**
     * 后台首页
     */
    public function index()
    {
        $data['base_url'] = site_url();
        $this->load->view('admin/index', $data);
    }

    /**
     * 后台首页
     */
    public function main()
    {

        $this->load->view('admin/main');
    }

    function table_data()
    {
        $this->load->view('admin/table_data');
    }


    protected function menu_node_list($rid,$gid,$pid = 0,$level = 1){
        $tab_a = $this->config->item('role_tab')['tab_a'];
        $tab_g = $this->config->item('role_tab')['tab_g'];
        $tab_n = $this->config->item('role_tab')['tab_n'];
        $tab_r = $this->config->item('role_tab')['tab_r'];

        $userInfo = $this->session->userdata('user');
        if($userInfo['id'] == 1){
            $list = $this->loop_model->list_query("select n.id as nid,n.title,n.d,n.c,n.f from $tab_g g,$tab_n n where g.status=1 and g.id=n.gid and n.pid=$pid and n.gid=$gid and n.status=1 and n.level=$level order by n.sort asc ");
        }else{
            $r_str = " a.r_id={$rid} ";
            $list = $this->loop_model->list_query("select n.id as nid,n.title,n.d,n.c,n.f from $tab_a a,$tab_g g,$tab_n n,$tab_r r where {$r_str} and a.n_id=n.id and g.status=1 and g.id=n.gid and n.pid=$pid and n.gid=$gid and n.status=1 and n.level=$level and r.id=a.r_id and r.status=1 order by n.sort asc ");
        }
        if(empty($list)){
            return '';
        }else{
            return $list;
        }
    }
    public function menu(){
        $userInfo = $this->session->userdata('user');
        if(!empty($userInfo)){
            $act_type = $role_id = $userInfo['act_type'];
            if(empty($act_type)){
                echo $this->json_en('');exit;
            }else{
                $tab_a = $this->config->item('role_tab')['tab_a'];
                $tab_g = $this->config->item('role_tab')['tab_g'];
                $tab_n = $this->config->item('role_tab')['tab_n'];
                $tab_r = $this->config->item('role_tab')['tab_r'];
                if($userInfo['id'] == 1){
                    $role_nid_list = $this->loop_model->list_query("select n.id as n_id,n.gid from $tab_g g,$tab_n n where g.status=1 and g.id=n.gid and n.status=1 and n.level in(1,2) ");
                }else{
                    $r_str = " a.r_id={$role_id} ";
                    $role_nid_list = $this->loop_model->list_query("select a.n_id,n.gid from $tab_a a,$tab_g g,$tab_n n,$tab_r r where {$r_str} and a.n_id=n.id and g.status=1 and g.id=n.gid and n.status=1 and n.level in(1,2) and r.id=a.r_id and r.status=1 ");
                }
                //$role_sel_nid = array_column($role_nid_list,'n_id');
                $role_sel_gid = implode(',',array_unique(array_column($role_nid_list,'gid')));
                $list_g = $this->loop_model->list_query("select g.id as gid,g.title from $tab_g g where g.id in($role_sel_gid) and g.status=1 order by g.sort asc ");
                $menu_arr = $child_n1_arr = $child_n2_arr = array();
                if(!empty($list_g)){
                    foreach($list_g as $key_g => $val_g){
                        $menu_arr[$key_g]['id'] = $val_g['gid'];
                        $menu_arr[$key_g]['text'] = $val_g['title'];
                        $menu_arr[$key_g]['state'] = 'open';
                        $list_n1 = $this->menu_node_list($role_id,$val_g['gid'],0,1);
                        if(!empty($list_n1)){
                            foreach($list_n1 as $key_n1 => $val_n1){
                                $child_n1_arr[$key_n1]['id'] = $val_g['gid'].'_'.$val_n1['nid'];
                                $child_n1_arr[$key_n1]['text'] = $val_n1['title'];
                                $child_n1_arr[$key_n1]['state'] = 'open';
                                $list_n2 = $this->menu_node_list($role_id,$val_g['gid'],$val_n1['nid'],2);
                                if(!empty($list_n2)){
                                    foreach($list_n2 as $key_n2 => $val_n2){
                                        $child_n2_arr[$key_n2]['id'] = $val_g['gid'].'_'.$val_n1['nid'].'_'.$val_n2['nid'];
                                        $child_n2_arr[$key_n2]['text'] = $val_n2['title'];
                                        $child_n2_arr[$key_n2]['state'] = 'open';
                                        $child_n2_arr[$key_n2]['attributes'] = array(
                                            'url'=>'index.php?d='.$val_n2['d'].'&c='.$val_n2['c'].'&m='.$val_n2['f']
                                        );
                                    }
                                    $child_n1_arr[$key_n1]['children'] = $child_n2_arr;
                                    $child_n2_arr = array();
                                }else{
                                    $child_n1_arr[$key_n1]['attributes'] = array(
                                        'url'=>'index.php?d='.$val_n1['d'].'&c='.$val_n1['c'].'&m='.$val_n1['f']
                                    );
                                }
                            }
                            $menu_arr[$key_g]['children'] = $child_n1_arr;
                            $child_n1_arr = array();
                        }
                    }
                }
                echo json_encode($menu_arr, JSON_UNESCAPED_UNICODE);exit;
            }
        }else{
            echo json_encode('');exit;
        }
    }

    public function goEasy()
    {
        set_cache(time(), $_GET['type']);
    }


}