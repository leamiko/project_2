<?php

/**
 * easy_shipping table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ShippingModel extends Model {

    /**
     * Add a shipping type
     *
     * @param string $name
     *            Shipping company name
     * @param int $business_model
     *            Business model(1:b2c, 2:b2b)
     * @param int $type
     *            Shipping type(1:air, 2:ship, 3:highway)
     * @return array
     */
    public function addShipping($name, $business_model, $type) {
        if ($this->where(array(
            'name' => $name,
            'business_model' => $business_model,
            'type' => $type
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'This shipping type already exists.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'name' => $name,
            'business_model' => $business_model,
            'type' => $type,
            'add_time' => time()
        ))) {
            // Add successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add shipping type successful'
            );
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add shipping type failed'
            );
        }
    }

    /**
     * Delete shipping type
     *
     * @param array $id
     *            Shipping type id
     * @return array
     */
    public function deleteShipping(array $id) {
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
                'msg' => 'Delete shipping type successful'
            );
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete shipping type failed'
            );
        }
    }

    /**
     * Get shipping type count
     *
     * @param string $keyword
     *            Keyword
     * @return int
     */
    public function getShippingCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'name' => array(
                'like',
                "%{$keyword}%"
            )
        ))->count());
    }

    /**
     * Get shipping type list
     *
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     * @param string $order
     *            Order field
     * @param string $sort
     *            Sort
     * @param string $keyword
     *            Keyword
     */
    public function getShippingList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'name' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * Update a shipping type
     *
     * @param int $id
     *            Shipping type id
     * @param string $name
     *            Shipping company name
     * @param int $business_model
     *            Business model(1:b2c, 2:b2b)
     * @param int $type
     *            Shipping type(1:air, 2:ship, 3:highway)
     * @return array
     */
    public function updateShipping($id, $name, $business_model, $type) {
        if ($this->where(array(
            'name' => $name,
            'business_model' => $business_model,
            'type' => $type,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'This shipping type already exists.'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'business_model' => $business_model,
            'type' => $type,
            'update_time' => time()
        ))) {
            // Update successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Update shipping type successful'
            );
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Update shipping type failed'
            );
        }
    }

}