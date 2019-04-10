<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\facade\Session;
use \think\Db;

class Member extends Base
{

	/**
	 * 会员管理列表
	 */
	public function userList()
	{
		$role_id = Session::get('role_id');
		if (!$role_id) 
        	return json(['msg' => '数据异常','code' => 201, 'data' =>[]]);

		$status 	= $this->request->param('status',1);
		$user_name 	= $this->request->param('user_name','');
		$order 		= $this->request->param('sort','create_time');
		$id 		= $this->request->param('id',$this->USER_ID);


		$where[] = ['a.role_id','=',0];
		$where[] = ['a.status','=',$status];
		if ($role_id<2) 
			$where[] = ['a.parent_id','=',$id];

		if ($user_name) 
			$where[] = ['a.user_name','=',$user_name];	

		$user_data = Db::table('menber')
		->alias('a')
		->field('a.id,b.user_name AS agent_name,a.user_name,a.user_number,a.part,a.blance AS quick_open_quote,a.create_time,a.login_time,a.status,a.bet_status')
		->leftJoin('menber b','a.parent_id=b.id')
		->where($where)
		->order($order, 'desc')
		->paginate(10,false,['var_page'=>'index'])
		->each(function($item, $key){
    		$item['part'] = part_to_str($item['part']);
    		return $item;
    	});

        return json(['msg' => 'succeed','code' => 200, 'data' =>$user_data]);
	}
	/**
	 * 总代理列表
	 */

	public function generalAgentList()
	{
		$role_id = Session::get('role_id');
		if (!$role_id) 
        	return json(['msg' => '数据异常','code' => 201, 'data' =>[]]);

		$status 	= $this->request->param('status',1);
		$user_name 	= $this->request->param('user_name','');
		$order 		= $this->request->param('sort','create_time');
		$id 		= $this->request->param('id',$this->USER_ID);


		$where[] = ['m1.role_id','=',2];
		$where[] = ['m1.status','=',$status];
		if ($role_id<4) 
			$where[] = ['m1.parent_id','=',$id];

		if ($user_name) 
			$where[] = ['m1.user_name','=',$user_name];	
		$subsql = Db::name('menber')
		->field('COUNT(1) AS count_user,parent_id')
		->where('parent_id','in','SELECT id FROM `menber` WHERE role_id = 2')
		->group('parent_id')
		->fetchSql(false)
		->buildSql();

		$data = Db::name('menber')
		->alias('m1')
		->field('m1.id,m2.user_name AS general_name,m1.user_name,m1.user_number,IFNULL(m3.count_user,0) as count_user,m1.blance AS quick_open_quote,m1.create_time,m1.login_time,m1.`status`,m1.bet_status')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->leftJoin([$subsql=> 'm3'],'m1.id=m3.parent_id')
		->where($where)
		->order('m1.'.$order, 'desc')
		->paginate(10,false,['var_page'=>'index']);


        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

	}



	/**
	 * 股东列表管理列表
	 */
	public function shareholderList()
	{
		if (!Session::get('is_admin')) 
        	return json(['msg' => '您不是管理员不能查看股东列表','code' => 201, 'data' =>[]]);
			
		$status 	= $this->request->param('status',1);
		$user_name 	= $this->request->param('user_name','');
		$order 		= $this->request->param('sort','create_time');

		$where[] = ['m1.role_id','=',3];
		$where[] = ['m1.status','=',$status];
		if ($user_name) 
			$where[] = ['m1.user_name','=',$user_name];

		$subsql = Db::name('menber')
		->field('COUNT(1) AS count_user,parent_id')
		->where('parent_id','in','SELECT id FROM `menber` WHERE role_id = 3')
		->group('parent_id')
		->buildSql();

		$data = Db::name('menber')
		->alias('m1')
		->field('m1.id,m2.user_name AS general_name,m1.user_name,m1.user_number,IFNULL(m3.count_user,0) as count_user,m1.blance AS quick_open_quote,m1.create_time,m1.login_time,m1.`status`,m1.bet_status')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->leftJoin([$subsql=> 'm3'],'m1.id=m3.parent_id')
		->where($where)
		->order('m1.'.$order, 'desc')
		->paginate(10,false,['var_page'=>'index']);

        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}


