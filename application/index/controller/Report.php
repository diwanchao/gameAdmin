<?php
namespace app\index\controller;
use app\index\controller\Base;


class Report extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    public function reportAgent()
    {
        return $this->fetch();
    }  
    public function reportMember()
    {
        return $this->fetch();
    }
}
