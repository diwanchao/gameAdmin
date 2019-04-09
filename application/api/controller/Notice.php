<?php
namespace app\api\controller;
use app\api\controller\Base;
use \think\Request;
use \think\Db;

class Notice extends Base
{

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
     * 公告列表
     */
    public function list()
    {
        $data = Db::name('notice')->field('id,content,create_time')->order('create_time desc')->paginate(10,false,['var_page'=>'index']);
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);
    }


    public function add()
    {
        $content   = $this->request->param('content','');
        $id   = $this->request->param('id',0);

        $data = [
            'content'=>$content,
            'create_time'=>date('Y-m-d H:i:s',time()),
        ];
        if ($id) 
        {
            Db::name('notice')->where('id=?',[$id])->update($data);
        }else{
            Db::name('notice')->insert($data);
        }

        return json(['msg' => '添加成功','code' => 200, 'data' =>$data]);
    }


    public function edit()
    {
        $id   = $this->request->param('id',0);
        $data = Db::name('notice')->where('id=?',[$id])->find();
        return json(['msg' => 'succeed','code' => 200, 'data' =>$data]);

    }

    public function delete()
    {
        $id   = $this->request->param('id',0);
        $data = Db::name('notice')->where('id=?',[$id])->delete();
        return json(['msg' => '删除成功','code' => 200, 'data' =>$data]);
    }


}
