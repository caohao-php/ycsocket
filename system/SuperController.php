<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 控制器基类
 * SuperController Class
 *
 * @package       SuperCI
 * @subpackage    Controller
 * @category      Controller Base
 * @author        caohao
 */

class SuperController {
	protected $loader;
    protected $params;
    protected $ip;
    protected $input_fd;
    
    public function __construct($fd, & $params, $clientInfo) {
    	$this->params = & $params;
    	$this->ip = $clientInfo['remote_ip'];
    	$this->input_fd = $fd;
    	
    	$this->loader = new Loader($this);
    	
    	$this->init();
    }
    
    protected function init() {
	}
    
    public function & get_ip() {
    	return $this->ip;
    }
    
    public function & get_params() {
    	return $this->params;
    }
    
    private function get_result_success($message) {
		if(empty($message['tagcode'])) {
            if(empty($message)) {
                $message = array('c' => $this->params['c'], 'm' => $this->params['m'], 'tagcode' => '00000000');
            } else {
                $code = array('c' => $this->params['c'], 'm' => $this->params['m'], 'tagcode' => '00000000');
                $message = array_merge($code, $message);
            }
        }
        
        return json_encode($message);
	}
	
	private function get_result_error($code, $message) {
		$data = array('c' => $this->params['c'], 'm' => $this->params['m'], "tagcode" => "" . $code, "description" => $message);
        return json_encode($data);
	}
	
    /**
    * json输出
    * @param array $data
    */
    protected function response_success_to_all($message)
    {
        $data = array();
        $data["send_user"] = "all";
        $data["msg"] = $this->get_result_success($message);
        return $data;
    }
    
    /**
    * json输出
    * @param array $data
    */
    protected function response_success_to_me($message)
    {
        $data = array();
        $data["send_user"] = array($this->input_fd);
        $data["msg"] = $this->get_result_success($message);
        return $data;
    }
    
    /**
     * 返回错误code以及错误信息
     * @param sting $message   返回错误的提示信息
     * @param int $type 	返回的方式
     */
    protected function response_error($code, $message)
    {
        $data = array();
        $data["send_user"] = array($this->input_fd);
        $data["msg"] = $this->get_result_error($code, $message);
        return $data;
    }
}


