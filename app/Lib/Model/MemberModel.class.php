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
        return (int) (empty($keyword) ? $this->count() : $this->where("account LIKE '%{$keyword}%' OR email LIKE '%{$keyword}%'")->count());
    }

    /**
     * get member list
     *
     * @param string $keyword
     *            keyword
     */
    public function getMemberList($page, $pageSize, $order, $sort, $keyword) {
        $this->limit(($page - 1), $pageSize)->order($order . " " . $sort);
        empty($keyword) || $this->where("account LIKE '%{$keyword}%' OR email LIKE '%{$keyword}%'");
        return $this->select();
    }

}