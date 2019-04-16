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
     * 获取列表
     */
    public function list()
    {
        $start_time     = $this->request->param('start_time',0);
        $end_time       = $this->request->param('end_time',0);
        $id  
    	$data = [
            'jlk3'=>[
                ['down_id'=>1,'down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
                ['down_id'=>1,'down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            ]
            'ssc'=>[
                ['down_id'=>1,'down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
                ['down_id'=>1,'down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            ]
            //'down_total'=>['open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
    /**
     * 头部统计
     */
    public function head()
    {
        $start_time     = $this->request->param('start_time',0);
        $end_time       = $this->request->param('end_time',0);
        $id             = $this->request->param('id',0);


        $data = [
            ['down_name'=>'dwc','up_num'=>1000,'self_num'=>1000,'down_num'=>1000],
            ['down_name'=>'hdj','up_num'=>1000,'self_num'=>1000,'down_num'=>1000],
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
