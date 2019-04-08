<?php 
use \think\Db;
    /**
     * 获取吉林快3期数
     */
	function get_k3_number()
	{
        $openstamp 	= strtotime(date('Y-m-d 08:40:00',time()));
        $closestamp = strtotime(date('Y-m-d 21:40:00',time()));
        $period 	= 20*60;
        $now 		= time(); 

        if ($now>$closestamp) 
        {
        	return date('Ymd',strtotime('+1 day')).'01';
        }
        if ($now<$openstamp) 
        {
        	return date('Ymd',$now).'01';
        }
	    $num = ceil((($now-$openstamp)/$period))+1;
	    $num = ($num<10) ? '0'.$num : $num;

	    return date('Ymd').$num;

	}
    /**
     * 保存用户充值记录
     */
    function set_integral($user_id,$admin_id,$type_name='初始金额',$num=0)
    {
        $data = [
            'type'  => $type_name,
            'num'   => $num,
            'time'  => date('Y-m-d H:i:s',time()),
            'user_id' => $user_id,
            'admin_id'=> $admin_id
        ];
        Db::name('integral')->insert($data);
    }


    /**
     * 会员盘json转字符串
     */
    function part_to_str($part_json)
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
     * 根据user_id查询游戏信息
     */
    function get_user_info_by_user_id($user_id=0)
    {
        $item       = [];
        $user_game  = Db::name('menber')->where('id=?',[$user_id])->value('game_list');
        foreach (json_decode($user_game,true) as $key => $value) 
        {
            if ($value == 'true') 
                $item[] = $key;
        }
        $game_data          = Db::name('game_info')->field('`name`,game_key,url')->where('game_key', 'in', $item)->select();
        return $game_data;
    }


 ?>