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
     * 删除分类
     *
     * @param array $id
     *            分类ID
     * @return array
     */
    public function deleteParentCategory(array $id) {
        $goods = M('Goods');
        // 查看该分类是否存在商品
        $count = $goods->where(array(
            'cate_id' => array(
                'in',
                $id
            )
        ))->count();
        if ($count > 0) {
            // 开启事务
            $goods->startTrans();
            // 删除该分类下的所有商品
            if ($goods->where(array(
                'cate_id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 开启事务
                $this->startTrans();
                // 删除分类
                if ($this->where(array(
                    'id' => array(
                        'in',
                        $id
                    )
                ))->delete()) {
                    // 删除成功，提交事务
                    $goods->commit();
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => '删除分类成功'
                    );
                } else {
                    // 删除失败，回滚事务
                    $goods->rollback();
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '删除分类失败'
                    );
                }
            } else {
                // 删除失败，回滚失败
                $goods->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除该分类下的商品失败，尚未能删除该分类'
                );
            }
        } else {
            // 开启事务
            $this->startTrans();
            // 删除分类
            if ($this->where(array(
                'id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 删除成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '删除分类成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除分类失败'
                );
            }
        }
    }

    /**
     * Get Parent Category amount
     *
     * @return int
     */
    public function getParentCategoryCount() {
        return (int) $this->count();
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
        return $this->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
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