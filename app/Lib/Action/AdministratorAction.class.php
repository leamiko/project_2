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
     * add an administrator
     */
    public function add() {
        if ($this->isAjax()) {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $realname = isset($_POST['realname']) ? $_POST['realname'] : '';
            if (empty($username) || empty($password) || empty($realname)) {
                $this->redirect('/');
            }
            $email = $_POST['email'];
            $desc = $_POST['desc'];
            $adminUser = D('adminUser');
            echo json_encode($adminUser->addAdministrator($username, $password, $realname, $email, $desc));
        } else {
            $this->display();
        }
    }

    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            empty($id) && $this->redirect('/');
            $id = explode(',', $id);
            $adminUser = D('adminUser');
            echo json_encode($adminUser->deleteAdministrator((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * administrator management
     */
    public function management() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $result = array();
            $adminUser = M('adminUser');
            $count = $adminUser->field("COUNT(1) AS total")->select();
            $result['Total'] = $count[0]['total'];
            if ($result['Total']) {
                $result['Rows'] = $adminUser->field("id, username, real_name, email,
                        FROM_UNIXTIME(add_time) AS add_time,
                        FROM_UNIXTIME(last_time) AS last_time, desc, type")->limit(($page - 1), $pageSize)->order($order . " " . $sort)->select();
            } else {
                $result['Rows'] = null;
            }
            echo json_encode($result);
        } else {
            $this->assign('type', $this->admin_info['type']);
            $this->display();
        }
    }

}
