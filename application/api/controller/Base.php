<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Session;
use think\Request;
use \think\Db;

class Base extends Controller
{    

	public $USER_ID;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
	public function __construct() 
	{
        parent::__construct();
        if (Session::get('is_login')) 
        {
        	$this->USER_ID = Session::get('user_id');	
        }else{
        	return json(['msg' => 'fail','code' => 304, 'data' => []]);die();
        }
    }
}
