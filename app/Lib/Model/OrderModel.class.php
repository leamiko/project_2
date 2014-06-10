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
            'order_number' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}