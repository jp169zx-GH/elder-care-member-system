//BankSync.php 银行托管对接（核心对账）
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class BankSync
{
    //银行自动同步对账
    public function syncBond()
    {
        $member_id = Request::param('member_id');
        $systemBond = Db::table('member_bond')->where('member_id',$member_id)->find();

        //模拟银行官方接口返回真实余额（正式环境替换为真实银行API）
        $bank_balance = $systemBond['bond_balance'];
        $change_money = 0;

        $syncData['member_id'] = $member_id;
        $syncData['system_balance'] = $systemBond['bond_balance'];
        $syncData['bank_balance'] = $bank_balance;
        $syncData['change_money'] = $change_money;
        $syncData['change_type'] = 1;
        $syncData['bank_account_no'] = 'BOND_'.$member_id;
        $syncData['sync_result'] = '同步成功';

        Db::table('bank_bond_sync')->insert($syncData);
        //同步更新系统同步状态
        Db::table('member_bond')->where('member_id',$member_id)->update(['sync_bank_status'=>1]);

        return json([
            'code'=>1,
            'msg'=>'银行托管账户对账完成',
            'system_balance'=>$systemBond['bond_balance'],
            'bank_balance'=>$bank_balance
        ]);
    }

    //获取银行同步流水
    public function syncLog()
    {
        $member_id = Request::param('member_id');
        $log = Db::table('bank_bond_sync')->where('member_id',$member_id)->order('sync_time desc')->select();
        return json($log);
    }
}
?>
