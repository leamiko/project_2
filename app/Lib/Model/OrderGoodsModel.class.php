<?php

/**
 * easy_order_goods table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderGoodsModel extends Model {

    /**
     * Get Order goods detail list
     *
     * @param int $order_id
     *            Order id
     */
    public function getOrderGoodsList($order_id) {
        return $this->table($this->getTableName() . " AS og ")->join(array(
            "INNER JOIN " . M('Goods')->getTableName() . " AS g ON og.goods_id = g.id",
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON g.p_cate_id = p.id",
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON g.c_cate_id = c.id",
            "INNER JOIN " . M('Area')->getTableName() . " AS a ON g.area = a.id"
        ))->field(array(
            'g.name',
            'g.item_number',
            'g.stock',
            'g.business_model',
            'g.unit',
            'g.is_bidding',
            'g.shipping_fee',
            'g.sale_amount',
            'g.size',
            'g.weight',
            'g.color',
            'g.quality',
            'g.guarantee',
            'g.description',
            'og.goods_price',
            'og.goods_amount',
            'p.name' => 'parent_category',
            'c.name' => 'child_category',
            'a.name_zh',
            'a.name_en',
            'a.name_ar'
        ))->where(array(
            'og.order_id' => $order_id
        ))->select();
    }

}