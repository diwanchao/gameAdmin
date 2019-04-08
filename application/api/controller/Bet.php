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
        $user_num   = $this->request->param('user_num',''); 
        $game_key   = $this->request->param('game_key',''); 
        $time       = $this->request->param('time',date('Y-m-d',time())); 
        $status     = $this->request->param('status',''); 

        $where[] = ['time','=',$time];
        if ($game_key) 
            $where[] = ['game_key','=',$game_key];
        if ($user_num) 
            $where[] = ['user_id','=',$user_num];
        if ($status) 
            $where[] = ['status','=',$status];

        $sql = "SELECT
                user_name,
                user_number,
                o.user_id,
                o.time,
                o.game_key,
                o. NO AS number,
                o.part,
                o.number AS game_num,
                play_name,
                content,
                odds,
                game_result,
                money,
                handsel,
                break
            FROM
                `order` o
            LEFT JOIN menber m ON o.user_id = m.id";
            //echo $sql;die();
        $res = Db::query($sql);


    	$data = [
            'data'=>$res,
/*            'data'=>[
                ['user_name'=>'dwc','user_num'=>'123','user_id'=>1,'time'=>date('Y-m-d H:i:s',time()),'game_type'=>'吉林快3','number'=>'108132','part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'game_result'=>'123','money'=>12,'handsel'=>10,'break'=>1,'amount'=>1],
                ['user_name'=>'dwc','user_num'=>'123','user_id'=>1,'time'=>date('Y-m-d H:i:s',time()),'game_type'=>'吉林快3','number'=>'108132','part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'game_result'=>'123','money'=>12,'handsel'=>10,'break'=>1,'amount'=>1],
            ],*/
            'total'=>10,
            'money'=>10,
            'handsel'=>10,
            'break'=>10,
            'amount'=>2,
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
}
