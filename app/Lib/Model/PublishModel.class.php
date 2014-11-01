<?php

/**
 * easy_publish table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class PublishModel extends Model {

    /**
     * Get publish count
     *
     * @param string $keyword
     *            Keyword
     * @return int
     */
    public function getPublishCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'goods_name' => array(
                'like',
                "%{$keyword}%"
            )
        ))->count());
    }

    /**
     * Get publish list
     *
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     * @param string $order
     *            Order field
     * @param string $sort
     *            Sort
     * @param string $keyword
     *            Keyword
     */
    public function getPublishList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'goods_name' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->order("publish_time DESC")->limit($offset, $pageSize)->select();
    }

}