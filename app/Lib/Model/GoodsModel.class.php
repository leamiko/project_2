<?php

/**
 * easy_goods table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsModel extends Model {

    /**
     * Delete goods by child category id
     *
     * @param array $child_id
     *            child category id
     * @return boolean
     */
    public function deleteGoodsByChildCateId(array $child_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'c_cate_id' => array(
                'in',
                $child_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,commit transaction
            $this->commit();
            return true;
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

    /**
     * Delete goods by parent category id
     *
     * @param array $parent_id
     *            parent category id
     * @return boolean
     */
    public function deleteGoodsByParentCateId(array $parent_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'p_cate_id' => array(
                'in',
                $parent_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,commit transaction
            $this->commit();
            return true;
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

    /**
     * Get goods count
     *
     * @param string $keyword
     *            keyword
     * @return int
     */
    public function getGoodsCount($keyword) {
        $condition = array(
            'is_delete' => 0
        );
        empty($keyword) || $condition['name'] = array(
            'like',
            "%{$keyword}%"
        );
        return (int) $this->where($condition)->count();
    }

    /**
     * Get goods list
     *
     * @param int $page
     *            current page
     * @param int $pageSize
     *            page size
     * @param string $order
     *            order field
     * @param string $sort
     *            sort
     * @param string $keyword
     *            keyword
     * @return array
     */
    public function getGoodsList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        $this->table($this->getTableName() . " AS g")->join(array(
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON g.c_cate_id = c. id",
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON g.p_cate_id = p. id"
        ))->field(array(
            'g.id',
            'g.c_cate_id',
            'g.p_cate_id',
            'g.name',
            'g.item_number',
            'g.price',
            'g.sale_amount',
            'g.unit',
            'g.size',
            'g.quality',
            'g.color',
            'g.area',
            'g.pay_method',
            'g.guarantee',
            'g.stock',
            'g.description',
            'g.add_time',
            'g.update_time',
            'c.name' => 'child_category',
            'p.name' => 'parent_category'
        ))->order($order . " " . $sort)->limit($offset, $pageSize);
        $condition = " g.is_delete = 0 ";
        empty($keyword) || $condition .= " AND g.name LIKE \"%{$keyword}%\" ";
        return $this->where($condition)->select();
    }

}