<?php
namespace app\api\controller;
use think\Controller;
use \think\Request;
use \think\Db;
use think\facade\Session;
class Login extends Controller
{
    /**
     * @SWG\Post(
     *   path="/api/login",
     *   tags={"Login"},
     *   summary="登录接口",
     *   operationId="updatePetWithForm",
     *   consumes={"application/x-www-form-urlencoded"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="user_name",
     *     in="formData",
     *     description="用户名",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="user_pwd",
     *     in="formData",
     *     description="密码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="code",
     *     in="formData",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="201",description="字段不全"),
     *   security={{
     *     "petstore_auth": {"write:pets", "read:pets"}
     *   }}
     * )
     */
    public function index()
    {
        $data = $this->request->param();

        try {
        	if (!($data['user_name']??'')) 
        		throw new \Exception("用户名不能为空", 1);
        	if (!($data['user_pwd']??'')) 
        		throw new \Exception("密码不能为空", 1);
        	if (!($data['code']??'')) 
        		throw new \Exception("验证码不能为空", 1);
        	if (!captcha_check($data['code'])) 
        		throw new \Exception("验证码不正确", 1);

        	$user_data = Db::name('menber')->field('id,user_name,password,blance,role_id')->where('user_number=? and role_id !=?',[$data['user_name'],0])->find();
        	if (!$user_data) 
        		throw new \Exception("用户名不存在", 1);
        	if ($user_data['password'] != md5($data['user_pwd'])) 
        		throw new \Exception("密码错误", 1);
        	Session::set('user_name',$user_data['user_name']);
			Session::set('user_id', $user_data['id']);
			Session::set('is_login',1);
            Session::set('role_id',$user_data['role_id']);
            if($user_data['role_id'] == 4)
                Session::set('is_admin',1);

            set_integral($user_data['id'],$user_data['id'],'用户登录');
            Db::name('menber')->where('id=?',[$user_data['id']])->update(['login_time'=>date('Y-m-d H:i:s',time())]);

        } catch (\Exception $e) {
			return json(['msg' => $e->getMessage(), 'code' => 201, 'data' => []]);        	
        }
        return json(['msg' => 'succeed','code' => 200, 'data' =>[]]);
    }
}
