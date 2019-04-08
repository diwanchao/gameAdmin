<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;
use \think\Db;

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
    	
        $first_notice   = Db::name('notice')->order('create_time desc')->value('content');
        return json(['msg' => 'succeed','code' => 200, 'data' =>['content'=>$first_notice]]);
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
        if ($game_key == 'ssc') 
        {
            $expect = get_ssc_number() ?? '';
        }


        return json(['msg' => 'succeed','code' => 200, 'data' =>['number'=>$expect]]);
    }


}
