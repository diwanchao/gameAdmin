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
	    $num = ($num<10) ? '00'.$num : '0'.$num;

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
        $game_data  = [];
        $user_game  = Db::name('menber')->where('id=?',[$user_id])->value('game_list');
        if ($user_game) 
        {
            foreach (json_decode($user_game,true) as $key => $value) 
            {
                if ($value == 'true') 
                    $item[] = $key;
            }
            $game_data          = Db::name('game_info')->field('`name`,game_key,url')->where('game_key', 'in', $item)->select();
        }


        return $game_data;
    }
    function get_ssc_number()
    {
        $openstamp  = strtotime(date('Y-m-d 00:10:00',time()));
        $closestamp = strtotime(date('Y-m-d 03:10:00',time()));

        $openstamp1  = strtotime(date('Y-m-d 07:10:00',time()));
        $closestamp1 = strtotime(date('Y-m-d 23:50:00',time()));

        $period     = 20*60;
        $now        = time(); 
        if ($now < $openstamp) 
        {
            $num = '001';
        }elseif ($now>$openstamp && $now < $closestamp) 
        {
            $num = ceil((($now-$openstamp)/$period));
        }elseif ($now>$closestamp && $now < $openstamp1) {
            $num = '010';
        }elseif ($now > $openstamp1 && $now < $closestamp1) {
            $num = ceil(($now-$openstamp1)/$period)+9;    
        }elseif ($now > $openstamp1) {
            return date('Ymd',strtotime('+1 day')).'001';
        }
        $num = $num < 9 ? '00'.$num : '0'.$num;
        return date('Ymd').$num;
    }
    /**
     * 获取子集
     */

    function get_sons($id){
        $category_ids = $id.",";
        $child_category = Db::query("select id from menber where parent_id = '{$id}'");
        foreach( $child_category as $key => $val )
            $category_ids .= get_sons( $val["id"] );
        return $category_ids;
    }
 ?>