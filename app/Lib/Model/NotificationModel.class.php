<?php

/**
 * easy_notification table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NotificationModel extends Model {

    /**
     * Add notification
     *
     * @param string $content
     *            Notification content
     * @return array
     */
    public function addNotification($content) {
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'content' => $content,
            'add_time' => time()
        ))) {
            // Add successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add notification successful'
            );
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add notification failed,please try again later.'
            );
        }
    }

    /**
     * Delete notification
     *
     * @param array $id
     *            Notification id
     * @return array
     */
    public function deleteNotification(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // Delete notification successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Delete notification succeeful'
            );
        } else {
            // Delete notification failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete notification failed,please try again later'
            );
        }
    }

    /**
     * Get notification count
     *
     * @return number
     */
    public function getNotificationCount() {
        return (int) $this->count();
    }

    /**
     * Get notification list
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
    public function getNotificationList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

}