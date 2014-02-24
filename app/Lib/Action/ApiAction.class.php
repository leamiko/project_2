<?php

/**
 * Api Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    /**
     * Login
     *
     * @return array
     */
    public function login() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $member = M('Member');
            if ($member->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->count()) {
                $result = array_map(function ($value) use($password) {
                    $value['password'] = $password;
                    $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                    $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                    return $value;
                }, $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'avatar',
                    'sex',
                    'FROM_UNIXTIME(register_time)' => 'register_time',
                    'last_time'
                ))->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->select());
                // Update user last login time,start transaction
                $member->startTrans();
                if ($member->where("id = {$result[0]['id']}")->save(array(
                    'last_time' => time()
                ))) {
                    // Update successful,commit transaction
                    $member->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                } else {
                    // Update failed,rollback transaction
                    $member->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Update last login time failed'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid password'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

}