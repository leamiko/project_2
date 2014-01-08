<?php

/**
 * easy_admin_user table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminUserModel extends Model {

    /**
     * add an administrator
     *
     * @param string $username
     *            account
     * @param string $password
     *            password
     * @param string $realname
     *            real name
     * @param string $email
     *            e-mail
     * @param string $desc
     *            description
     * @return array
     */
    public function addAdministrator($username, $password, $realname, $email, $desc) {
        $result = $this->where("username = '{$username}'")->limit(1)->select();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => 'account exists!!!'
            );
        }
        $this->startTrans();
        if ($this->data(array(
            'username' => $username,
            'password' => md5($password),
            'real_name' => $realname,
            'email' => $email,
            'add_time' => time(),
            'last_time' => 0,
            'status' => 1,
            'desc' => $desc,
            'type' => 0
        ))->add()) {
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'success'
            );
        } else {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'failed'
            );
        }
    }

    /**
     * account verification
     *
     * @param string $username
     *            account
     * @param string $password
     *            account password
     * @return array
     */
    public function auth($username, $password) {
        $result = $this->where("username = '{$username}'")->limit(1)->select();
        if (empty($result)) {
            return array(
                'status' => false,
                'msg' => 'user not found'
            );
        } else {
            if (md5($password) == $result[0]['password']) {
                return array(
                    'status' => true,
                    'admin_info' => $result[0]
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => 'invalid password'
                );
            }
        }
    }

}
