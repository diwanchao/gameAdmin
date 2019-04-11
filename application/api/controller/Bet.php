<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;
use \think\Db;

class Bet extends Base
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
     * 注单列表
     */
    public function list()
    {    
        $game_type =['jlk3'=>'吉林快三','ssc'=>'重庆时时彩'];

        $user_num   = $this->request->param('user_num',''); 
        $game_key   = $this->request->param('game_key',''); 
        $time_start = $this->request->param('time_start',date('Y-m-d',time())); 
        $time_end   = $this->request->param('time_end',date('Y-m-d',time())); 
        $status     = $this->request->param('status',''); 
        $page       = $this->request->param('index',1); 

        $where = [];
        if ($game_key) 
            $where[] = ['o.game_key','=',$game_key];
        if ($user_num) 
            $where[] = ['o.user_id','=',$user_num];
        if ($status) 
            $where[] = ['o.status','=',$status];

        $money=$handsel=$break=$amount=0;

        $ids = rtrim(get_sons($this->USER_ID),',');

        if($ids){
            $where[] = ['o.user_id','in',explode(',', $ids)];

            $total  = Db::name('order')
            ->alias('o')
            ->leftJoin('menber m','o.user_id = m.id')
            ->whereBetweenTime("DATE_FORMAT(o.time,'%Y-%m-%d')", $time_start, $time_end)
            ->where($where)
            ->value('count(1)');
            $res    = Db::name('order')
                ->alias('o')
                ->field('user_name,user_number as user_num,o.user_id,o.time,o.game_key,o. NO AS number,o.part,o.number AS game_num,play_name,content,odds,game_result,money,handsel,break')
                ->leftJoin('menber m','o.user_id = m.id')
                ->whereBetweenTime("DATE_FORMAT(o.time,'%Y-%m-%d')", $time_start, $time_end)
                ->where($where)
                ->page($page,10)
                ->select();
            if ($res) {
                foreach ($res as $key => $value) 
                {
                    $res[$key]['game_type'] = $game_type[$value['game_key']];
                    $res[$key]['amount']    = $value['money'] - $value['break'] - $value['handsel'];
                    $money      += $value['money'] ?? 0;
                    $handsel    += $value['handsel'] ?? 0;
                    $break      += $value['break'] ?? 0;
                    $amount     += $res[$key]['amount'] ?? 0;
                }
            }
        }

    	$data = [
            'data'      => $res ?? [],
            'total'     => $total ?? 0,
            'money'     => sprintf("%.2f",$money),
            'handsel'   => sprintf("%.2f",$handsel),
            'break'     => sprintf("%.2f",$break),
            'amount'    => sprintf("%.2f",$amount),
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}