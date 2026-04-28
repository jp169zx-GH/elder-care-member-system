// 后端控制器 Health.php
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class Health
{
    public function getInfo()
    {
        $member_id = Request::instance()->param('member_id');
        $data['archive'] = Db::table('health_archive')->where('member_id',$member_id)->find();
        $data['activity'] = Db::table('member_activity')->where('member_id',$member_id)->select();
        $data['treat'] = Db::table('treat_plan')->where('member_id',$member_id)->select();
        $data['drug'] = Db::table('drug_record')->where('member_id',$member_id)->select();
        return json($data);
    }

    public function addArchive()
    {
        $post = Request::instance()->post();
        Db::table('health_archive')->insert($post);
        return json(['code'=>1,'msg'=>'录入成功']);
    }
}
?>
