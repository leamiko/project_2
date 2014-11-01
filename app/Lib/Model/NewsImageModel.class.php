<?php

/**
 * easy_news_image table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NewsImageModel extends Model {

    /**
     * Add news images
     *
     * @param int $news_id
     *            News id
     * @param int $add_time
     *            Add time
     * @param array $image
     *            News image
     * @return boolean
     */
    public function addNewsImage($news_id, $add_time, array $image) {
        for ($i = 0; $i < count($image); $i++) {
            $data[$i] = array(
                'news_id' => $news_id,
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
     * Delete news image
     *
     * @param array $news_id
     *            News id
     * @return boolean
     */
    public function deleteNewsImage(array $news_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'news_id' => array(
                'in',
                $news_id
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
     * Update news image
     *
     * @param int $news_id
     *            News id
     * @param int $update_time
     *            Update time
     * @return boolean
     */
    public function updateNewsImage($news_id, $update_time) {
        if (!$this->where(array(
            'is_delete' => 0,
            'news_id' => $news_id
        ))->count()) {
            return true;
        }
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'news_id' => $news_id,
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