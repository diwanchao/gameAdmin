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
			'quick_open_quote'=>1000,
			'game_list'=>[
				['game_key'=>'jlk3','name'=>'吉林快3'],
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
			'role_name'=>'总代理',
			'credit_quota'=>0,
			'use_quota'=>0,
			'quick_open_quote'=>0,
			'Ratio'=>['吉林快3'=>'0%','重庆时时彩'=>'0%'],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

	}
	/**
	 * 修改密码
	 */
    public function changePassword()
    {
        $old_pwd  	= $this->request->param('old_pwd');
        $new_pwd  	= $this->request->param('new_pwd');
        $repeat_pwd = $this->request->param('repeat_pwd');
        try {
            if (!$old_pwd) 
                throw new \Exception("旧密码不能为空", 1);
            if (!$new_pwd) 
                throw new \Exception("新密码不能为空", 1);
            if (!$repeat_pwd) 
                throw new \Exception("确认密码不能为空", 1);
            if ($new_pwd!=$repeat_pwd) 
                throw new \Exception("两次输入密码不一致", 1);


            $user_data = Db::name('menber')->field('password')->where('id=?',[$this->USER_ID])->find();
            if (!$user_data) 
                throw new \Exception("用户不存在", 1);
            if ($user_data['password'] != md5($old_pwd)) 
                throw new \Exception("旧密码错误", 1);
            $update_res = Db::name('menber')->where('id', $this->USER_ID)->update(['password' => md5($new_pwd)]);
            if (!$update_res) 
                throw new \Exception("修改密码失败", 1);

            Session::set('is_login',0);
        } catch (\Exception $e) {
            return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);          
        }
        return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);

    }

}
