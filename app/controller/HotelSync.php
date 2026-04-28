// app/controller/HotelSync.php 酒店订房 & 财务对接
php
<?php
namespace app\controller;
use think\Db;
use think\Request;

class HotelSync
{
    //双向同步酒店账单
    public function hotelCheck()
    {
        $order_no = Request::param('order_no');
        //模拟酒店返回消费金额
        $hotel_consume = rand(200,800);
        $system = Db::table('member_checkin')->where('hotel_order_no',$order_no)->find();

        $sync['order_no'] = $order_no;
        $sync['member_id'] = $system['member_id'];
        $sync['hotel_consume'] = $hotel_consume;
        $sync['check_status'] = 1;
        Db::table('hotel_data_sync')->insert($sync);

        return json(['code'=>1,'hotel_consume'=>$hotel_consume,'msg'=>'酒店财务对账完成']);
    }
}
?>
