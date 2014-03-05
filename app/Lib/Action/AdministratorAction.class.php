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
            $adminUser = D('AdminUser');
            $this->ajaxReturn($adminUser->addAdministrator($username, $password, $realname, $email, $desc));
        } else {
            $this->display();
        }
    }

    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            empty($id) && $this->redirect('/');
            $id = explode(',', $id);
            $adminUser = D('AdminUser');
            echo json_encode($adminUser->deleteAdministrator((array) $id));
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
                foreach ($rows as &$value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
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