	/**
	 * 代理管理列表
	 */
	public function agentList()
	{
		$role_id = Session::get('role_id');
		if (!$role_id) 
        	return json(['msg' => '数据异常','code' => 201, 'data' =>[]]);

		$status 	= $this->request->param('status',1);
		$user_name 	= $this->request->param('user_name','');
		$order 		= $this->request->param('sort','create_time');
		$id 		= $this->request->param('id',$this->USER_ID);


		$where[] = ['m1.role_id','=',1];
		$where[] = ['m1.status','=',$status];
		if ($role_id<3) 
			$where[] = ['m1.parent_id','=',$id];

		if ($user_name) 
			$where[] = ['m1.user_name','=',$user_name];	
		$subsql = Db::name('menber')
		->field('COUNT(1) AS count_user,parent_id')
		->where('parent_id','in','SELECT id FROM `menber` WHERE role_id = 1')
		->group('parent_id')
		->fetchSql(false)
		->buildSql();

		$data = Db::name('menber')
		->alias('m1')
		->field('m1.id,m2.user_name AS general_name,m1.user_name,m1.user_number,IFNULL(m3.count_user,0) as count_user,m1.blance AS quick_open_quote,m1.create_time,m1.login_time,m1.`status`,m1.bet_status')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->leftJoin([$subsql=> 'm3'],'m1.id=m3.parent_id')
		->where($where)
		->order('m1.'.$order, 'desc')
		->paginate(10,false,['var_page'=>'index']);


        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}

	/**
	 * 获取快开额度
	 */
	public function getQuick()
	{
		$type 	= $this->request->param('type',0);
		$id 	= $this->request->param('id',0);
		$user_data 	= Db::name('menber')
		->alias('m1')
		->field('m1.blance,m1.role_id,m2.blance as parent_blance')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->where('m1.id=?',[$id])
		->find();
		if ($type == 0) 
		{
			if (($user_data['role_id'] ?? 0) == 3) 
				$number = 99999;
			else
				$number = $user_data['parent_blance'] ?? 0;
		}else{
			$number = $user_data['blance'] ?? 0;
		}

        return json(['msg' => 'succeed','code' => 200, 'data' =>['number'=>$number]]);

	}

	/**
	 * 存入快开额度
	 */
	public function setQuick()
	{
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);
		$number 	= $this->request->param('number',0);

		$user_data 	= Db::name('menber')
		->alias('m1')
		->field('m1.blance,m1.role_id,m2.blance as parent_blance,m1.parent_id')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->where('m1.id=?',[$user_id])
		->fetchSql(false)
		->find();

