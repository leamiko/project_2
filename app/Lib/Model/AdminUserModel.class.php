<?php

/**
 * easy_admin_user table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminUserModel extends Model {

    public function add($username, $password, $realname, $email, $desc) {
        $result = $this->where("username = '{$username}'")->limit(1)->select();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => 'account exists!!!'
            );
        }
        $data['username'] = $username;
        $data['password'] = md5($password);
        $data['real_name'] = $realname;
        $data['email'] = $email;
        $data['add_time'] = time();
        $data['last_time'] = 0;
        $data['status'] = 1;
       // $data['desc'] = $desc;
        $data['type'] = 0;
        dump($this->data($data)->add());exit;
        echo $this->add($data);exit;
        //$this->startTrans();
        if ($this->add($data)) {
           // $this->commit();
            return array(
                'status' => true,
                'msg' => 'success'
            );
        } else {
            //$this->rollback();
            return array(
                'status' => false,
                'msg' => 'failed'
            );
        }
    }

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
