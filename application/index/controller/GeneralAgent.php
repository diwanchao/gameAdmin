<?php
namespace app\index\controller;
use app\index\controller\Base;


class GeneralAgent extends Base
{
    public function index()
    {
        return $this->fetch('zondaili_management/index');
    }
    public function accountInfo(){
        return $this->fetch('zondaili_management/account_info');
    }
    public function kindInfo(){
        return $this->fetch('zondaili_management/kind_info');
    }     
    public function addInfo(){
        return $this->fetch('zondaili_management/add_info');
    }  
    public function editInfo(){
        return $this->fetch('zondaili_management/edit_info');
    }     
    public function operationLog(){
        return $this->fetch('zondaili_management/operation_log');
    } 



}
