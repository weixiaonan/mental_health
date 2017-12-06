<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 模型
include_once 'common_model.php';
class file_model extends CI_Model
{
	function __construct ()
    {
        parent::__construct();
		$this->table = 'attachment';
    }

	//上传处理
	function file_upload($save_path,$userfile,$allowed_types = '')
	{
		set_time_limit(300);
		$path = './uploads/'.trim($save_path).date('Ymd',time());
		$config['max_size'] = 1024; // 20M = 20480

		$config['upload_path'] = $path."/";
		//echo $config['upload_path'];
		if(!empty($allowed_types)){
			$config['allowed_types'] = $allowed_types;
		}
		$config['encrypt_name'] = true;

		if (! file_exists ( $path )){
			mkdir ( $path, 0666, true );
		}

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($userfile))
		{
			$error = array('error' => $this->upload->display_errors());
			return $error;
		}else {
			$data = array('upload_data' => $this->upload->data() , 'upload_path' => $config['upload_path']);
			return $data;
		}

	}

}
