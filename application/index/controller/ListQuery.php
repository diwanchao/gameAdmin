<?php
namespace app\index\controller;
use app\index\controller\Base;


class ListQuery extends Base
{
    public function index()
    {
        return $this->fetch();
    }
  
}
