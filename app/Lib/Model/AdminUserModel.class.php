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
     * Add an administrator
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
        if ($this->where(array(
            'username' => $username
        ))->count()) {
            return array(
                'status' => false,
                'msg' => 'Account exists!!!'
            );
        }
        // Start transaction
        $this->startTrans();
        if ($this->add(array(
            'username' => $username,
            'password' => md5($password),
            'real_name' => $realname,
            'email' => $email,
            'add_time' => time(),
            'desc' => $desc
        ))) {
            // Add successfully,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Add successfully'
            );
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add failed'
            );
        }
    }

    /**
     * Account verification
     *
     * @param string $username
     *            account
     * @param string $password
     *            account password
     * @return array
     */
    public function auth($username, $password) {
        $result = $this->where(array(
            'username' => $username
        ))->find();
        if (empty($result)) {
            return array(
                'status' => false,
                'msg' => 'User not found'
            );
        } else {
            if (md5($password) == $result['password']) {
                return array(
                    'status' => true,
                    'admin_info' => $result
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
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'password' => md5($password)
        ))) {
            // Change password successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Change password successful'
            );
        } else {
            // Change password failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Change password failed'
            );
        }
    }

    /**
     * Delete administrator
     *
     * @param array $id
     *            administrator's id to be deleted
     * @return array
     */
    public function deleteAdministrator(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // Delete successful,commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Delete administrator(s) successful'
            );
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete administrator(s) failed'
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
     * Get administrator list
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
        $offset = ($page - 1) * $pageSize;
        return $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

}
