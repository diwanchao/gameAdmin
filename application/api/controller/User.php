<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\facade\Session;

class User extends Base
{

	/**
	 * 登出
	 */
	public function logout()
	{
    	Session::delete('is_login');
        return json(['msg' => 'succeed','code' => 200, 'data' =>[]]);
	}
	/**
	 * 获取用户信息
	 */

	public function getUserInfo()
	{
		$user_id = 1;
		$data = 
		[
			'role_type'=>1,
			'role_name'=>'代理',
			'user_name'=>'账号',
			'game_list'=>[
				['game_key'=>'jlk3','name'=>'吉林快3'],
				//['game_key'=>'ssc','name'=>'重庆时时彩'],
			],
			'dish'=>['A','B','C','D'],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}
	/**
	 * 用户信用资料
	 */
	public function creditInfo()
	{
		$data = [
			'user_name'=>'dwc',
			'credit_quota'=>0,
			'use_quota'=>0,
			'quick_open_quote'=>0,
			'Ratio'=>['jlke'=>'0%','ssc'=>'0%'],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

	}



}
