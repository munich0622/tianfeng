<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 * 用户任务模型
 *
 * @author 刘志超
 * @date 2015-04-21
 */
class User_model extends CI_Model{
    public $limit = 20;
	public function __construct(){
		parent::__construct();
		$this->load->database();
	
	}
	
	/**
	 * 获取用户信息
	 * $field string 字段名称
	 * $value string 字段对应的值
	 */
	public function get_user($field,$value){
	    if(!in_array($field,array('uid','phone','name')) || empty($value)){
	        return false;
	    }
	    $where = array(
	        $field => $value
	    );
	    return $this->db->get_where('ooo_user',$where)->row_array();
	}
	
	/**
	 * 获取用户推荐的人数
	 * @param $uid int 用户id
	 */
	public function invited_count($uid){
	    $uid = intval($uid);
	    
	    return $this->db->where('invited_uid',$uid)->from('ooo_invited')->count_all_results();
	}
	
	/**
	 * 获取直推列表
	 */
	public function tj_list($where,$page = 1){
	    $page   = (int)$page > 0 ? (int)$page : 1;
	    $offset = ($page - 1) * $this->limit;
	    
	    $sql = " SELECT count(1) AS count FROM tf_ooo_invited AS ti {$where}";
	    $num = $this->db->query($sql)->row_array();
	    
	    $sql = " SELECT tu.* FROM tf_ooo_invited AS ti INNER JOIN tf_ooo_user AS tu 
	             ON ti.be_invited_uid = tu.uid {$where} LIMIT {$offset}, {$this->limit}";
	    
	    $list = $this->db->query($sql)->result_array();
	    
	    $data['total'] = $num['count'];
	    $data['list']  = $list;
	    
	    return $data;
	}
}

?>
