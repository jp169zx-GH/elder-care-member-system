## Finance.php 缴费 & 保证金管理
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class Finance
{
    //缴费列表
    public function payList()
    {
        $member_id = Request::param('member_id');
        $list = Db::table('member_pay')->where('member_id',$member_id)->order('pay_time desc')->select();
        return json($list);
    }

    //获取会员保证金
    public function bondInfo()
    {
        $member_id = Request::param('member_id');
        $info = Db::table('member_bond')->where('member_id',$member_id)->find();
        return json($info);
    }

    //新增缴费记录
    public function addPay()
    {
        $data = Request::post();
        $data['pay_time'] = date('Y-m-d H:i:s');
        Db::table('member_pay')->insert($data);
        return json(['code'=>1,'msg'=>'缴费录入成功']);
    }

    //新增保证金
    public function addBond()
    {
        $data = Request::post();
        $data['pay_bond_time'] = date('Y-m-d H:i:s');
        Db::table('member_bond')->insert($data);
        return json(['code'=>1,'msg'=>'保证金入账成功']);
    }
}
?>

