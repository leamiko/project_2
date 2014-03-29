<?php

/**
 * easy_child_category table's Model
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class ChildCategoryModel extends Model {

    /**
     * Add a child category
     *
     * @param string $name
     *            child category name
     * @param int $parent_id
     *            parent category id
     * @param string $image
     *            child category image
     * @return array
     */
    public function addChildCategory($name, $parent_id, $image) {
        if ($this->where(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'is_delete' => 0
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'A same child category existed.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'image' => $image,
            'add_time' => time()
        ))) {
            // Add successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add child category successful'
            );
        } else {
            // Add failed,rollbak transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add child category failed'
            );
        }
    }

    /**
     * Child category list api
     *
     * @param int $parent_id
     *            Parent category id
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     */
    public function apiGetChildCategoryList($parent_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS c")->join(array(
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON p.id = c.parent_id"
        ))->field(array(
            'c.id',
            'c.parent_id',
            'c.name',
            'c.image',
            'c.add_time',
            'c.update_time',
            'c.is_delete',
            'p.name' => 'parent_name'
        ))->where(array(
            'c.parent_id' => $parent_id,
            'c.is_delete' => 0
        ))->order("c.id ASC")->limit($offset, $pageSize)->select();
    }

    /**
     * Delete child category by parent category id
     *
     * @param array $parent_id
     *            parent category id
     * @return boolean
     */
    public function deleteChildCateByParentCateId(array $parent_id) {
        // start transaction
        $this->startTrans();
        if ($this->where(array(
            'parent_id' => array(
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

    /**
     * Delete child category
     *
     * @param array $id
     *            child category id
     * @return array
     */
    public function deleteChildCategory(array $id) {
        $gflag = false;
        $cflag = false;
        if (M('Goods')->where(array(
            'is_delete' => 0,
            'c_cate_id' => array(
                'in',
                $id
            )
        ))->count()) {
            $gflag = D('Goods')->deleteGoodsByChildCateId($id);
        } else {
            $gflag = true;
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,commit transaction
            $this->commit();
            $cflag = true;
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
        }
        if ($gflag && $cflag) {
            return array(
                'status' => true,
                'msg' => 'Delete child category successful'
            );
        } else {
            return array(
                'status' => false,
                'msg' => 'Delete child category failed'
            );
        }
    }

    /**
     * Get child category amount
     *
     * @return int
     */
    public function getChildCategoryCount() {
        return (int) $this->where("is_delete = 0")->count();
    }

    /**
     * Get child category list
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
    public function getChildCategoryList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    c.id, c.name, c.image, c.add_time, c.update_time,
                    c.is_delete, p.name AS parent_name
                FROM
                    " . $this->getTableName() . " AS c
                INNER JOIN
                    easy_parent_category AS p
                ON
                    c.parent_id = p.id
                WHERE
                    c.is_delete = 0
                ORDER BY
                    c.id DESC
                LIMIT
                    {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * Update child category
     *
     * @param int $id
     *            child category id
     * @param string $name
     *            child category name
     * @param int $parent_id
     *            parent category id
     * @param string $image
     *            child category image
     * @return array
     */
    public function updateChildCategory($id, $name, $parent_id, $image) {
        if ($this->where(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'is_delete' => 0,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'A same child category existed.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'image' => $image,
            'update_time' => time()
        ))) {
            // Update successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Edit child category successful'
            );
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Edit child category failed'
            );
        }
    }

}