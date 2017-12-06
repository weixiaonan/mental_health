<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 13:51
 */
header("Access-Control-Allow-Origin:*");
defined('BASEPATH') OR exit('No direct script access allowed');
//加载GatewayClient。关于GatewayClient参见本页面底部介绍
require_once './application/libraries/Gateway.php';
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '192.168.0.138:8001';
require_once 'Common.php';
class Gateway_msg extends Common
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loop_model');
    }

    public function index()
    {
        $id = rand(1,12);
        $rows = $this->loop_model->get_id('point', $id);
        // 向当前client_id发送数据
        $client_id = 1;
        $return = json_encode(
            array(
                'type'         => 'map',
                'msg'          => $client_id,
                'longitude'    => $rows['longitude'],
                'latitude'     => $rows['latitude'],
                'address_info' => $rows['address_info'])
        );

        Gateway::sendToUid($client_id, $return);

       // $this->load->view('bui_index');
    }

    public function bind()
    {
        $client_id = $this->input->post('client_id', true);
        // 假设用户已经登录，用户uid和群组id在session中
        $uid      = $this->userInfo['id'];
        $group_id = 1;//$_SESSION['group'];
        // client_id与uid绑定
        Gateway::bindUid($client_id, $uid);
        // 加入某个群组（可调用多次加入多个群组）
        //Gateway::joinGroup($client_id, $group_id);
        $this->loop_model->update_id('user', array('is_online'=>1, 'client_id'=>$client_id), $uid);
    }

    /**
     * 保存聊天信息,民警向咨询师发送
     *
     */

    public function save_chat()
    {
        $zxs_id = $this->input->post('zxs_id');
        $bind_uid = $this->input->post('bind_uid');//咨询师绑定的用户ID
        $content = $this->input->post('content');

        $data['therapist_id'] = $zxs_id;
        $data['to_uid']   = $this->userInfo['id'];
        $data['content']  = $content;
        $data['send_type']= 2;
        $data['addtime']  = time();
        $query = $this->loop_model->insert('chat', $data);

        if($query > 0)
        {
            // 如果不在线就先存起来
            if(Gateway::isUidOnline($bind_uid))
            {
                $say_data['content'] = $data['content'];
                $say_data['type'] = 'say_to_zxs';
                $say_data['to_uid'] = $data['to_uid'];

                // 在线就转发消息给对应的uid
                Gateway::sendToUid($bind_uid, json_encode($say_data));
            }

            json_msg(true, '成功！');
        }else{
            json_msg(false, '失败！');
        }

    }

    /**
     * 咨询师向民警发送
     */

    function save_chat_to_police()
    {
        $therapist_id = $this->input->get('therapist_id');
        $to_uid = $this->input->get('to_uid');//咨询师绑定的用户ID
        $content = $this->input->post('content');

        $data['therapist_id'] = $therapist_id;
        $data['to_uid']   = $to_uid;
        $data['content']  = $content;
        $data['send_type']= 1;
        $data['addtime']  = time();
        $query = $this->loop_model->insert('chat', $data);

        if($query > 0)
        {
            // 如果不在线就先存起来
            if(Gateway::isUidOnline($to_uid))
            {
                $say_data['content'] = $data['content'];
                $say_data['type'] = 'say_to_police';
                $say_data['zxs_id'] = $data['therapist_id'];

                // 在线就转发消息给对应的uid
                Gateway::sendToUid($to_uid, json_encode($say_data));
            }

            json_msg(true, '成功！');
        }else{
            json_msg(false, '失败！');
        }
    }
}