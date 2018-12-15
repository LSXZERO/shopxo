<?php
namespace app\index\controller;

use app\service\BannerService;
use app\service\GoodsService;
use app\service\ArticleService;
use app\service\OrderService;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-02T11:11:49+0800
     */
    public function Index()
    {
        // // 服务层
        // echo Goods::Test();
        // //echo \Page::Test();
        
        // // 基础类库 统一放到base下去
        // echo \base\Page::Test();
        
        // // 支付类库
        // echo \payment\Alipay::Test();

        // return 'shopxo';
        

        // 首页轮播
        $this->assign('banner_list', BannerService::PC());

        // 楼层数据
        $this->assign('goods_floor_list', GoodsService::HomeFloorList());

        // 新闻
        $params = [
            'where' => ['a.is_enable'=>1, 'a.is_home_recommended'=>1],
            'field' => 'a.id,a.title,a.title_color,ac.name AS category_name',
            'm' => 0,
            'n' => 9,
        ];
        $this->assign('article_list', ArticleService::ArticleList($params));

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
        $this->assign('user_order_status', $user_order_status['data']);
        return $this->fetch();
    }

    // 视图
    public function view()
    {
        return $this->fetch();
    }
}
?>