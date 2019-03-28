<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Session;
use think\Request;

class Base extends Controller
{    
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
	public function __construct() 
	{
        parent::__construct();

    }
}
