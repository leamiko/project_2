<?php

/**
 * easy_goods_image table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsImageModel extends Model {

    /**
     * Add goods image
     *
     * @param int $good_id
     *            Goods id
     * @param int $p_cate_id
     *            Parent category id
     * @param int $c_cate_id
     *            Child category id
     * @param int $add_time
     *            Add time
     * @param array $image
     *            Goods image
     * @return boolean
     */
    public function addGoodsImage($goods_id, $p_cate_id, $c_cate_id, $add_time, array $image) {
        for ($i = 0; $i < count($image); $i++) {
            $data[$i] = array(
                'goods_id' => $goods_id,
                'p_cate_id' => $p_cate_id,
                'c_cate_id' => $c_cate_id,
                'image' => $image[$i],
                'is_delete' => 0,
                'add_time' => $add_time
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->addAll($data)) {
            // Add successful,commit transaction
            $this->commit();
            return true;
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

    /**
     * Get goods images list api
     *
     * @param int $goods_id
     *            Goods is
     * @return array
     */
    public function apiGetGoodsImageList($goods_id) {
        $images = $this->field(array(
            'image'
        ))->where(array(
            'goods_id' => $goods_id,
            'is_delete' => 0
        ))->select();
        foreach ($images as &$v) {
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        return $images;
    }

    /**
     * Update goods images
     *
     * @param int $goods_id
     *            Goods id
     * @param int $p_cate_id
     *            Parent category id
     * @param int $c_cate_id
     *            Child category id
     * @param int $update_time
     *            Update time
     * @return boolean
     */
    public function updateGoodsImage($goods_id, $p_cate_id, $c_cate_id, $update_time) {
        if (!$this->where(array(
            'is_delete' => 0,
            'goods_id' => $goods_id
        ))->count()) {
            return true;
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'goods_id' => $goods_id,
            'is_delete' => 0
        ))->save(array(
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'update_time' => $update_time
        ))) {
            // Update successful,commit transaction
            $this->commit();
            return true;
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

}