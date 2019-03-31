<?php
namespace app\index\controller;
use app\index\controller\Base;


class MemberManagement extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    public function memberInfo(){
    	return $this->fetch();
    }
    public function settingInfo(){
    	return $this->fetch();
    }    
}
