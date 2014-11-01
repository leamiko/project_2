<?php

/**
 * easy_advertisement_image table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdvertisementImageModel extends Model {

    /**
     * Add advertisement images
     *
     * @param int $advertisement_id
     *            Advertisement id
     * @param int $add_time
     *            Add time
     * @param array $image
     *            Advertisement image
     * @return boolean
     */
    public function addAdvertisementImage($advertisement_id, $add_time, array $image) {
        for ($i = 0; $i < count($image); $i++) {
            $data[$i] = array(
                'advertisement_id' => $advertisement_id,
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
     * Delete advertisement image
     *
     * @param array $advertisement_id
     *            Advertisement id
     * @return boolean
     */
    public function deleteAdvertisementImage(array $advertisement_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'advertisement_id' => array(
                'in',
                $advertisement_id
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
     * Update advertisement image
     *
     * @param int $advertisement_id
     *            Advertisement id
     * @param int $update_time
     *            Update time
     * @return boolean
     */
    public function updateAdvertisementImage($advertisement_id, $update_time) {
        if (!$this->where(array(
            'is_delete' => 0,
            'advertisement_id' => $advertisement_id
        ))->count()) {
            return true;
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'advertisement_id' => $advertisement_id,
            'is_delete' => 0
        ))->save(array(
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