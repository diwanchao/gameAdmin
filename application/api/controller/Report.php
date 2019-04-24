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
        $data           = [];
        $start_time     = $this->request->param('start_time',date('Y-m-d 00:00:00',time()));
        $end_time       = $this->request->param('end_time',date('Y-m-d 23:59:59',time()));
        $game_key       = $this->request->param('game_key','');
        $user_id        = $this->request->param('id',[]);
        $user_id        = $user_id ?: $this->USER_ID;
        $user_info      = Db::name('menber')->where('id','=',$user_id)->fetchSql(0)->find();
        $up         = $user_info['role_id']+1;
        $down       = $user_info['role_id']-1;
        $self       = $user_info['role_id'];

        if ($user_info['role_id'] == 4) 
        {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ["order.game_key",'in',$game_key];
            $res = Db::name('order')
                ->field("user_name,{$down}_id as user_id,SUM(break) AS break,user_number,user_name AS down_name,order.game_key,IFNULL(user_proportion,0) AS user_proportion,IFNULL(parent_proportion,100) AS parent_proportion,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM(1_earn+2_earn+3_earn) AS down_proportion,0 as up_proportion")
                ->leftJoin('menber',"`order`.`{$down}_id`=menber.id")
                ->leftJoin('break_log',"`order`.`3_id` = break_log.user_id AND `order`.game_key = break_log.game_key")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->group("{$down}_id,`order`.`game_key`")
                ->fetchSql(0)
                ->select();
            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                $value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['up_back']           = 0;
                $value['self_back']         = $value['break']*$value['parent_proportion']*0.01;
                $value['down_back']         = $value['break']*$value['user_proportion']*0.01;
                $value['rebate']            = 0;
                if ($value['sum_loss'] > 0) 
                {
                    $value['up_profit']         = 0;
                    $value['down_profit']       = $value['self_proportion'] - $value['self_back'];
                    $value['self_profit']       = $value['down_profit'];
                }else{
                    $value['up_profit']         = 0;
                    $value['down_profit']       = $value['self_proportion'] + $value['self_back'];
                    $value['self_profit']       = $value['down_profit'];
                }

                $data[$value['game_key']][] = $value;
            }
        }elseif ($user_info['role_id'] == 3) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ["order.game_key",'in',$game_key];
            $res = Db::name('order')
                ->field("SUM(break) AS break,IFNULL(user_proportion,0) AS user_proportion,IFNULL(parent_proportion,100) AS parent_proportion,user_name,{$down}_id as user_id,user_number,user_name AS down_name,order.game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM(2_earn+1_earn) AS down_proportion,SUM({$up}_earn) as up_proportion")
                ->leftJoin('menber',"`order`.`{$down}_id`=menber.id")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->leftJoin('break_log',"`order`.`3_id` = break_log.user_id AND `order`.game_key = break_log.game_key")
                ->group("{$down}_id,`order`.`game_key`")
                ->fetchSql(0)
                ->select();
            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                $value['up_proportion']     = $value['up_proportion'] > 0 ? -1*$value['up_proportion'] : abs($value['up_proportion']);
                $value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['self_back']         = $value['break']*$value['user_proportion']*0.01;
                $value['up_back']           = $value['break']*$value['parent_proportion']*0.01;
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['self_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['up_profit'] - $value['down_profit'];
                $data[$value['game_key']][] = $value;   
            }
        }elseif ($user_info['role_id'] == 2) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ["order.game_key",'in',$game_key];
            $res = Db::name('order')
                ->field("SUM(break) AS break,user_name,{$down}_id as user_id,user_number,user_name AS down_name,game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM(1_earn) AS down_proportion,SUM(3_earn+4_earn) as up_proportion")
                ->leftJoin('menber',"`order`.`{$down}_id`=menber.id")
                ->where($where)
                ->whereBetweenTime("time", $start_time, $end_time)
                ->group("{$down}_id,`order`.`game_key`")
                ->select();

            foreach ($res as $key => $value) {
                $value['sum_loss']          = $value['sum_loss'] > 0 ? -1*$value['sum_loss'] : abs($value['sum_loss']);
                $value['self_proportion']   = $value['self_proportion'] > 0 ? -1*$value['self_proportion'] : abs($value['self_proportion']);
                $value['up_proportion']     = $value['up_proportion'] > 0 ? -1*$value['up_proportion'] : abs($value['up_proportion']);
                $value['down_proportion']   = $value['down_proportion'] > 0 ? -1*$value['down_proportion'] : abs($value['down_proportion']);
                $value['self_back']         = 0;
                $value['up_back']           = $value['break'];
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['self_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['up_profit'] - $value['down_profit'];
                $data[$value['game_key']][] = $value;   
            }
        }elseif ($user_info['role_id'] == 1) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];
            $res = Db::name('order')
                ->field("SUM(break) AS break,user_name,user_id,user_number,user_name AS down_name,game_key,IF(`order`.`status`=1,COUNT(1),0) AS open_count,IF(`order`.`status`=0,COUNT(1),0) AS not_open_count,SUM(money) AS bet_amount,SUM(get) AS sum_loss,SUM({$self}_earn) AS self_proportion,SUM(get) AS down_proportion,SUM(2_earn+3_earn+4_earn) as up_proportion")
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
                $value['up_back']           = $value['break'];
                $value['self_back']         = 0;
                $value['down_back']         = 0;
                $value['rebate']            = 0;
                $value['up_profit']         = $value['up_proportion'] + $value['self_back'];
                $value['down_profit']       = $value['sum_loss'] + $value['down_back'];
                $value['self_profit']       = $value['up_profit'] - $value['down_profit'];
                $data[$value['game_key']][] = $value;
            }
        }/*else{
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
        }*/

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
        $data       = [];

        if ($user_info['role_id'] == 1) {
            $where[] = ['1_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM(2_earn+3_earn+4_earn) as up_num,SUM({$self}_earn) AS self_num,SUM(get) AS down_num,user_name AS down_name")
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

        }elseif ($user_info['role_id'] == 2) 
        {
            $where[] = ['2_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM(3_earn+4_earn) as up_num,SUM({$self}_earn) AS self_num,SUM(1_earn) AS down_num,user_name AS down_name")
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
        }elseif ($user_info['role_id'] == 3) 
        {
            $where[] = ['3_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM(4_earn) as up_num,SUM({$self}_earn) AS self_num,SUM(1_earn+2_earn) AS down_num,user_name AS down_name")
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
        }elseif($user_info['role_id'] == 4) {
            $where[] = [$self.'_id','=',$user_id];
            $where[] = ['game_key','in',$game_key];

            $data = Db::name('order')
                ->field("SUM({$self}_earn) AS self_num,SUM(1_earn+2_earn+3_earn) AS down_num,user_name AS down_name")
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

        }/*else{
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

        }*/
        //var_dump($data);die();


        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
