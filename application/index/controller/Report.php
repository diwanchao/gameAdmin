<?php
namespace app\index\controller;
use app\index\controller\Base;


class Report extends Base
{
    public function index()
    {
        return $this->fetch();
    }
  
}
