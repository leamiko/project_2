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
     * Delete goods by parent category id
     *
     * @param array $parent_id
     *            parent category id
     * @return boolean
     */
    public function deleteGoodsByParentCateId(array $parent_id) {
        // start transaction
        $this->startTrans();
        if ($this->where(array(
            'p_cate_id' => array(
                'in',
                $parent_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // delete successful,commit transaction
            $this->commit();
            return true;
        } else {
            // delete failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

}