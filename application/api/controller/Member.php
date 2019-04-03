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

		$parent_id 	= $this->request->param('id',1);
		$status 	= $this->request->param('status',1);
		$user_name 	= $this->request->param('user_name','');
		$order 		= $this->request->param('sort','create_time');

		$where = "a.parent_id = 1 and a.status ={$status}";
		$order = "{$order} DESC";
		if ($user_name) 
			$where .= " and a.user_name='{$user_name}'";





		$sql = "SELECT b.user_name AS agent_name,a.user_name,a.user_number,a.part,a.blance AS quick_open_quote,a.create_time,a.login_time,a.status,a.bet_status FROM `menber` AS a LEFT JOIN menber AS b ON a.parent_id=b.id WHERE {$where} ORDER BY {$order}";
		echo $sql;die();

		$user_data = Db::query($sql);

		foreach ($user_data as $key => $value) 
		{
			if ($value['part']) 
				$user_data[$key]['part'] = $this->part_to_str($value['part']);
		}

		$data =[
			'total' => 10,
			'data' 	=> $user_data, /*[
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1,'bet_status'=>1],
				['agent_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','part'=>'A,C,D','id'=>1,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1,'bet_status'=>1],
			],*/
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}


	public function part_to_str($part_json)
	{
        $item       = [];
        foreach (json_decode($part_json,true) as $key => $value) 
        {
            if ($value == 'true') 
                $item[] = $key;
        }
        return implode(',', $item);

	}



	/**
	 * 代理管理列表
	 */
	public function agentList()
	{
		$data =[
			'total'=>10,
			'data'=>[
				['id'=>1,'general_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','count_user'=>2,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1,'bet_status'=>1],
				['id'=>1,'general_name'=>'dwc','user_number'=>'dwc123','user_name'=>'邸万超','count_user'=>2,'quick_open_quote'=>'570','create_time'=>'02-26 15:27:10','login_count'=>'33','login_time'=>'03-20 20:12:34','status'=>1,'bet_status'=>1],
			],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
	}

	/**
	 * 获取快开额度
	 */
	public function getQuick()
	{
		$type 	= $this->request->param('type',0);
		$data 	= ['number'=>'123'];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

	}

	/**
	 * 存入快开额度
	 */
	public function setQuick()
	{
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);
		if (!$user_id) {
	        return json(['msg' => '保存失败,数据异常.','code' => 201, 'data' =>[]]);		
		}
		$data 	= ['number'=>'123'];
        return json(['msg' => '保存成功','code' => 200, 'data' =>$data]);
	}
	/**
	 * 变更账号状态
	 */

	public function changeMemberStatus()
	{
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);
        return json(['msg' => 'succeed','code' => 200, 'data' =>[]]);
	}
	/**
	 * 变更是否投注状态
	 */
	public function changeBet(){
		$type 		= $this->request->param('type',0);
		$user_id 	= $this->request->param('id',0);
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
			if (!$user_number) 
				throw new \Exception("账号不能为空");
			if (!$user_name) 
				throw new \Exception("会员名称不能为空");
			if (!$password) 
				throw new \Exception("密码不能为空");
			if ($password != $confirm_pwd) 
				throw new \Exception("两次密码输入不一致");

			$parent_info = Db::name('menber')->field('id,blance')->where('user_number=?',['dwc'])->find();
			if ($parent_info['blance'] < $blance) 
				throw new \Exception("代理可用额度不够");

			$data = [
				'parent_id' => $parent_info['id'],
				'password' 	=> md5($password),
				'user_name' => $user_name,
				'blance' 	=> $blance,
				'rule_name' => '会员',
				'user_number' => $user_number,
				'game_list' => json_encode($game_list),
				'part' 		=> json_encode($part),
				'create_time' => date('Y-m-d H:i:s',time()),
			];

			$user_id  = Db::name('menber')->insertGetId($data);
			set_integral($user_id,1,'存入金额',$blance);
			$this->init_user_method($user_id,'jlk3');

			Db::commit();

		} catch (\Exception $e) {
			Db::rollback();
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
		}

        return json(['msg' => '添加成功','code' => 200, 'data' =>[]]);	

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

	}





	/**
	 * 编辑会员
	 */
	public function editUser()
	{
		$user_id 	= $this->request->param('id',0);
		$data = [
			'agent_name'=>'111',
			'user_num'=>'会员账号',
			'user_name'=>'会员名称',
			'quick_open_quote'=>100,
			'usable_quote'=>50,
			'part'=>['A'=>true,'B'=>false],
			'game'=>['jlk3'=>true],
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 编辑代理
	 */
	public function editAgent()
	{
		$user_id 	= $this->request->param('id',0);
		$data = [
			'general_name'=>'111',
			'user_num'=>'会员账号',
			'user_name'=>'会员名称',
			'quick_open_quote'=>100,
			'usable_quote'=>50,
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}

	/**
	 * 新增代理
	 */
	public function addAgent()
	{
		$data = $this->request->param();
        return json(['msg' => '添加成功','code' => 200, 'data' =>$data]);	

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
		$user_id = $this->request->param('id',0);
		$data =[
			'13'=>'hdj',
			'88'=>'dwc',
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
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
		$data = [
			'jlk3'=>100,
			'ssc'=>100,
		];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}

	/**
	 * 获取代理占比
	 */
	public function getAccountList()
	{
		$data = [
			'jlk3'	=> ['agent'=>100,'member'=>0],
			'ssc' 	=> ['agent'=>100,'member'=>0],
		];
		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 保存代理占比
	 */
	public function setAccount()
	{
		return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);	
	}
	/**
	 * 获取代理游戏信息
	 */
	public function addAgentGameInfo()
	{
		$data = [
			'part'	=> ['A'=>true,'B'=>false,'C'=>true,'D'=>true],
			'game' 	=> ['jlk3'=>true,'ssc'=>false],
		];	
		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}
	/**
	 * 保存代理游戏信息
	 */
	public function setAgentGameInfo()
	{
		return json(['msg' => '修改成功','code' => 200, 'data' =>[]]);	
	}

	public function getOperationLog()
	{
		$data = [
			['id'=>1,'content'=>'代理dwc登录','time'=>date('Y-m-d H:i:s',time()),'admin'=>'dwc'],
			['id'=>1,'content'=>'代理dwc登录','time'=>date('Y-m-d H:i:s',time()),'admin'=>'dwc'],

		];
		return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);	
	}

}
