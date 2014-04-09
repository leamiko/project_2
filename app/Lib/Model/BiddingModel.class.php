<?php

/**
 * easy_bidding table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class BiddingModel extends Model {

    /**
     * Get bidding list by goods id
     *
     * @param int $goods_id
     *            Goods id
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     */
    public function getBiddingListByGoodsId($goods_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS b")->join(array(
            "INNER JOIN " . M('Member')->getTableName() . " AS m ON b.user_id = m.id"
        ))->field(array(
            'b.id',
            'b.goods_id',
            'b.c_cate_id',
            'b.user_id',
            'b.price',
            'b.bidding_time',
            'b.remark',
            'm.account',
            'm.phone',
            'm.avatar',
            'm.sex',
            'm.status',
            'm.is_vip',
            'm.email',
            'm.register_time',
            'm.last_time',
            'm.upgrade_time'
        ))->where(array(
            'b.goods_id' => $goods_id
        ))->order("b.bidding_time DESC")->limit($offset, $pageSize)->select();
    }

}