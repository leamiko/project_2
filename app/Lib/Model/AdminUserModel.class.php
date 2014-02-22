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
        if ($this->where("username = \"{$username}\"")->count()) {
            return array(
                'status' => false,
                'msg' => 'Account exists!!!'
            );
        }
        // start transaction
        $this->startTrans();
        if ($this->add(array(
            'username' => $username,
            'password' => md5($password),
            'real_name' => $realname,
            'email' => $email,
            'add_time' => time(),
            'desc' => $desc
        ))) {
            // add successfully.commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add successfully'
            );
        } else {
            // add failed.rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add failed'
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
                'msg' => 'User not found'
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
                    'msg' => 'Invalid password'
                );
            }
        }
    }

    /**
     * Change administrator's password
     *
     * @param int $id
     *            administrator's id
     * @param string $password
     *            new password
     * @return array
     */
    public function changePassword($id, $password) {
        $this->startTrans();
        if ($this->where("id = {$id}")->save(array(
            'password' => md5($password)
        ))) {
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Change password successfully'
            );
        } else {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Change password failed'
            );
        }
    }

    /**
     * delete administrator
     *
     * @param array $id
     *            administrator's id to be deleted
     * @return array
     */
    public function deleteAdministrator(array $id) {
        $map = array();
        $map['id'] = array(
            'in',
            $id
        );
        $this->startTrans();
        if ($this->where($map)->delete()) {
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Deleted successfully'
            );
        } else {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Deleted failed'
            );
        }
    }

    /**
     * get administrator count
     */
    public function getAdministratorCount() {
        return (int) $this->count();
    }

    /**
     * get administrator list
     *
     * @param int $page
     *            current page
     * @param int $pageSize
     *            page size
     * @param string $order
     *            order field
     * @param string $sort
     *            sort
     */
    public function getAdministratorList($page, $pageSize, $order, $sort) {
        return $this->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

}
