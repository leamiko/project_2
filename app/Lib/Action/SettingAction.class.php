<?php

/**
 * Setting Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class SettingAction extends AdminAction {

    /**
     * Add notification
     */
    public function add_notification() {
        if ($this->isAjax()) {
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->addNotification($content));
        } else {
            $this->display();
        }
    }

    /**
     * Delete notification
     */
    public function delete_notification() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', ($_POST['id'])) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->deleteNotification((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * System notification
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $notification = D('Notification');
            $total = $notification->getNotificationCount();
            if ($total) {
                $rows = $notification->getNotificationList($page, $pageSize, $order, $sort);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                }
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->display();
        }
    }

    /**
     * Push notification
     */
    public function push_notification() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $message = M('Notification')->field(array(
                'content'
            ))->where(array(
                'id' => $id
            ))->find();
            if (push($message['content'])) {
                $this->ajaxReturn(array(
                    'status' => true,
                    'msg' => 'Push notificatio successful'
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Push notificatio failed,please try again later'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

}