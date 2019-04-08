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
      	$user_id 	= $this->USER_ID ?? 0;

        $where  = 'user_id=? and game_key=?';
        $data   = Db::name('user_game_method')->where($where,[$user_id,$game_key])->select();
        $data   = ['data'  => $data];
        return json(['msg' => 'succeed','code' => 200, 'data' => $data]);
    }
}
