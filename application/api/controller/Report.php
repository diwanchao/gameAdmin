<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;
use \think\Db;

class Report extends Base
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
    public function list()
    {
    	$data = [
            'jlk3'=>['open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'all_agent_break'=>1,'agent_break'=>20,'rebate'=>'返利','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            'ssc'=>['open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'all_agent_break'=>1,'agent_break'=>20,'rebate'=>'返利','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            'down_total'=>['open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'all_agent_break'=>1,'agent_break'=>20,'rebate'=>'返利','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],

        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
