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
        if ($this->where("name = \"{$name}\"")->count()) {
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
     * delete parent category
     *
     * @param array $id
     *            parent category id
     * @return array
     */
    public function deleteParentCategory(array $id) {
        // check if have goods in this parent category
        $goods = M('Goods');
        if ($goods->where(array(
            array(
                'is_delete' => 0,
                'p_cate_id' => array(
                    'in',
                    $id
                )
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            //
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
        return $this->where("is_delete")->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * 更新商品分类
     *
     * @param int $id
     *            分类ID
     * @param string $name
     *            分类名称
     * @return array
     */
    public function updateCategory($id, $name) {
        $result = $this->where("name = \"{$name}\" AND id != {$id}")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->where("id = {$id}")->save(array(
            'name' => $name,
            'update_time' => time()
        ))) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新分类成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新分类失败'
            );
        }
    }

}