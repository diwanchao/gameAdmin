<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\facade\Session;
use \think\Db;

class Monitoring extends Base
{

	protected $tab_map = [
		'szhs' 			  => ['和值','和值大小','和值大单双','和值单双','和值小单双','红大小','红单双','黑大小','黑单双','黑码','红码'],
		'szzh' 			  => ['三不同号','三同号单选'],
		'ezzh' 			  => ['二不同号','二同号复选','二同号单选'],
		'slhtxsthtx' 	=> ['豹子','全顺'],
		'bszkhhm' 		=> ['5码黑','4码红','4码黑','杂','全顺','半顺','豹子','跨'],
		'sj' 			    => ['三军'],
	];


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
     * 游戏监控大屏
     */
    public function game()
    {
    	$return_data = [];


   		$game_key    = $this->request->param('game_key','');
   		$levelValue  = $this->request->param('levelValue','');
   		$periods     = $this->request->param('periods','');
   		$tab   			 = $this->request->param('tab','');

   		if ($game_key && $levelValue && $periods && $tab) 
   		{
   			if ($game_key == 'jlk3') 
   			{


   				//$periods 	= get_k3_number() == $periods ? $periods : get_k3_number();
                if ($this->tab_map[$tab] ?? '') {

           		   $data  		= Db::table('order')->field('COUNT(`no`) AS num,play_key')->where('play_name','in',$this->tab_map[$tab])->where('part','=',$levelValue)->where('number','=',$periods)->group('play_key')->select();
           		   $return_data = array_column($data,'num','play_key');
                }
   			}
   		}
        return json(['msg' => 'succeed','code' => 200, 'data' =>$return_data]);
    }


}
