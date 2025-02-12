<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Db;

class Information extends Base
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
     * 资讯中心列表
     */
    public function list()
    {
      	$game_key  	= $this->request->param('game_key','');
        $user_id    = $this->request->param('me',$this->USER_ID);

        //$where  = 'user_id=? and game_key=?';
        $where[] = ['user_id','=',$user_id];
        $where[] = ['game_key','=',$game_key];

        if ($game_key == 'ssc') 
            $where[] = ['methods','in',['龙虎','组选三','组选六']];


        $data   = Db::name('user_game_method')->where($where,[$user_id,$game_key])->select();
        $data   = ['data'  => $data];
        return json(['msg' => 'succeed','code' => 200, 'data' => $data]);
    }
}
