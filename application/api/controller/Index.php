<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;


class Index extends Base
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

    /**
     * 获取第一条公告
     */
    public function firstNotice()
    {
    	
        return json(['msg' => 'succeed','code' => 200, 'data' =>['content'=>'我是公告']]);
    }
    /**
     * 获取当前期号
     */
    public function getNowExpect()
    {
    	$expect 	= '';
        $game_key   = $this->request->param('game_key');
        if ($game_key == 'jlk3') 
        {
    		$expect = get_k3_number() ?? '';
        }
        return json(['msg' => 'succeed','code' => 200, 'data' =>['number'=>$expect]]);
    }


}
