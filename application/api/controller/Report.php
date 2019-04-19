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
        $date           = [];
        $start_time     = $this->request->param('start_time',date('Y-m-d 00:00:00',time()));
        $end_time       = $this->request->param('end_time',date('Y-m-d 23:59:59',time()));
        $game_key       = $this->request->param('game_key','');
        $user_id        = $this->request->param('id',[]);
        $user_id        = $user_id ?: $this->USER_ID;
        $user_info      = Db::name('menber')->where('id','=',$user_id)->fetchSql(0)->find();
//        SELECT  FROM `order` LEFT JOIN menber ON  WHERE 4_id=15 GROUP BY ;
        $up         = $user_info['role_id']+1;
        $down       = $user_info['role_id']-1;
        $self       = $user_info['role_id'];

        if ($user_info['role_id'] == 4) 
        {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];
            $res = Db::name('order')
                ->field("user_name,{$down}_id as user_id,user_number,user_name AS down_name,game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM({$down}_earn) AS down_proportion,0 as up_proportion")
                ->leftJoin('menber',"`order`.`{$down}_id`=menber.id")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->group("{$down}_id,game_key")
                ->select();

            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                //$value['up_proportion']     = $value['up_proportion'] > 0 ? -1*$value['up_proportion'] : abs($value['up_proportion']);
                $value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['self_back']         = 0;
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['down_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['down_proportion'] - $value['up_proportion'];
                $data[$value['game_key']][] = $value;
            }
        }elseif ($user_info['role_id'] == 1) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];
            $res = Db::name('order')
                ->field("user_name,user_id,user_number,user_name AS down_name,game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM(get) AS down_proportion,SUM({$up}_earn) as up_proportion")
                ->leftJoin('menber',"`order`.`user_id`=menber.id")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->group("user_id,game_key")
                ->fetchSql(0)
                ->select();
            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                $value['up_proportion']     = $value['up_proportion'] > 0 ? -1*$value['up_proportion'] : abs($value['up_proportion']);
                //$value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['self_back']         = 0;
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['down_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['down_proportion'] - $value['up_proportion'];
                $data[$value['game_key']][] = $value;
            }
        }else{
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];
            $res = Db::name('order')
                ->field("user_name,{$down}_id as user_id,user_number,user_name AS down_name,game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM({$down}_earn) AS down_proportion,SUM({$up}_earn) as up_proportion")
                ->leftJoin('menber',"`order`.`{$down}_id`=menber.id")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->group("{$down}_id,game_key")
                ->select();

            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                $value['up_proportion']     = $value['up_proportion'] > 0 ? -1*$value['up_proportion'] : abs($value['up_proportion']);
                $value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['self_back']         = 0;
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['down_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['down_proportion'] - $value['up_proportion'];
                $data[$value['game_key']][] = $value;   
            }
        }

        //var_dump($res);die();

/*    	$data = [
            'jlk3'=>[
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            ],
            'ssc'=>[
                ['user_name'=>'dwc','user_number'=>'11','down_name'=>'dwc','open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
            ]
            //'down_total'=>['open_count'=>1,'not_open_count'=>1,'bet_amount'=>0,'sum_loss'=>1,'up_proportion'=>1,'self_proportion'=>0,'down_proportion'=>2,'self_back'=>1,'down_back'=>20,'rebate'=>'0','up_profit'=>100,'down_profit'=>1,'self_profit'=>9999],
        ];*/
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
        $user_id        = $this->request->param('id',[]);
        $user_id        = $user_id ?: $this->USER_ID;
        $user_info  = Db::name('menber')->where('id','=',$user_id)->fetchSql(0)->find();
        $up         = $user_info['role_id']+1;
        $down       = $user_info['role_id']-1;
        $self       = $user_info['role_id'];


        if ($user_info['role_id'] == 1) {
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

        }elseif($user_info['role_id'] == 4) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM({$self}_earn) AS self_num,SUM({$down}_earn) AS down_num,user_name AS down_name")
                ->leftJoin('menber'," `order`.`{$down}_id` = menber.id")
                ->group("{$down}_id")
                ->whereBetweenTime("time", $start_time, $end_time)
                ->where($where)
                ->fetchSql(0)
                ->select();
            foreach ($data as $key => $value) {
                $data[$key]['up_num']   = 0;
                $data[$key]['self_num'] = $value['self_num'] > 0  ? -1*$value['self_num'] : abs($value['self_num']);
                $data[$key]['down_num'] = $value['down_num'] > 0  ? -1*$value['down_num'] : abs($value['down_num']);
            }

        }else{
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

        }
        //var_dump($data);die();


        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
