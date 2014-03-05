<?php

/**
 * Api Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    public function test() {
        echo $this->sendMail('971318606@qq.com', 'banzhiyan', 'test', 'This is a test');
    }

    /**
     * Address list
     */
    public function address_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 && $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $address = M('Address');
            $result = $address->where("user_id = {$user_id}")->limit(($page - 1) * $pageSize, $pageSize)->order("add_time DESC")->select();
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Add address
     */
    public function add_address() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($user_id < 1 || empty($name) || empty($phone) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameter'
                ));
            }
            $addressModel = M('Address');
            if ($addressModel->where("user_id = {$user_id} AND name = \"{$name}\" AND phone = \"{$phone}\" AND zip = \"{$zip}\" AND address = \"{$address}\"")->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'You have already added this address'
                ));
            }
            // Start transaction
            $addressModel->startTrans();
            if ($addressModel->add(array(
                'user_id' => $user_id,
                'name' => $name,
                'phone' => $phone,
                'zip' => $zip,
                'address' => $address,
                'add_time' => time()
            ))) {
                // Add successful,commit transaction
                $addressModel->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Add address successful'
                ));
            } else {
                // Add failed,rollback transaction
                $addressModel->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Change password
     */
    public function change_password() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : $this->redirect('/');
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : $this->redirect('/');
            if ($user_id < 1 || empty($old_password) || empty($new_password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $member = M('Member');
            if ($member->where("id = {$user_id} AND password = \"" . md5($old_password) . "\"")->count()) {
                // Start transaction
                $member->startTrans();
                if ($member->where("id = {$user_id} AND password = \"" . md5($old_password) . "\"")->save(array(
                    'password' => md5($new_password)
                ))) {
                    // Change successful,commit transaction
                    $member->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => 'Change password successful'
                    ));
                } else {
                    // Change failed,rollback transaction
                    $member->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Unknown error'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid old password'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Delete address
     */
    public function delete_address() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $address = M('Address');
            // Start transaction
            $address->startTrans();
            if ($address->where("id = {$id}")->delete()) {
                // Delete successful,commit transaction
                $address->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Delete address successful'
                ));
            } else {
                // Delete failed,rollback transaction
                $address->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Get default address
     */
    public function get_default_address() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            if ($user_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $address = M('Address');
            $result = $address->where("user_id = {$user_id} AND is_default = 1")->select();
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Login
     */
    public function login() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $member = M('Member');
            if ($member->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->count()) {
                $result = $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'avatar',
                    'sex',
                    'register_time',
                    'upgrade_time',
                    'last_time'
                ))->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->select();
                foreach ($result as &$value) {
                    $value['password'] = $password;
                    $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                    $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                    $value['upgrade_time'] = $value['upgrade_time'] ? date("Y-m-d H:i:s", $value['upgrade_time']) : $value['upgrade_time'];
                    $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                }
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

    /**
     * Registration
     */
    public function register() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $email = isset($_POST['email']) ? trim($_POST['email']) : $this->redirect('/');
            $member = M('Member');
            if ($member->where("account = \"{$account}\"")->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'User already exists'
                ));
            }
            // Start transaction
            $member->startTrans();
            if ($member->add(array(
                'account' => $account,
                'password' => md5($password),
                'email' => $email,
                'status' => 0,
                'register_time' => time()
            ))) {
                // Send the email to user with verfication code
                $verificationCode = $this->generateVerificationCode();
                if ($this->sendMail($email, $account, 'EasyBuy Register Verification Code', "Dear {$account}! Thinks for registering!Your verification code is : {$verificationCode}.Enjoy you shopping!")) {
                    $lastId = $member->getLastInsID();
                    $result = $member->field(array(
                        'id',
                        'account',
                        'avatar',
                        'sex',
                        'status',
                        'is_vip',
                        'email',
                        'register_time',
                        'last_time',
                        'upgrade_time'
                    ))->where("id = {$lastId}")->select();
                    foreach ($result as &$v) {
                        $v['avatar'] = $v['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$v['avatar']}" : $v['avatar'];
                        $v['register_time'] = date("Y-m-d H:i:s", $v['register_time']);
                        $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                        $v['upgrade_time'] = $v['upgrade_time'] ? date("Y-m-d H:i:s", $v['upgrade_time']) : $v['upgrade_time'];
                        $v['password'] = $password;
                        $v['verification_code'] = $verificationCode;
                    }
                    // Registration successful,commit transaction
                    $member->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                }
            } else {
                // Registration failed,rollback transaction
                $member->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * resend a verification email
     */
    public function resend_verification_email() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $email = isset($_POST['email']) ? trim($_POST['email']) : $this->redirect('/');
            if (empty($account) || empty($email)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            // Send the email to user with verfication code
            $verificationCode = $this->generateVerificationCode();
            if ($this->sendMail($email, $account, 'EasyBuy Register Verification Code', "Dear {$account}! Thinks for registering!Your verification code is : {$verificationCode}.Enjoy you shopping!")) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => $verificationCode
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Resend verification email failed'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Set default address
     */
    public function set_default_address() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            if ($id < 1 || $user_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $address = M('Address');
            // Check whether have default address or not
            if ($address->where("user_id = {$user_id} AND is_default = 1")->count()) {
                // Check address with current id is default or not
                if ($address->where("user_id = {$user_id} AND is_default = 1 AND id = {$id}")->count()) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'This address was the default one'
                    ));
                } else {
                    // Start transaction
                    $address->startTrans();
                    // Set the current default one to Non-default
                    if ($address->where("user_id = {$user_id} AND is_default = 1")->save(array(
                        'is_default' => 0
                    ))) {
                        // Update successful,set the new default address
                        if ($address->where("id = {$id}")->save(array(
                            'is_default' => 1,
                            'update_time' => time()
                        ))) {
                            // Set default address successful,commit transaction
                            $address->commit();
                            $this->ajaxReturn(array(
                                'status' => 1,
                                'result' => 'Set default address successful'
                            ));
                        } else {
                            // Set default address failed,rollback transaction
                            $address->rollback();
                            $this->ajaxReturn(array(
                                'status' => 0,
                                'result' => 'Unknown error'
                            ));
                        }
                    } else {
                        // Set the current default address to Non-default failed
                        $address->rollback();
                        $this->ajaxReturn(array(
                            'status' => 0,
                            'result' => 'Unknown error'
                        ));
                    }
                }
            } else {
                // No default address before,start transaction
                $address->startTrans();
                if ($address->where("id = {$id}")->save(array(
                    'is_default' => 1
                ))) {
                    // Set default address successful,commit transaction
                    $address->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => 'Set default address successful'
                    ));
                } else {
                    // Set default address failed,rollback transaction
                    $address->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Unknown error'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Update address
     */
    public function update_address() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($id < 1 || $user_id < 1 || empty($name) || empty($phone) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $addressModel = M('Address');
            if ($addressModel->where("user_id = {$user_id} AND name = \"{$name}\" AND phone = \"{$phone}\" AND zip = \"{$zip}\" AND address = \"{$address}\" AND id != {$id}")->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'You have already added this address'
                ));
            }
            // Start transaction
            $addressModel->startTrans();
            if ($addressModel->where("id = {$id}")->save(array(
                'user_id' => $user_id,
                'name' => $name,
                'phone' => $phone,
                'zip' => $zip,
                'address' => $address,
                'update_time' => time()
            ))) {
                // Update successful,commit transaction
                $addressModel->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Update address successful'
                ));
            } else {
                // Update failed,rollback transaction
                $addressModel->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Update user status
     */
    public function update_user_status() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            if ($user_id < 1 || !in_array($status, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $member = M('Member');
            // Start transaction
            $member->startTrans();
            if ($member->where("id = {$user_id}")->save(array(
                'status' => $status
            ))) {
                // Update user status successful,commit transaction
                $member->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Update user status successful'
                ));
            } else {
                // Update user status failed,rollback transaction
                $member->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Upgrade an user
     */
    public function user_upgrade() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $is_vip = isset($_POST['is_vip']) ? intval($_POST['is_vip']) : $this->redirect('/');
            if ($user_id < 1 || !in_array($is_vip, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $member = M('Member');
            // Start transaction
            $member->startTrans();
            if ($member->where("id = {$user_id}")->save(array(
                'is_vip' => $is_vip,
                'upgrade_time' => time()
            ))) {
                // Upgrade successful,commit transaction
                $member->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Upgrade successful'
                ));
            } else {
                // Upgrade failed,rollback transaction
                $member->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Unknown error'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Send an email
     *
     * @param string $recipient
     *            recipient
     * @param string $name
     *            name of recipient
     * @param string $subject
     *            mail subject
     * @param string $body
     *            mail content
     * @param string $attachment
     *            mail attachment
     * @return boolean
     */
    protected function sendMail($recipient, $name, $subject = '', $body = '', $attachment = null) {
        $config = C('EMAIL');
        vendor('PHPMailer.class#phpmailer');
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        // smtp debug setting(0:close, 1:errors and messages, 2:message only)
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = $config['SMTP_HOST'];
        $mail->Port = $config['SMTP_PORT'];
        $mail->Username = $config['SENDER_MAIL'];
        $mail->Password = $config['SENDER_PWD'];
        $mail->SetFrom($config['SENDER_MAIL'], $config['SENDER_NAME']);
        $replyEmail = $config['REPLY_EMAIL'] ? $config['REPLY_EMAIL'] : $config['SENDER_MAIL'];
        $replyName = $config['REPLY_NAME'] ? $config['REPLY_NAME'] : $config['SENDER_NAME'];
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($recipient, $name);
        if (is_array($attachment)) {
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    /**
     * Generate the verification code
     *
     * @return number
     */
    private function generateVerificationCode() {
        return rand(100000, 999999);
    }

}