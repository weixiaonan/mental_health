<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(1);
class Common extends CI_Controller
{
    public $PAGE_SIZE = 20;
    public $userInfo  = null;
    public function __construct()
    {
        parent::__construct();
        $this->userInfo = $this->session->userdata('user');
        $this->insert_log();
        if(empty($this->userInfo)) {
            if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
                // ajax 请求的处理方式
                $this->login_error();
            }else{
                // 正常请求的处理方式
                redirect('d=admin&c=Login');
            };

        }
    }

    function insert_log()
    {

        $userInfo  =  $this->userInfo;

        $url = $_SERVER['REQUEST_URI'];
        $ip  = $_SERVER['REMOTE_ADDR'];
        $parameters = $_REQUEST;
        $addtime    = time();
        $data_log['url']        = $url ? $url : '';
        $data_log['ip']         = $ip ? $ip : '';
        $data_log['addtime']    = $addtime;
        $data_log['parameters'] = json_encode($parameters, JSON_UNESCAPED_UNICODE);
        $data_log['uid']        = $userInfo['id'] ? $userInfo['id'] : 0;
        $data_log['browser']    = my_get_browser();

        $this->db->insert('action_log', $data_log);

    }
    
    public function login_error()
    {

       echo '<script>$.messager.alert(\'操作提示\',"登录超时！",\'error\',function () {window.location.href="index.php?d=admin&c=Login"});</script>';
       exit;

    }
}