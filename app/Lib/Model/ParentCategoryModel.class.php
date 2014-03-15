<?php

/**
 * easy_parent_category table's Model
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class ParentCategoryModel extends Model {

    /**
     *
     * @param string $name
     *            new parent category name
     * @param string $image
     *            new parent category image
     * @return array
     */
    public function addParentCategory($name, $image) {
        if ($this->where(array(
            'name' => $name,
            'is_delete' => 0
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'A same parent category existed.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'name' => $name,
            'image' => $image,
            'add_time' => time()
        ))) {
            // Add parent category successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add parent category successful'
            );
        } else {
            // Add parent category failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add parent category failed'
            );
        }
    }

    /**
     * Delete parent category
     *
     * @param array $id
     *            parent category id
     * @return array
     */
    public function deleteParentCategory(array $id) {
        $gflag = false;
        $cflag = false;
        $pflag = false;
        if (M('Goods')->where(array(
            'is_delete' => 0,
            'p_cate_id' => array(
                'in',
                $id
            )
        ))->count()) {
            $gflag = D('Goods')->deleteGoodsByParentCateId($id);
        } else {
            $gflag = true;
        }
        if (M('ChildCategory')->where(array(
            'is_delete' => 0,
            'parent_id' => array(
                'in',
                $id
            )
        ))->count()) {
            $cflag = D('ChildCategory')->deleteChildCateByParentCateId($id);
        } else {
            $cflag = true;
        }
        // start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // delete successful,commit transaction
            $this->commit();
            $pflag = true;
        } else {
            // delete failed,rollback transaction
            $this->rollback();
        }
        if ($gflag && $cflag && $pflag) {
            return array(
                'status' => true,
                'msg' => 'Delete parent category successful'
            );
        } else {
            return array(
                'status' => false,
                'msg' => 'Delete parent category failed'
            );
        }
    }

    /**
     * Get Parent Category amount
     *
     * @return int
     */
    public function getParentCategoryCount() {
        return (int) $this->where("is_delete = 0")->count();
    }

    /**
     * Get Parent category list
     *
     * @param int $page
     *            current page
     * @param int $pageSize
     *            page size
     * @param string $order
     *            order field
     * @param string $sort
     *            sort
     * @return array
     */
    public function getParentCategoryList($page, $pageSize, $order, $sort) {
        return $this->where(array(
            'is_delete' => 0
        ))->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * Update parent category
     *
     * @param int $id
     *            parent category id
     * @param string $name
     *            parent category name
     * @param string $image
     *            parent category image
     * @return array
     */
    public function updateParentCategory($id, $name, $image) {
        if ($this->where(array(
            'name' => $name,
            'is_delete' => 0,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'A same parent category existed.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'image' => $image,
            'update_time' => time()
        ))) {
            // Update successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Edit parent category successful'
            );
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Edit parent category failed'
            );
        }
    }

}