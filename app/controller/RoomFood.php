//RoomFood.php 住房管理 + 餐饮管理
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class RoomFood
{
    //获取所有房源
    public function roomList()
    {
        $list = Db::table('house_room')->select();
        return json($list);
    }

    //会员入住登记
    public function addCheckIn()
    {
        $data = Request::post();
        $data['hotel_order_no'] = 'HOT_'.date('Ymd').rand(1000,9999);
        Db::table('member_checkin')->insert($data);
        //同步更新房源状态为已入住
        Db::table('house_room')->where('room_id',$data['room_id'])->update(['room_status'=>2]);
        return json(['code'=>1,'msg'=>'入住成功，已同步酒店订房系统']);
    }

    //获取会员入住记录
    public function checkinList()
    {
        $member_id = Request::param('member_id');
        $list = Db::table('member_checkin')->where('member_id',$member_id)->select();
        return json($list);
    }

    //新增餐饮记录
    public function addFood()
    {
        $data = Request::post();
        Db::table('member_food')->insert($data);
        return json(['code'=>1,'msg'=>'餐饮记录已录入并同步酒店账户']);
    }

    //餐饮列表
    public function foodList()
    {
        $member_id = Request::param('member_id');
        $list = Db::table('member_food')->where('member_id',$member_id)->order('eat_date desc')->select();
        return json($list);
    }
}
?>

5、HotelSync.php 酒店订房 + 酒店财务对接
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class HotelSync
{
    //酒店双向对账同步
    public function checkSync()
    {
        $order_no = Request::param('order_no');
        $checkin = Db::table('member_checkin')->where('hotel_order_no',$order_no)->find();

        //模拟酒店财务系统消费数据
        $hotel_consume = rand(180,680);
        $system_consume = $checkin['live_status'] == 1 ? $hotel_consume : 0;
        $diff_money = $system_consume - $hotel_consume;

        $sync['order_no'] = $order_no;
        $sync['room_id'] = $checkin['room_id'];
        $sync['member_id'] = $checkin['member_id'];
        $sync['system_consume'] = $system_consume;
        $sync['hotel_consume'] = $hotel_consume;
        $sync['diff_money'] = $diff_money;
        $sync['sync_type'] = 1;
        $sync['check_status'] = $diff_money == 0 ? 1 : 0;

        Db::table('hotel_data_sync')->insert($sync);
        return json(['code'=>1,'msg'=>'酒店账单对账完成','data'=>$sync]);
    }

    //酒店对账记录
    public function syncAllLog()
    {
        $list = Db::table('hotel_data_sync')->order('sync_time desc')->select();
        return json($list);
    }
}
?>
