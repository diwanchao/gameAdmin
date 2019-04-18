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
        $id             = $this->request->param('id',0);
    	$data = [
            'jlk3'=>[
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            ],
            'ssc'=>[
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
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
        $start_time     = $this->request->param('start_time',date('Y-m-d 00:00:00',time()));
        $end_time       = $this->request->param('end_time',date('Y-m-d 23:59:59',time()));
        $game_key       = $this->request->param('game_key','');
        $user_id        = $this->request->param('id','');
        $user_id        = $user_id ?: $this->USER_ID;
        $user_info  = Db::name('menber')->where('id','=',$user_id)->fetchSql(0)->find();
        $up         = $user_info['role_id']+1;
        $down       = $user_info['role_id']-1;
        $self       = $user_info['role_id'];


        if ($user_info['role_id'] != 1) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM({$up}_earn) as up_num,SUM({$self}_earn) AS self_num,SUM({$down}_earn) AS down_num,user_name AS down_name")
                ->leftJoin('menber'," `order`.`{$down}_id` = menber.id")
                ->group("{$down}_id")
                ->whereBetweenTime("time", $start_time, $end_time)
                ->where($where)
                ->fetchSql(0)
                ->select();
            foreach ($data as $key => $value) {
                $data[$key]['up_num'] = $value['up_num'] > 0  ? -1*$value['up_num'] : abs($value['up_num']);
                $data[$key]['self_num'] = $value['self_num'] > 0  ? -1*$value['self_num'] : abs($value['self_num']);
                $data[$key]['down_num'] = $value['down_num'] > 0  ? -1*$value['down_num'] : abs($value['down_num']);
            }
        }else{
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM({$up}_earn) as up_num,SUM({$self}_earn) AS self_num,SUM(get) AS down_num,user_name AS down_name")
                ->leftJoin('menber'," `order`.`user_id` = menber.id")
                ->group("user_id")
                ->whereBetweenTime("time", $start_time, $end_time)
                ->where($where)
                ->fetchSql(0)
                ->select();
            foreach ($data as $key => $value) {
                $data[$key]['up_num'] = $value['up_num'] > 0  ? -1*$value['up_num'] : abs($value['up_num']);
                $data[$key]['self_num'] = $value['self_num'] > 0  ? -1*$value['self_num'] : abs($value['self_num']);
            }
        }
        //var_dump($data);die();


        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
