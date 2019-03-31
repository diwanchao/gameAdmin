<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\facade\Session;

class Member extends Base
{

	/**
	 * 会员管理列表
	 */
	public function userList()
	{
		$data =[
			'total'=>10,
			'data'=>[
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1],
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1],
			],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}	
	/**
	 * 代理管理列表
	 */
	public function agentList()
	{
		$data =[
			'total'=>10,
			'data'=>[
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1],
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1],
			],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}
}
