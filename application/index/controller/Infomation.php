<?php
namespace app\index\controller;
use app\index\controller\Base;


class Infomation extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}
