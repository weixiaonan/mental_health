<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
define('BASEPATH',true);
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据 
		$return = json_encode(array('type'=> 'init', 'client_id' => $client_id));
        Gateway::sendToClient($client_id, $return);
		
        // 向所有人发送
       // Gateway::sendToAll("$client_id login\r\n");
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        // 向所有人发送 
        Gateway::sendToAll("$client_id said $message\r\n");
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {


       require __DIR__  . "/../../../application/config/database.php";

       $mysql_conf = array(
           'host'    => $db['default']['hostname'],
           'db'      => $db['default']['database'],
           'db_user' => $db['default']['username'],
           'db_pwd'  => $db['default']['password'],
       );

       $mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
       if ($mysqli->connect_errno) {
           die("could not connect to the database:\n" . $mysqli->connect_error);//诊断连接错误
       }
       $mysqli->query("set names 'utf8';");//编码转化
       $select_db = $mysqli->select_db($mysql_conf['db']);
       if (!$select_db) {
           die("could not connect to the db:\n" .  $mysqli->error);
       }
       $sql = "UPDATE user SET is_online=0 where client_id = '{$client_id}'";
       $res = $mysqli->query($sql);

       $return = json_encode(array('type'=> 'close', 'client_id' => $client_id, 'msg'=>$sql));
       // 向所有人发送 
       GateWay::sendToAll($return);
   }
}
