## Login.php 后台登录控制器
php
<?php
namespace app\controller;
use think\Request;

class Login
{
    //后台登录验证
    public function index()
    {
        $username = Request::post('username');
        $pwd = Request::post('pwd');

        //默认后台管理员账号密码
        if($username == "admin" && $pwd == "123456"){
            return json(['code'=>1,'msg'=>'登录成功','token'=>md5(time())]);
        }else{
            return json(['code'=>0,'msg'=>'账号密码错误']);
        }
    }
}
?>
