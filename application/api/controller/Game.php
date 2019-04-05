<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;
use \think\Db;

class Game extends Base
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
     * 游戏投注详情
     */
    public function detail()
    {
    	$data = [
            ['number'=>'108132','time'=>date('Y-m-d H:i:s',time()),'user_num'=>'123','user_name'=>'dwc','break'=>12,'part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'money'=>12,'result'=>10],
            ['number'=>'108132','time'=>date('Y-m-d H:i:s',time()),'user_num'=>'123','user_name'=>'dwc','break'=>12,'part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'money'=>12,'result'=>10],
            'money'=>10,
            'result'=>10,
            'break'=>10,
            'amount'=>2,
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
