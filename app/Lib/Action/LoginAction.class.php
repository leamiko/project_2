<?php

/**
 * Login Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class LoginAction extends Action {

    public function index() {
        if ($this->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $adminUser = D('AdminUser');
            $check = $adminUser->auth($username, $password);
            if ($check['status']) {
                session('admin_info', $check['admin_info']);
                $adminUser->where("id = {$check['admin_info']['id']}")->save(array(
                    'last_time' => time()
                ));
                $this->redirect(U('/'));
            } else {
                echo "<script type=\"text/javascript\">
                        alert('" . $check['msg'] . "');
                        window.location.href = '".U('/login')."';
                    </script>";
            }
        } else {
            if (session('admin_info')) {
                echo "<script type=\"text/javascript\">
                        alert('You have alreay login!!!');
                        history.back();
                    </script>";
            } else {
                $this->display();
            }
        }
    }

    public function logout() {
        session('admin_info', null);
        $this->redirect('/login');
    }

}