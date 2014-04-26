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
            $vip_only = isset($_POST['vip_only']) ? intval($_POST['vip_only']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->addNotification($vip_only, $content));
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
            $vip_only = isset($_POST['vip_only']) ? intval($_POST['vip_only']) : $this->redirect('/');
            $flag = false;
            $message = M('Notification')->field(array(
                'content'
            ))->where(array(
                'id' => $id
            ))->find();
            if ($vip_only) {
                $result = M('Member')->field(array(
                    'id'
                ))->where(array(
                    'is_vip' => 1
                ))->select();
                $vips = array();
                if (!empty($result)) {
                    $i = ceil(count($result) / 1000);
                    for ($j = 1; $j <= $i; $j++) {
                        $alias = "";
                        $length = min(array(
                            ($j * 1000),
                            count($result)
                        ));
                        for ($k = ($j - 1) * 1000; $k < $length; $k++) {
                            $alias .= $result[$k]['id'] . ",";
                        }
                        $vips[] = rtrim($alias, ",");
                    }
                }
                if ($vips) {
                    for ($i = 0; $i < count($vips); $i++) {
                        if (push($message['content'], 3, $vips[$i])) {
                            $flag = true;
                        } else {
                            break;
                        }
                    }
                }
            } else {
                $flag = push($message['content']);
            }
            if ($flag) {
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