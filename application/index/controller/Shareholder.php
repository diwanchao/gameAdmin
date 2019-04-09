<?php
namespace app\index\controller;
use app\index\controller\Base;


class Shareholder extends Base
{
    public function index()
    {
        return $this->fetch('gudong_management/index');
    }
    public function accountInfo(){
        return $this->fetch('gudong_management/account_info');
    }
    public function kindInfo(){
        return $this->fetch('gudong_management/kind_info');
    }     
    public function addInfo(){
        return $this->fetch('gudong_management/add_info');
    }  
    public function editInfo(){
        return $this->fetch('gudong_management/edit_info');
    }     
    public function operationLog(){
        return $this->fetch('gudong_management/operation_log');
    } 



}
