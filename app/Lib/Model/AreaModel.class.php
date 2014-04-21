<?php

/**
 * easy_area table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AreaModel extends Model {

    /**
     * Add an area
     *
     * @param string $zip_code
     *            Zip code
     * @param string $name_zh
     *            Chinese name
     * @param string $name_en
     *            English name
     * @param string $name_ar
     *            Arabic name
     * @return array
     */
    public function addArea($zip_code, $name_zh, $name_en, $name_ar) {
        if ($this->where(array(
            'zip_code' => $zip_code
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'This zip code have already exists.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'zip_code' => $zip_code,
            'name_zh' => $name_zh,
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'add_time' => time()
        ))) {
            // Add successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add area successful'
            );
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add area failed'
            );
        }
    }

    /**
     * Delete area
     *
     * @param array $id
     *            Area id
     * @return array
     */
    public function deleteArea(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // Delete successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Delete area successful'
            );
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete area failed'
            );
        }
    }

    /**
     * Get area count
     *
     * @return int
     */
    public function getAreaCount() {
        return (int) $this->count();
    }

    /**
     * Get area list
     *
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     * @param string $order
     *            Order field
     * @param string $sort
     *            Sort
     */
    public function getAreaList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * Update an area
     *
     * @param int $id
     *            Area id
     * @param string $zip_code
     *            Zip code
     * @param string $name_zh
     *            Chinese name
     * @param string $name_en
     *            English name
     * @param string $name_ar
     *            Arabic name
     * @return array
     */
    public function updateArea($id, $zip_code, $name_zh, $name_en, $name_ar) {
        if ($this->where(array(
            'zip_code' => $zip_code,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'This zip code have already exists.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'zip_code' => $zip_code,
            'name_zh' => $name_zh,
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'update_time' => time()
        ))) {
            // Update successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Update area successful'
            );
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Update area failed'
            );
        }
    }

}