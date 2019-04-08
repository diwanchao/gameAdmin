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
