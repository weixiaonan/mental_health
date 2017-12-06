<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 15:49
 */

if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
// 登录控制器

class Login extends CI_Controller
{
    function __construct()
    {
        $this->name = '登录模块';
        parent::__construct();
    }
    public function index(){
        $this->load->view("admin/login");
    }
    public function login_check(){
        $username = addslashes(trim($this->input->post('unm',true)));
        $password = trim($this->input->post('pwd',true));
        if(empty($username)){
            $this->json_msg(false,'请输入用户名');
        }
        if(empty($password)){
            $this->json_msg(false,'请输入密码');
        }
        $user_data['username'] = $username;
        $user_data['password'] = get_password($password);

        $user = $this->loop_model->get_where('user', $user_data);


        if(!$user){
            json_msg(false,'用户名 或 密码错误');
        }else{
            if($user['status'] ==1){
                json_msg(false,'该账号已被禁止登陆！');
            }
            $update_date['ip']             = ip();
            $update_date['lastlogin_time'] = time();
            $update_date['login_num']      = $user['login_num'] + 1;

            $this->db->where('id', $user['id']);
            $this->db->update('user', $update_date);

            $this->session->set_userdata('user', $user);
            json_msg(true,'登录成功','index.php?d=admin&c=Admin&m=index');
        }
    }
    public function login_jump(){
        $token_str = trim($this->input->get('token',true));
        $token_arr = explode(',',$token_str);
        $username = addslashes(urldecode($token_arr[0]));
        $token = addslashes($token_arr[1]);
        $this->session->set_userdata('token',$token);
        redirect('d=admin&c=Index&m=index&token='.urlencode($username).','.$token);
    }
    public function login_out(){
        $this->session->sess_destroy();
        redirect('d=admin&c=Login&m=index');
    }
}