<?php

/**
 * Administrator Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdministratorAction extends AdminAction {

    /**
     * Add an administrator
     */
    public function add() {
        if ($this->isAjax()) {
            $username = isset($_POST['username']) ? trim($_POST['username']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $realname = isset($_POST['realname']) ? trim($_POST['realname']) : $this->redirect('/');
            $email = isset($_POST['email']) ? trim($_POST['email']) : $this->redirect('/');
            $desc = isset($_POST['desc']) ? trim($_POST['desc']) : $this->redirect('/');
            $this->ajaxReturn(D('AdminUser')->addAdministrator($username, $password, $realname, $email, $desc));
        } else {
            $this->display();
        }
    }

    /**
     * Delete administrator(s)
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('AdminUser')->deleteAdministrator((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Administrator management
     */
    public function management() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $adminUser = D('AdminUser');
            $total = $adminUser->getAdministratorCount();
            if ($total) {
                $rows = $adminUser->getAdministratorList($page, $pageSize, $order, $sort);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                }
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Total' => $total,
                'Rows' => $rows
            ));
        } else {
            $this->assign('type', $this->admin_info['type']);
            $this->display();
        }
    }

}