		Db::startTrans();
		try {
			if (!$user_id) 
				throw new \Exception("数据异常", 1);

			if ($type) 
			{
				if ($user_data['blance'] < $number)
					throw new \Exception("存入金额大于最大值", 1);

				Db::table('menber')->where('id', $user_id)->update(['blance' => Db::raw('blance-'.$number)]);
				Db::table('menber')->where('id', $user_data['parent_id'])->update(['blance' => Db::raw('blance+'.$number)]);
				set_integral($user_id,$this->USER_ID,'上级提取',$number);
				set_integral($user_data['parent_id'],$this->USER_ID,'提取下级',$number);
			}else{
				if ($user_data['parent_blance'] < $number)
					throw new \Exception("提取金额大于最大值", 1);

				Db::table('menber')->where('id', $user_id)->update(['blance' => Db::raw('blance+'.$number)]);
				if ($user_data['role_id'] !=3) 
				{
					Db::table('menber')->where('id', $user_data['parent_id'])->update(['blance' => Db::raw('blance-'.$number)]);
				}
				set_integral($user_id,$this->USER_ID,'上级存入',$number);
				set_integral($user_data['parent_id'],$this->USER_ID,'存入下级',$number);
			}

			Db::commit();
		} catch (\Exception $e) {

			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);   
		}
        return json(['msg' => '保存成功','code' => 200, 'data' =>[]]);
	}
	/**
	 * 变更账号状态
	 */

	public function changeMemberStatus()
	{
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);

		Db::name('menber')->where('id', $user_id)->update(['status' => $type]);

        return json(['msg' => 'succeed','code' => 200, 'data' =>[]]);
	}
	/**
	 * 变更是否投注状态
	 */
	public function changeBet(){
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);
		Db::name('menber')->where('id', $user_id)->update(['bet_status' => $type]);
        return json(['msg' => 'succeed','code' => 200, 'data' =>[]]);	
	}
	/**
	 * 新建会员
	 */

	public function addUser()
	{
		$parent_id 		= $this->request->param('agent_name',0);
		$user_number 	= $this->request->param('user_num',0);
		$user_name 		= $this->request->param('user_name',0);
		$password 		= $this->request->param('password',0);
		$confirm_pwd 	= $this->request->param('confirm_pwd',0);
		$blance 		= $this->request->param('quick_open_quote',0);
		$part 			= $this->request->param('part/a',0);
		$game_list 		= $this->request->param('game/a',0);

		Db::startTrans();
		try {
			if (Session::get('role_id') != 1) 
				throw new \Exception("没有权限添加会员账号");
			if (!$user_number) 
				throw new \Exception("账号不能为空");
			if (!$user_name) 
				throw new \Exception("会员名称不能为空");
			if (!$password) 
				throw new \Exception("密码不能为空");
			if ($password != $confirm_pwd) 
				throw new \Exception("两次密码输入不一致");

			$parent_blance = Db::name('menber')->where('id=?',[$this->USER_ID])->value('blance');
			if ($parent_blance < $blance) 
				throw new \Exception("代理可用额度不够");

			$data = [
				'parent_id' => $this->USER_ID,
				'password' 	=> md5($password),
				'user_name' => $user_name,
				'blance' 	=> $blance,
				'rule_name' => '会员',
				'game_list' => json_encode($game_list),
				'part' 		=> json_encode($part),
				'user_number' => $user_number,
				'create_time' => date('Y-m-d H:i:s',time()),
			];

			$user_id  = Db::name('menber')->insertGetId($data);
			set_integral($user_id,$this->USER_ID,'存入金额',$blance);
			Db::name('menber')->where('id=?',[$this->USER_ID])->update(['blance' => Db::raw('blance-'.$blance)]);
			$this->init_user_method($user_id,'jlk3');
			$this->init_user_method($user_id,'ssc');

			Db::commit();

		} catch (\Exception $e) {
			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
		}

        return json(['msg' => '添加成功','code' => 200, 'data' =>[]]);	

	}

	/**
	 * 新建股东
	 */

	public function addShareholder()
	{
		$parent_id 		= $this->request->param('agent_name',0);
		$user_number 	= $this->request->param('user_num',0);
		$user_name 		= $this->request->param('user_name',0);
		$password 		= $this->request->param('password',0);
		$confirm_pwd 	= $this->request->param('confirm_pwd',0);
		$blance 		= $this->request->param('quick_open_quote',0);
		$part 			= $this->request->param('part/a',[]);
		$game_list 		= $this->request->param('game/a',[]);
		$accountList 	= $this->request->param('accountList/a',[]);

		Db::startTrans();
		try {
			if (!Session::get('is_admin')) 
				throw new \Exception("您不是超级管理员,不能添加股东账号");
			if (!$user_number) 
				throw new \Exception("账号不能为空");
			if (!$user_name) 
				throw new \Exception("会员名称不能为空");
			if (!$password) 
				throw new \Exception("密码不能为空");
			if ($password != $confirm_pwd) 
				throw new \Exception("两次密码输入不一致");

			$data = [
				'parent_id' => $this->USER_ID,
				'password' 	=> md5($password),
				'user_name' => $user_name,
				'blance' 	=> $blance,
				'rule_name' => '股东',
				'role_id' 	=> '3',
				'user_number' => $user_number,
				'game_list' => json_encode($game_list),
				'part' 		=> json_encode($part),
				'create_time' => date('Y-m-d H:i:s',time()),
			];

			$user_id  = Db::name('menber')->insertGetId($data);
			set_integral($user_id,$this->USER_ID,'存入金额',$blance);
			$this->init_user_method($user_id,'jlk3');
			$this->init_user_method($user_id,'ssc');
			$this->add_account_list($accountList,$user_id);

			Db::commit();

		} catch (\Exception $e) {
			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
		}
        return json(['msg' => '添加成功','code' => 200, 'data' =>[]]);	
	}

	/**
	 * 新建总代理
	 */

	public function addGeneralAgent()
	{
		$parent_id 		= $this->request->param('agent_name',0);
		$user_number 	= $this->request->param('user_num',0);
		$user_name 		= $this->request->param('user_name',0);
		$password 		= $this->request->param('password',0);
		$confirm_pwd 	= $this->request->param('confirm_pwd',0);
		$blance 		= $this->request->param('quick_open_quote',0);
		$part 			= $this->request->param('part/a',[]);
		$game_list 		= $this->request->param('game/a',[]);
		$accountList 	= $this->request->param('accountList/a',[]);

		Db::startTrans();
		try {
			if (Session::get('role_id') != 3) 
				throw new \Exception("没有权限添加总代理账号");
			if (!$user_number) 
				throw new \Exception("账号不能为空");
			if (!$user_name) 
				throw new \Exception("会员名称不能为空");
			if (!$password) 
				throw new \Exception("密码不能为空");
			if ($password != $confirm_pwd) 
				throw new \Exception("两次密码输入不一致");

			$parent_blance = Db::name('menber')->where('id=?',[$this->USER_ID])->value('blance');
			if ($parent_blance < $blance) 
				throw new \Exception("代理可用额度不够");

			$data = [
				'parent_id' => $this->USER_ID,
				'password' 	=> md5($password),
				'user_name' => $user_name,
				'blance' 	=> $blance,
				'rule_name' => '总代理',
				'role_id' 	=> '2',
				'user_number' => $user_number,
				'game_list' => json_encode($game_list),
				'part' 		=> json_encode($part),
				'create_time' => date('Y-m-d H:i:s',time()),
			];

			$user_id  = Db::name('menber')->insertGetId($data);
			Db::name('menber')->where('id=?',[$this->USER_ID])->update(['blance' => Db::raw('blance-'.$blance)]);
			set_integral($user_id,$this->USER_ID,'存入金额',$blance);
			$this->init_user_method($user_id,'jlk3');
			$this->init_user_method($user_id,'ssc');
			$this->add_account_list($accountList,$user_id);

			Db::commit();

		} catch (\Exception $e) {
			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
		}
        return json(['msg' => '添加成功','code' => 200, 'data' =>[]]);	
	}

	/**
	 * 新建设置游戏占比
	 */

	public function add_account_list($data=[],$user_id)
	{

		$insert_data  = [];
		if ($data['jlk3'] ?? '') 
		{
			$insert_data[] = [
				'user_id'			=>$user_id,
				'user_proportion'	=>$data['jlk3']['member'],
				'parent_id'			=>$this->USER_ID,
				'parent_proportion'	=>$data['jlk3']['agent'],
				'game_key'			=>'jlk3',
			];
		}
		if ($data['ssc'] ?? '') 
		{
			$insert_data[] = [
				'user_id'			=>$user_id,
				'user_proportion'	=>$data['ssc']['member'],
				'parent_id'			=>$this->USER_ID,
				'parent_proportion'	=>$data['ssc']['agent'],
				'game_key'			=>'ssc',
			];
		}
		if ($insert_data) 
			Db::name('proportion_log')->insertAll($insert_data);
	}


	/**
	 * 初始化游戏玩法
	 */
	public function init_user_method($user_id,$game_key)
	{
		if ($game_key == 'jlk3') 
		{
			$data = [
				['methods'=>'二同号复选','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'二不同号','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三同号单选','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'二同号单选','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三不同号','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和值','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和值大小','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和值单双','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三同号通选','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三连号通选','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'半顺','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'全顺','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'杂','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'跨','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'4码黑','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'4码红','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'5码黑','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'红大小','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'红单双','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'黑大小','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'黑单双','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和值大单双','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和值小单双','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'黑码','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'红码','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三军','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],

			];
			Db::name('user_game_method')->insertAll($data);
		}

		if ($game_key == 'ssc') 
		{
			$data = [
				['methods'=>'一字组合','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'二字组合','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'三字组合','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'百定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'拾定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'个定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'百拾定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'百个定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'双面','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'和数','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'尾数','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'一字过关','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'跨度','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'组选三','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'组选六','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'复式组合','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'一字组合[全五]	','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'万定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'千定位','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'豹子','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'顺子','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'对子','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'半顺','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'杂六','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'准对','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'不出号','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'龙虎','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'龙虎和局','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],
				['methods'=>'总和','A'=>1,'B'=>1,'C'=>1,'D'=>1,'limit'=>2,'max'=>10000,'min'=>2,'user_id'=>$user_id,'game_key'=>$game_key],

			];
			Db::name('user_game_method')->insertAll($data);
		}


	}


	/**
	 * 编辑会员
	 */
	public function editUser()
	{
		$return_data = [];

		$user_id 	= $this->request->param('id',0);

		$return_data = Db::name('menber')
		->alias('a')
		->field('a.id,b.user_name AS agent_name,a.user_name,a.user_number as user_num,a.part,a.blance AS quick_open_quote,a.game_list as game')
		->leftJoin('menber b','a.parent_id=b.id')
		->where('a.id','=',$user_id)
		->find();

		if ($return_data)
		{
			$return_data['part'] = json_decode($return_data['part']);
			$return_data['game'] = json_decode($return_data['game']);
		}

        return json(['msg' => 'succeed','code' => 200, 'data' =>$return_data]);	
	}
	/**
	 * 编辑代理
	 */
	public function editAgent()
	{
		$user_id 	= $this->request->param('id',0);

		$data 	= Db::name('menber')
		->alias('m1')
		->field('m1.blance as quick_open_quote,m2.user_name as general_name,m1.user_name,m1.user_number as user_num,m2.blance as usable_quote')
		->leftJoin('menber m2','m1.parent_id=m2.id')
		->where('m1.id=?',[$user_id])
		->find();

        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 新建代理
	 */
	public function setAgent()
	{
		$parent_id 		= $this->request->param('agent_name',0);
		$user_number 	= $this->request->param('user_num',0);
		$user_name 		= $this->request->param('user_name',0);
		$password 		= $this->request->param('password',0);
		$confirm_pwd 	= $this->request->param('confirm_pwd',0);
		$blance 		= $this->request->param('quick_open_quote',0);
		$part 			= $this->request->param('part/a',[]);
		$game_list 		= $this->request->param('game/a',[]);
		$accountList 	= $this->request->param('accountList/a',[]);

		Db::startTrans();
		try {
			if (Session::get('role_id') != 2) 
				throw new \Exception("没有权限添加代理账号");
			if (!$user_number) 
				throw new \Exception("账号不能为空");
			if (!$user_name) 
				throw new \Exception("会员名称不能为空");
			if (!$password) 
				throw new \Exception("密码不能为空");
			if ($password != $confirm_pwd) 
				throw new \Exception("两次密码输入不一致");

			$parent_blance = Db::name('menber')->where('id=?',[$this->USER_ID])->value('blance');
			if ($parent_blance < $blance) 
				throw new \Exception("代理可用额度不够");

			$data = [
				'parent_id' => $this->USER_ID,
				'password' 	=> md5($password),
				'user_name' => $user_name,
				'blance' 	=> $blance,
				'rule_name' => '代理',
				'role_id' 	=> '1',
				'user_number' => $user_number,
				'game_list' => json_encode($game_list),
				'part' 		=> json_encode($part),
				'create_time' => date('Y-m-d H:i:s',time()),
			];

			$user_id  = Db::name('menber')->insertGetId($data);
			Db::name('menber')->where('id=?',[$this->USER_ID])->update(['blance' => Db::raw('blance-'.$blance)]);
			set_integral($user_id,$this->USER_ID,'存入金额',$blance);
			$this->init_user_method($user_id,'jlk3');
			$this->init_user_method($user_id,'ssc');
			$this->add_account_list($accountList,$user_id);

			Db::commit();

		} catch (\Exception $e) {
			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
		}
        return json(['msg' => '添加成功','code' => 200, 'data' =>[]]);	
	}


	/**
	 * 修改会员资料
	 */
	public function updateUser()
	{
		$user_name 		= $this->request->param('user_name',0);
		$password 		= $this->request->param('password',0);
		$confirm_pwd 	= $this->request->param('confirm_pwd',0);
		$part 			= $this->request->param('part/a',0);
		$game_list 		= $this->request->param('game/a',0);

		if (!$user_name) 
        	return json(['msg' => '用户名不能为空','code' => 201, 'data' =>[]]);	
        if ($password != $confirm_pwd) 
        	return json(['msg' => '两次密码输入不一致','code' => 201, 'data' =>[]]);	

		$data = [
			'user_name' => $user_name,
			'game_list' => json_encode($game_list),
			'part' 		=> json_encode($part),
		];
        if ($password)
        	$data['password'] = md5($password);

        Db::name('menber')->where('id=?',[$user_id])->update($data);
        return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);
	}

	/**
	 * 编辑代理资料
	 */
	public function addAgent()
	{
		$user_name 		= $this->request->param('user_name','');
		$user_id 		= $this->request->param('id',0);
		$password 		= $this->request->param('password','');
		$confirm_pwd 	= $this->request->param('confirm_pwd','');
		if (!$user_name) 
        	return json(['msg' => '用户名不能为空','code' => 201, 'data' =>[]]);	
        if ($password != $confirm_pwd) 
        	return json(['msg' => '两次密码输入不一致','code' => 201, 'data' =>[]]);	

        $data['user_name'] = $user_name;
        if ($password)
        	$data['password'] = md5($password);

        Db::name('menber')->where('id=?',[$user_id])->update($data);
        return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);
	}
	/**
	 * 检测用户名
	 */

	public function checkUserName()
	{


		$user_name 	= $this->request->param('user_name','');
		$user   	= Db::name('menber')->where('user_number=?',[$user_name])->find();

		if ($user) {
        	return json(['msg' => '账号重复','code' => 201, 'data' =>[]]);
		}else{
        	return json(['msg' => '账号可用','code' => 200, 'data' =>[]]);
		}

	}
	/**
	 * 会员列表
	 */

	public function memberList()
	{
		$return_data = [];
		$user_id 	= $this->request->param('id',0);
		$data 		= Db::name('menber')->field('id,user_name')->where('parent_id=?',[$user_id])->select();

		if ($data)
		{
			$return_data = array_column($data, 'user_name','id');
		}
        return json(['msg' => 'succeed','code' => 200, 'data' =>$return_data]);	
	}
	/**
	 * 保存投注反水信息
	 */

	public function setMemberMethod()
	{
        return json(['msg' => '保存成功','code' => 200, 'data' =>[]]);	
	}

	/**
	 * 获取占比
	 */
	public function getProportion()
	{
		$type 		= $this->request->param('type',0);
		$ratio_info = Db::name('proportion_log')->field('game_key,user_proportion')->where('user_id','=',$this->USER_ID)->select();
		if ($ratio_info) 
			$ratio_info = ['jlk3'=>100,'ssc'=>100];
		else
			$ratio_info = ['jlk3'=>0,'ssc'=>0];

        return json(['msg' => 'succeed','code' => 200, 'data' =>$ratio_info]);	
	}

	/**
	 * 获取代理占比
	 */
	public function getAccountList()
	{
		$data 		= [];
		$id 		= $this->request->param('id',0);
		$user_data 	= Db::name('proportion_log')->field('game_key,user_proportion as member,parent_proportion as agent')->where('user_id=?',[$id])->select();
		if ($user_data) 
			$data = array_column($user_data, null,'game_key');

		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 保存代理占比
	 */
	public function setAccount()
	{
		$id 			= $this->request->param('id',0);
		$accountList 	= $this->request->param('accountList/a','');
		if (!$accountList) 
			return json(['msg' => '修改失败,数据为空','code' => 201, 'data' =>[]]);	
		if ($accountList['jlk3'] ?? '') 
		{
			$k3_data = ['user_proportion'	=>$accountList['jlk3']['member'],'parent_proportion'	=>$accountList['jlk3']['agent']];
			Db::name('proportion_log')->where("game_key = 'jlk3' and user_id=?",[$id])->update($k3_data);
		}
		if ($accountList['ssc'] ?? '') 
		{
			$ssc_data = [
				'user_proportion'	=>$accountList['ssc']['member'],
				'parent_proportion'	=>$accountList['ssc']['agent'],
			];
			Db::name('proportion_log')->where("game_key = 'ssc' and user_id=?",[$id])->update($ssc_data);
		}

		return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);	
	}
	/**
	 * 获取代理游戏信息
	 */
	public function addAgentGameInfo()
	{
		$user_id 	= $this->request->param('id',0);
		$data 		= Db::name('menber')->field('game_list as game,part')->where('id=?',[$user_id])->find();
		$data['game'] = json_decode($data['game']);
		$data['part'] = json_decode($data['part']);

		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 保存代理游戏信息
	 */
	public function setGameInfo()
	{
		$user_id 	= $this->request->param('id',0);
		$part 		= $this->request->param('part/a',0);
		$game_list 	= $this->request->param('game/a',0);
		Db::name('menber')
		->where('id=?',[$user_id])
		->update(['part'=>json_encode($part),'game_list'=>json_encode($game_list)]);

		return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);	
	}
	/**
	 * 获取操作日志
	 */
	public function getOperationLog()
	{
		$user_id = $this->request->param('id',0);

		$data = Db::name('`integral`')
		->alias('i')
		->field("CONCAT(m1.rule_name,m1.user_name,i.type) AS content, i.time,m2.user_name AS admin")
		->leftJoin('menber m1','m1.id=i.user_id')
		->leftJoin('menber m2','i.admin_id=m2.id')
		->where('user_id=?',[$user_id])
		->select();

		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}

}
