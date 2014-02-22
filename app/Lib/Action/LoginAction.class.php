<?php

/**
 * Login Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class LoginAction extends Action {

    /**
     * change password
     */
    public function chpwd() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $id || $this->redirect('/');
        if ($this->isAjax()) {
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            empty($password) && $this->redirect('/');
            $adminUser = D('adminUser');
            echo json_encode($adminUser->changePassword($id, $password));
        } else {
            $this->assign('adminId', $id);
            $this->display();
        }
    }

    /**
     * login
     */
    public function index() {
        if ($this->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $adminUser = D('adminUser');
            $check = $adminUser->auth($username, $password);
            if ($check['status']) {
                session('admin_info', $check['admin_info']);
                $adminUser->where("id = {$check['admin_info']['id']}")->save(array(
                    'last_time' => time()
                ));
                $this->redirect(U('/'));
            } else {
                vendor('Message.Message');
                Message::showMsg($check['msg'], U('/login'));
            }
        } else {
            if (session('admin_info')) {
                vendor('Message.Message');
                Message::showMsg('You have already login!!!');
            } else {
                $this->display();
            }
        }
    }

    /**
     * logout
     */
    public function logout() {
        session('admin_info', null);
        $this->redirect('/login');
    }

}