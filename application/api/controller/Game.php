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
        $sons = get_sons($this->USER_ID);
        $money = $result = $break = $amount = 0;

        $game_key   = $this->request->param('game_key',''); 
        $part       = $this->request->param('part','');
        $now_number = $game_key == 'jlk3' ? get_k3_number() : get_ssc_number();


        $where []= ['game_key','=',$game_key];
        $where []= ['order.part','=',$part];
        $where []= ['role_id','=',0];
        $where []= ['user_id','in',explode(',', $sons)];
        $where []= ['order.number','=',$now_number];


        $bet_data   = Db::name('order')
                ->field('time,no as number,order.part,content,money,break,number as game_num,play_name,odds,game_result as result,user_number as user_num,user_name')
                ->leftJoin('menber','order.user_id=menber.id')
                ->where($where)
                ->order('no desc')
                ->fetchSql(0)
                ->select();
        if ($bet_data) {
            foreach ($bet_data as $value) {
                $money  += $value['money'] ?: 0;
                $result += $value['result'] ?: 0;
                $break  += $value['break'] ?: 0;
                $amount ++;
            }
        }


    	$data = [
            'data'=>$bet_data ?? [],
            /*[
                ['number'=>'108132','time'=>date('Y-m-d H:i:s',time()),'user_num'=>'123','user_name'=>'dwc','break'=>12,'part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'money'=>12,'result'=>10],
                ['number'=>'108132','time'=>date('Y-m-d H:i:s',time()),'user_num'=>'123','user_name'=>'dwc','break'=>12,'part'=>'A','game_num'=>'20190402018','play_name'=>'红马单双','content'=>'单','odds'=>2,'money'=>12,'result'=>10],
            ],*/
            'money'     =>$money ?? 0,
            'result'    =>$result ?? 0,
            'break'     =>$break ?? 0,
            'amount'    =>$amount ?? 0,
        ];
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }
    /**
     * 开奖结果列表
     */
    public function resultList()
    {
        $result_data= [];
        $weekarray  = array("日","一","二","三","四","五","六");
        $game_key   = $this->request->param('game_key',''); 
        $page       = $this->request->param('index',1);
        $page_row   = 10;

        $count  = Db::name('game_result')->where('game_key=?',[$game_key])->select();

        if ($count) 
        {
            $star   = ($page*$page_row)-$page_row;
            $data   = Db::name('game_result')->where("game_key=?",[$game_key])->order('number', 'desc')->limit($star,$page_row)->select();

            if ($game_key=='jlk3') 
            {
                foreach ($data as $key => $value) 
                {
                    $sum                            = 0;
                    $result_data[$key]['no']        = substr($value['number'],-2);
                    $result_data[$key]['week']      = $weekarray[date("w",strtotime($value['time']))];
                    $result_data[$key]['time']      = date('Y-m-d',strtotime($value['time']));
                    $result_data[$key]['content']   = $value['game_result'];
                    $one = str_split($value['game_result']);
                    foreach ($one as $val) {
                        $sum +=$val;
                    }
                    $result_data[$key]['sum']       = $sum;
                    $result_data[$key]['oddEven']   = ($sum%2)==0 ? '双' : '单';
                    $result_data[$key]['bigSmall']  = $sum>9 ? '大' : '小';


                }
            }
            if ($game_key == 'ssc') 
            {
                foreach ($data as $key => $value) {
                    $result_data[$key]['no']        = substr($value['number'],-2);
                    $result_data[$key]['week']      = $weekarray[date("w",strtotime($value['time']))];
                    $result_data[$key]['time']      = date('Y-m-d',strtotime($value['time']));
                    $result_data[$key]['content']   = $value['game_result'];
                    $num_data = str_split($value['game_result']);
                    $result_data[$key]['tenThousand']   = $this->count_result_num($num_data[0]);
                    $result_data[$key]['thousand']      = $this->count_result_num($num_data[1]);
                    $result_data[$key]['hundred']       = $this->count_result_num($num_data[2]);
                    $result_data[$key]['ten']           = $this->count_result_num($num_data[3]);
                    $result_data[$key]['one']           = $this->count_result_num($num_data[4]);
                }
            }
        }
        $return=[
            'total' => $count ? count($count) : 0,
            'data'  => $result_data,

        ];
        return json(['msg' => 'succeed','code' => 200, 'data' => $return]);
    }


    function count_result_num($num)
    {
        $bigSmall   = $num>9 ? '大' : '小';
        $oddEven    = ($num%2)==0 ? '双' : '单';
        $PerfectNumber = $this->isPerfectNumber($num) ? '合' : '质';

        return $bigSmall.$oddEven.$PerfectNumber;

    }
    function isPerfectNumber($N) 
    { 

        $sum = 0; 

        for ($i = 1; $i < $N; $i++) 
        { 
            if ($N % $i == 0) 
            { 
                $sum = $sum + $i; 
            }    
        } 
        return $sum == $N; 
    } 


}
