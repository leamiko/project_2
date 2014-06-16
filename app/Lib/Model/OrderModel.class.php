<?php

/**
 * easy_order table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderModel extends Model {

    /**
     * Get order count
     *
     * @param string $keyword
     *            Keyword
     * @return int
     */
    public function getOrderCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'order_number' => array(
                'like',
                "%{$keyword}%"
            )
        ))->count());
    }

    /**
     * Get order list
     *
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Pagesize
     * @param string $order
     *            Order field
     * @param string $sort
     *            Sort
     * @param string $keyword
     *            Keyword
     */
    public function getOrderList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'o.order_number' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->table($this->getTableName() . " AS o")->join(array(
            "INNER JOIN " . M('Member')->getTableName() . " AS m ON o.user_id = m.id",
            "INNER JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.id",
            "INNER JOIN " . M('Shipping')->getTableName() . " AS s ON o.shipping_type = s.id"
        ))->field(array(
            'm.account' => 'username',
            'a.phone',
            'a.telephone',
            'a.zip',
            'a.address',
            's.name' => 'shipping',
            'o.id',
            'o.order_number',
            'o.pay_method',
            'o.status',
            'o.order_time',
            'o.remark'
        ))->order("o." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}