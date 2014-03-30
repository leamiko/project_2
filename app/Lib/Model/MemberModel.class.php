<?php

/**
 * easy_member table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class MemberModel extends Model {

    /**
     * get member count
     *
     * @param string $keyword
     *            keyword
     * @return number
     */
    public function getMemberCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'account' => array(
                'like',
                "%{$keyword}%"
            ),
            'email' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        ))->count());
    }

    /**
     * get member list
     *
     * @param string $keyword
     *            keyword
     */
    public function getMemberList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'account' => array(
                'like',
                "%{$keyword}%"
            ),
            'email' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        ));
        return $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

}