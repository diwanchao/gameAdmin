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




    	$data = [
            'data'=>[
                ['user_name'=>'dwc','user_num'=>'123','user_id'=>1,'time'=>date('Y-m-d H:i:s',time()),'game_type'=>'吉林快3','number'=>'108132','part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'game_result'=>'123','money'=>12,'handsel'=>10,'break'=>1,'amount'=>1],
                ['user_name'=>'dwc','user_num'=>'123','user_id'=>1,'time'=>date('Y-m-d H:i:s',time()),'game_type'=>'吉林快3','number'=>'108132','part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'game_result'=>'123','money'=>12,'handsel'=>10,'break'=>1,'amount'=>1],
            ],
            'total'=>10,
            'money'=>10,
            'handsel'=>10,
            'break'=>10,
            'amount'=>2,
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
