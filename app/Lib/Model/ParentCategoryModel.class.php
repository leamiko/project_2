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
     *            New parent category name
     * @param int $business_model
     *            Business model(1:b2c,2:b2b)
     * @param string $image
     *            New parent category image
     * @return array
     */
    public function addParentCategory($name, $business_model, $image) {
        if ($this->where(array(
            'name' => $name,
            'business_model' => $business_model,
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
            'business_model' => $business_model,
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
        return (int) ($this->where(array(
            'is_delete' => 0
        ))->count());
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
     * @param int|null $business_model
     *            Business model(1:b2c,2:b2b)
     * @return array
     */
    public function getParentCategoryList($page, $pageSize, $order, $sort, $business_model = null) {
        $offset = ($page - 1) * $pageSize;
        $condition = array(
            'is_delete' => 0
        );
        $business_model && $condition['business_model'] = $business_model;
        return $this->where($condition)->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * Update parent category
     *
     * @param int $id
     *            Parent category id
     * @param string $name
     *            Parent category name
     * @param int $business_model
     *            Business model
     * @param string $image
     *            Parent category image
     * @return array
     */
    public function updateParentCategory($id, $name, $business_model, $image) {
        if ($this->where(array(
            'name' => $name,
            'business_model' => $business_model,
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
            'business_model' => $business_model,
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