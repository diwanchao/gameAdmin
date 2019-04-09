<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\facade\Session;
use \think\Db;


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

		$user_data = Db::name('menber')->field('role_id as role_type,rule_name as role_name,user_name,blance as quick_open_quote,game_list,part')->where('id=?',[$this->USER_ID])->find();
		if ($user_data) 
		{
			$game_data = get_user_info_by_user_id($this->USER_ID);
			$user_data['role_type'] = $user_data['role_type'] -1;
			$user_data['game_list'] = $game_data;
			$user_data['dish'] 		= explode(',', part_to_str($user_data['part']));
		}

        return json(['msg' => 'succeed','code' => 200, 'data' =>$user_data]);
	}
	/**
	 * 用户信用资料
	 */
	public function creditInfo()
	{
		$user_info = Db::name('menber')->field('user_name,role_name,blance as quick_open_quote')->where('id=?',[$this->USER_ID])->find();
		if (!Session::get('is_admin'))
		{
			$ratio_info = Db::name('proportion_log')
			->alias('p')
			->field('name,user_proportion')
			->leftJoin('game_info g','p.game_key=g.game_key')
            ->select();

			if ($ratio_info) 
				$ratio_info = array_column($ratio_info, 'user_proportion','name');
		}else{
			$ratio_info = ['吉林快3'=>'100','重庆时时彩'=>'100'];

		}

		$user_info['Ratio'] = $ratio_info ?? ['吉林快3'=>'0','重庆时时彩'=>'0'];

/*		$data = [
			'user_name'=>'dwc',
			'role_name'=>'总代理',
			'credit_quota'=>0,
			'use_quota'=>0,
			'quick_open_quote'=>0,
			'Ratio'=>['吉林快3'=>'0','重庆时时彩'=>'0'],
		];*/
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
    /**
     * 额度变动
     */
    public function limitChange()
    {
    	$where 		= [];
    	$page 		= $this->request->param('index',1);
    	$user_name 	= $this->request->param('user_name','');


    	$ids = get_sons($this->USER_ID);
    	$ids = rtrim($ids, ',');

    	$where[] = ['i.user_id','in',explode(',', $ids)];
    	if ($user_name) 
    			$where[] = ['m1.user_number','=',$user_name];

    	$data = Db::name('integral')
		->alias('i')
    	->field('i.type,i.num,i.time,m1.user_number,m2.user_number AS admin')
		->leftJoin('menber m1','i.user_id=m1.id')
		->leftJoin('menber m2','i.admin_id=m2.id')
		->where($where)
		->paginate(10,false,['var_page'=>'index']);
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

    }

}
