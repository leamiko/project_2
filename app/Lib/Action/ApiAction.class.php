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
     * Address detail
     */
    public function address_detail() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'statu' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Address')->where(array(
                'id' => $id
            ))->select();
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
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
            $result = M('Address')->where(array(
                'user_id' => $user_id
            ))->limit(($page - 1) * $pageSize, $pageSize)->order("add_time DESC")->select();
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
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
            $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($user_id < 1 || empty($name) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameter'
                ));
            }
            $addressModel = M('Address');
            if ($addressModel->where(array(
                'user_id' => $user_id,
                'name' => $name,
                'address' => $address
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'You have already added this address.'
                ));
            }
            $data = array(
                'user_id' => $user_id,
                'name' => $name,
                'address' => $address,
                'add_time' => time()
            );
            strlen($phone) && $data['phone'] = $phone;
            strlen($telephone) && $data['telephone'] = $telephone;
            strlen($zip) && $data['zip'] = $zip;
            // Start transaction
            $addressModel->startTrans();
            if ($addressModel->add($data)) {
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
     * Advertisement list
     */
    public function advertisement_list() {
        if ($this->isPost() || $this->isAjax()) {
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if (!in_array($type, array(
                1,
                2,
                3,
                4,
                5,
                6
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Advertisement')->where(array(
                'type' => $type,
                'status' => 1
            ))->order("add_time DESC")->limit(6)->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['content'] = preg_replace("/src=\"(.+)\"/U", "src=\"http://{$_SERVER['HTTP_HOST']}$1\"", $v['content']);
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = M('AdvertisementImage')->field(array(
                        'image'
                    ))->where(array(
                        'advertisement_id' => $v['id'],
                        'is_delete' => 0
                    ))->select();
                    foreach ($v['image'] as &$v_1) {
                        $v_1['image'] = "http://{$_SERVER['HTTP_HOST']}{$v_1['image']}";
                    }
                }
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
     * Area list
     */
    public function area_list() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameter'
                ));
            }
            $result = D('Area')->getAreaList($page, $pageSize, "name_en", "ASC");
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
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
     * Bidding
     */
    public function bidding() {
        if ($this->isPost() || $this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : null;
            if ($goods_id < 1 || $c_cate_id < 1 || $user_id < 1 || $price < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $bidding = M('Bidding');
            // Start transaction
            $bidding->startTrans();
            if ($bidding->add(array(
                'goods_id' => $goods_id,
                'c_cate_id' => $c_cate_id,
                'user_id' => $user_id,
                'price' => $price,
                'remark' => strlen($remark) ? $remark : null,
                'bidding_time' => time()
            ))) {
                // Add bidding successful,commit transaction
                $bidding->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Bidding successful'
                ));
            } else {
                // Add bidding failed,rollback transaction
                $bidding->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Bidding failed'
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
     * Child category list
     */
    public function child_category_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $p_cate_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = D('ChildCategory')->apiGetChildCategoryList($p_cate_id, $page, $pageSize);
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                    $v['isSubscription'] = M('Subscription')->where(array(
                        'user_id' => $user_id,
                        'p_cate_id' => $p_cate_id,
                        'c_cate_id' => $v['id']
                    ))->count() ? 1 : 0;
                }
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
            if ($address->where(array(
                'id' => $id
            ))->delete()) {
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
     * Get bidding goods
     */
    public function get_bidding_goods() {
        if ($this->isPost() || $this->isAjax()) {
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($c_cate_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Goods')->where(array(
                'c_cate_id' => $c_cate_id,
                'is_delete' => 0,
                'is_bidding' => 1
            ))->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['image'] = M('GoodsImage')->field(array(
                        'image'
                    ))->where(array(
                        'goods_id' => $v['id'],
                        'is_delete' => 0
                    ))->order("id ASC")->select();
                    foreach ($v['image'] as &$v_1) {
                        $v_1['image'] = "http://{$_SERVER['HTTP_HOST']}{$v_1['image']}";
                    }
                    $v['bidding_list'] = D('Bidding')->getBiddingListByGoodsId($v['id'], $page, $pageSize);
                    foreach ($v['bidding_list'] as &$v_1) {
                        $v_1['avatar'] = $v_1['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$v_1['avatar']}" : $v_1['avatar'];
                        $v_1['register_time'] = date("Y-m-d H:i:s", $v_1['register_time']);
                        $v_1['last_time'] = $v_1['last_time'] ? date("Y-m-d H:i:s", $v_1['last_time']) : $v_1['last_time'];
                        $v_1['upgrade_time'] = $v_1['upgrade_time'] ? date("Y-m-d H:i:s", $v_1['upgrade_time']) : $v_1['upgrade_time'];
                    }
                }
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
            $result = M('Address')->where(array(
                'user_id' => $user_id,
                'is_default' => 1
            ))->select();
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
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
     * Goods list
     */
    public function goods() {
        if ($this->isPost() || $this->isAjax()) {
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($p_cate_id < 1 || $c_cate_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = D('Goods')->apiGetGoodsList($p_cate_id, $c_cate_id, $page, $pageSize);
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = D('GoodsImage')->apiGetGoodsImageList($v['id']);
                }
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
     * Goods detail
     */
    public function goods_detail() {
        if ($this->isPost() || $this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            if ($goods_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = D('Goods')->getGoodsDetail($goods_id);
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = D('GoodsImage')->apiGetGoodsImageList($v['id']);
                }
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
     * Home page news list
     */
    public function home_page_news_list() {
        if ($this->isPost() || $this->isAjax()) {
            $language = isset($_POST['language']) ? $_POST['language'] : $this->redirect('/');
            if (!in_array($language, array(
                1,
                2,
                3
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('News')->where(array(
                'type' => 1,
                'language' => $language
            ))->order("add_time DESC")->limit(6)->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['content'] = preg_replace("/src=\"(.+)\"/U", "src=\"http://{$_SERVER['HTTP_HOST']}$1\"", $v['content']);
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = M('NewsImage')->field(array(
                        'image'
                    ))->where(array(
                        'news_id' => $v['id'],
                        'is_delete' => 0
                    ))->select();
                    foreach ($v['image'] as &$v_1) {
                        $v_1['image'] = "http://{$_SERVER['HTTP_HOST']}{$v_1['image']}";
                    }
                }
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
     * My order list
     */
    public function my_order_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Order')->where(array(
                'user_id' => $user_id
            ))->order("order_time DESC")->limit(($page - 1) * $pageSize, $pageSize)->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['order_time'] = date("Y-m-d H:i:s", $v['order_time']);
                    $total_price = M('OrderGoods')->field(array(
                        'SUM(goods_price)' => 'total_price'
                    ))->where(array(
                        'order_id' => $v['id']
                    ))->select();
                    $v['total_price'] = $total_price[0]['total_price'];
                    $temp = M('Shipping')->field(array(
                        'name'
                    ))->where(array(
                        'id' => $v['shipping_type']
                    ))->find();
                    $v['shipping_type'] = $temp['name'];
                }
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
     * My publish list
     */
    public function my_publish_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Publish')->where(array(
                'user_id' => $user_id
            ))->order("publish_time DESC")->limit(($page - 1) * $pageSize, $pageSize)->select();
            foreach ($result as &$v) {
                $v['image_1'] = $v['image_1'] ? "http://{$_SERVER['HTTP_HOST']}{$v['image_1']}" : $v['image_1'];
                $v['image_2'] = $v['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$v['image_2']}" : $v['image_2'];
                $v['image_3'] = $v['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$v['image_3']}" : $v['image_3'];
                $v['image_4'] = $v['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$v['image_4']}" : $v['image_4'];
                $v['publish_time'] = date("Y-m-d H:i:s", $v['publish_time']);
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
     * My shipping agency list
     */
    public function my_shipping_agency_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('ShippingAgency')->where(array(
                'user_id' => $user_id
            ))->order("add_time DESC")->limit(($page - 1) * $pageSize, $pageSize)->select();
            foreach ($result as &$v) {
                $v['loading_time'] = date("Y-m-d H:i:s", $v['loading_time']);
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
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
     * Order
     */
    public function order() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $goods_detail = isset($_POST['goods_detail']) ? trim($_POST['goods_detail']) : $this->redirect('/');
            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : $this->redirect('/');
            $shipping_type = isset($_POST['shipping_type']) ? intval($_POST['shipping_type']) : $this->redirect('/');
            $pay_method = isset($_POST['pay_method']) ? trim($_POST['pay_method']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : null;
            if ($user_id < 0 || empty($goods_detail) || $address_id < 0 || $shipping_type < 0 || empty($pay_method) || !in_array($status, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $order_number = $this->generateOrderNumber($user_id);
            $data = array(
                'user_id' => $user_id,
                'address_id' => $address_id,
                'shipping_type' => $shipping_type,
                'pay_method' => $pay_method,
                'order_number' => $order_number,
                'status' => $status,
                'order_time' => time()
            );
            strlen($remark) && $data['remark'] = $remark;
            $order = M('Order');
            // Start transaction
            $order->startTrans();
            if ($order->add($data)) {
                $order_id = $order->getLastInsID();
                // Add successful,add the order goods
                $goods_detail = json_decode($goods_detail);
                $dataList = array();
                foreach ($goods_detail as $v) {
                    $dataList[] = array(
                        'goods_id' => $v->id,
                        'goods_price' => $v->price,
                        'goods_amount' => $v->amount,
                        'order_id' => $order_id
                    );
                }
                $order_goods = M('OrderGoods');
                // Start transaction
                $order_goods->startTrans();
                if ($order_goods->addAll($dataList)) {
                    // Add order goods successful,commit transaction
                    $order_goods->commit();
                    $order->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => array(
                            'order_id' => $order_id
                        )
                    ));
                } else {
                    // Add order goods failed,rollback transaction
                    $order_goods->rollback();
                    $order->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Unknown error'
                    ));
                }
            } else {
                // Add failed,rollback transaction
                $order->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Add order failed'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Order goods list
     */
    public function order_goods_list() {
        if ($this->isPost() || $this->isAjax()) {
            $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($order_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('OrderGoods')->where(array(
                'order_id' => $order_id
            ))->order("id ASC")->limit(($page - 1) * $pageSize, $pageSize)->select();
            foreach ($result as &$v) {
                $temp = M('Goods')->field(array(
                    'name',
                    'shipping_fee'
                ))->where(array(
                    'id' => $v['goods_id']
                ))->find();
                $shipping_type = M('Order')->field(array('shipping_type'))->where(array('id' => $order_id))->find();
                $v['shipping_type'] = $shipping_type['shipping_type'];
                $v['goods_name'] = $temp['name'];
                $v['shipping_fee'] = $temp['shipping_fee'];
                $v['goods_image'] = M('GoodsImage')->field(array(
                    'CONCAT("' . "http://{$_SERVER['HTTP_HOST']}" . '", image)' => 'image'
                ))->where(array(
                    'goods_id' => $v['goods_id']
                ))->select();
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
            if (empty($account) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $member = M('Member');
            if ($member->where(array(
                'account' => $account,
                'password' => md5($password)
            ))->count()) {
                $result = $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'avatar',
                    'sex',
                    'status',
                    'is_vip',
                    'email',
                    'register_time',
                    'upgrade_time',
                    'last_time'
                ))->where(array(
                    'account' => $account,
                    'password' => md5($password)
                ))->select();
                foreach ($result as &$value) {
                    $value['password'] = $password;
                    $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                    $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                    $value['upgrade_time'] = $value['upgrade_time'] ? date("Y-m-d H:i:s", $value['upgrade_time']) : $value['upgrade_time'];
                    $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                }
                // Update user last login time,start transaction
                $member->startTrans();
                if ($member->where(array(
                    'id' => $result[0]['id']
                ))->save(array(
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
                    'result' => 'Account and the password are not match.'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * News list
     */
    public function news_list() {
        if ($this->isPost() || $this->isAjax()) {
            $language = isset($_POST['language']) ? $_POST['language'] : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if (!in_array($language, array(
                1,
                2,
                3
            )) || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameter'
                ));
            }
            $result = M('News')->where(array(
                'language' => $language
            ))->order("add_time DESC")->limit(($page - 1) * $pageSize, $pageSize)->select();
            foreach ($result as &$v) {
                $v['content'] = preg_replace("/src=\"(.+)\"/U", "src=\"http://{$_SERVER['HTTP_HOST']}$1\"", $v['content']);
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                $v['image'] = M('NewsImage')->field(array(
                    'image'
                ))->where(array(
                    'news_id' => $v['id'],
                    'is_delete' => 0
                ))->select();
                foreach ($v['image'] as &$v_1) {
                    $v_1['image'] = "http://{$_SERVER['HTTP_HOST']}{$v_1['image']}";
                }
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
     * Parent category list
     */
    public function parent_category_list() {
        if ($this->isPost() || $this->isAjax()) {
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = D('ParentCategory')->getParentCategoryList($page, $pageSize, "id", "ASC", $business_model);
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
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
     * Publish
     */
    public function publish() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $goods_name = isset($_POST['goods_name']) ? trim($_POST['goods_name']) : $this->redirect('/');
            $publisher_second_name = isset($_POST['publisher_second_name']) ? trim($_POST['publisher_second_name']) : $this->redirect('/');
            $publisher_first_name = isset($_POST['publisher_first_name']) ? trim($_POST['publisher_first_name']) : $this->redirect('/');
            $country = isset($_POST['country']) ? trim($_POST['country']) : $this->redirect('/');
            $carton = isset($_POST['carton']) ? intval($_POST['carton']) : $this->redirect('/');
            $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $email = isset($_POST['email']) ? trim($_POST['email']) : $this->redirect('/');
            $company = isset($_POST['company']) ? trim($_POST['company']) : null;
            $image_1 = isset($_POST['image_1']) ? trim($_POST['image_1']) : $this->redirect('/');
            $image_2 = isset($_POST['image_2']) ? trim($_POST['image_2']) : null;
            $image_3 = isset($_POST['image_3']) ? trim($_POST['image_3']) : null;
            $image_4 = isset($_POST['image_4']) ? trim($_POST['image_4']) : null;
            $length = isset($_POST['length']) ? trim($_POST['length']) : null;
            $width = isset($_POST['width']) ? trim($_POST['width']) : null;
            $height = isset($_POST['height']) ? trim($_POST['height']) : null;
            $thickness = isset($_POST['thickness']) ? trim($_POST['thickness']) : null;
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : null;
            $color = isset($_POST['color']) ? trim($_POST['color']) : null;
            $use = isset($_POST['use']) ? trim($_POST['use']) : null;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : $this->redirect('/');
            $material = isset($_POST['material']) ? trim($_POST['material']) : null;
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : null;
            if ($user_id < 1 || !in_array($type, array(
                0,
                1
            )) || empty($goods_name) || empty($publisher_second_name) || empty($publisher_first_name) || empty($country) || empty($telephone) || empty($phone) || empty($email) || empty($image_1) || $quantity < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $data = array(
                'user_id' => $user_id,
                'type' => $type,
                'goods_name' => $goods_name,
                'publisher_second_name' => $publisher_second_name,
                'publisher_first_name' => $publisher_first_name,
                'country' => $country,
                'carton' => $carton,
                'telephone' => $telephone,
                'phone' => $phone,
                'email' => $email,
                'image_1' => $this->saveImage('publish_', $image_1),
                'quantity' => $quantity,
                'publish_time' => time()
            );
            strlen($company) && $data['company'] = $company;
            strlen($length) && $data['length'] = intval($length);
            strlen($width) && $data['width'] = intval($width);
            strlen($height) && $data['height'] = intval($height);
            strlen($thickness) && $data['thickness'] = intval($thickness);
            strlen($weight) && $data['weight'] = intval($weight);
            strlen($color) && $data['color'] = $color;
            strlen($use) && $data['use'] = $use;
            strlen($material) && $data['material'] = $material;
            strlen($remark) && $data['remark'] = $remark;
            strlen($image_2) && $data['image_2'] = $this->saveImage('publish_', $image_2);
            strlen($image_3) && $data['image_3'] = $this->saveImage('publish_', $image_3);
            strlen($image_4) && $data['image_4'] = $this->saveImage('publish_', $image_4);
            $publis = M('Publish');
            // Start transaction
            if ($publis->add($data)) {
                // Add successful,commit transaction
                $publis->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Publish successful'
                ));
            } else {
                // Add failed,rollback transaction
                $publis->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Publish failed'
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
            if (empty($account) || empty($password) || empty($email)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
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
                if ($this->sendMail($email, $account, 'EasyBuy Register Verification Code', "Dear {$account}! Thinks for registering!Your verification code is : {$verificationCode}.Enjoy your shopping!")) {
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
     * Search
     */
    public function search() {
        if ($this->isPost() || $this->isAjax()) {
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : $this->redirect('/');
            $zip_code_id = isset($_POST['zip_code_id']) ? intval($_POST['zip_code_id']) : null;
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if (!in_array($business_model, array(
                1,
                2
            )) || empty($business_model) || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = D('Goods')->searchGoods($business_model, $keyword, $zip_code_id, $page, $pageSize);
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = D('GoodsImage')->apiGetGoodsImageList($v['id']);
                }
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
            if ($address->where(array(
                'user_id' => $user_id,
                'id' => $id,
                'is_default' => 1
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'This address was the default one'
                ));
            }
            // Start transaction
            $address->startTrans();
            if ($address->where(array(
                'user_id' => $user_id,
                'is_default' => 1
            ))->count()) {
                // Set all address to normal
                if (!$address->where(array(
                    'user_id' => $user_id
                ))->save(array(
                    'is_default' => 0
                ))) {
                    // Set all addresses to normal failed,rollback transaction
                    $address->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Unknown error'
                    ));
                }
            }
            if ($address->where(array(
                'id' => $id
            ))->save(array(
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
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Shipping agency
     */
    public function shipping_agency() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : $this->redirect('/');
            $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : $this->redirect('/');
            $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $email = isset($_POST['email']) ? trim($_POST['email']) : $this->redirect('/');
            $company = isset($_POST['company']) ? trim($_POST['company']) : $this->redirect('/');
            $country = isset($_POST['country']) ? trim($_POST['country']) : $this->redirect('/');
            $goods_name = isset($_POST['goods_name']) ? trim($_POST['goods_name']) : $this->redirect('/');
            $shipping_type = isset($_POST['shipping_type']) ? trim($_POST['shipping_type']) : $this->redirect('/');
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : $this->redirect('/');
            $shipping_port = isset($_POST['shipping_port']) ? trim($_POST['shipping_port']) : $this->redirect('/');
            $destination_port = isset($_POST['destination_port']) ? trim($_POST['destination_port']) : $this->redirect('/');
            $container = isset($_POST['container']) ? trim($_POST['container']) : null;
            $wish_shipping_line = isset($_POST['wish_shipping_line']) ? trim($_POST['wish_shipping_line']) : null;
            $loading_time = isset($_POST['loading_time']) ? trim($_POST['loading_time']) : null;
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : null;
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : null;
            $document_type = isset($_POST['document_type']) ? trim($_POST['document_type']) : null;
            if ($user_id < 1 || empty($first_name) || empty($last_name) || empty($telephone) || empty($phone) || empty($email) || empty($company) || empty($country) || empty($goods_name) || empty($shipping_type) || $quantity < 0 || empty($shipping_port) || empty($destination_port)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $data = array(
                'user_id' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'telephone' => $telephone,
                'phone' => $phone,
                'email' => $email,
                'company' => $company,
                'country' => $country,
                'goods_name' => $goods_name,
                'shipping_type' => $shipping_type,
                'quantity' => $quantity,
                'shipping_port' => $shipping_port,
                'destination_port' => $destination_port,
                'add_time' => time()
            );
            strlen($container) && $data['container'] = intval($container);
            strlen($wish_shipping_line) && $data['wish_shipping_line'] = $wish_shipping_line;
            strlen($loading_time) && $data['loading_time'] = strtotime($loading_time);
            strlen($weight) && $data['weight'] = intval($weight);
            strlen($remark) && $data['remark'] = $remark;
            strlen($document_type) && $data['document_type'] = $document_type;
            $shipping_agency = M('ShippingAgency');
            // Start transaction
            $shipping_agency->startTrans();
            if ($shipping_agency->add($data)) {
                // Add successful,commit transaction
                $shipping_agency->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Apply shipping agency successful'
                ));
            } else {
                // Add failed,rollback transaction
                $shipping_agency->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Apply shipping agency failed'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Shipping type list
     */
    public function shipping_type_list() {
        if ($this->isPost() || $this->isAjax()) {
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            if (!in_array($business_model, array(
                1,
                2
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $result = M('Shipping')->where(array(
                'business_model' => $business_model
            ))->order("id ASC")->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
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
     * Subscription
     */
    public function subscription() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if ($user_id < 1 || $c_cate_id < 1 || $p_cate_id < 1 || !in_array($type, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $subscription = M('Subscription');
            // subscribe
            if ($type) {
                if ($subscription->where(array(
                    'user_id' => $user_id,
                    'p_cate_id' => $p_cate_id,
                    'c_cate_id' => $c_cate_id
                ))->count()) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'You have already subscribe this child category'
                    ));
                }
                // Start transaction
                $subscription->startTrans();
                if ($subscription->add(array(
                    'p_cate_id' => $p_cate_id,
                    'c_cate_id' => $c_cate_id,
                    'user_id' => $user_id,
                    'subscribe_time' => time()
                ))) {
                    // Add successful,commit transaction
                    $subscription->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => 'Subscribe successful'
                    ));
                } else {
                    // Add failed,rollback transaction
                    $subscription->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Subscribe failed'
                    ));
                }
            } else {
                // Start transaction
                $subscription->startTrans();
                if ($subscription->where(array(
                    'user_id' => $user_id,
                    'p_cate_id' => $p_cate_id,
                    'c_cate_id' => $c_cate_id
                ))->delete()) {
                    // Delete successful,commit transaction
                    $subscription->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => 'Unsubscribe successful'
                    ));
                } else {
                    // Delete failed,rollback transaction
                    $subscription->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Unsubscribe failed'
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
            $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($id < 1 || $user_id < 1 || empty($name) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $addressModel = M('Address');
            if ($addressModel->where(array(
                'user_id' => $user_id,
                'name' => $name,
                'address' => $address,
                'id' => array(
                    'neq',
                    $id
                )
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'You have already added this address'
                ));
            }
            // Start transaction
            $addressModel->startTrans();
            if ($addressModel->where(array(
                'id' => $id
            ))->save(array(
                'user_id' => $user_id,
                'name' => $name,
                'phone' => strlen($phone) ? $phone : null,
                'telephone' => strlen($telephone) ? $telephone : null,
                'zip' => strlen($zip) ? $zip : null,
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
     * Update a member
     */
    public function update_member() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $account = isset($_POST['account']) ? trim($_POST['account']) : null;
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
            $avatar = isset($_POST['avatar']) ? trim($_POST['avatar']) : null;
            $sex = isset($_POST['sex']) ? intval($_POST['sex']) : null;
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $member = M('Member');
            $data = array();
            if ($account) {
                if ($member->where(array(
                    'account' => $account,
                    'id' => array(
                        'neq',
                        $id
                    )
                ))->count()) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'This account was used by anothor member.'
                    ));
                } else {
                    $data['account'] = $account;
                }
            }
            $phone && $data['phone'] = $phone;
            if ($avatar) {
                $old_avatar = $member->where(array(
                    'id' => $id
                ))->field(array(
                    'avatar'
                ))->find();
                if ($old_avatar['avatar']) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $old_avatar['avatar'])) {
                        if (!unlink($_SERVER['DOCUMENT_ROOT'] . $old_avatar['avatar'])) {
                            $this->ajaxReturn(array(
                                'status' => 0,
                                'result' => 'Update member avatar failed'
                            ));
                        }
                    }
                }
                $data['avatar'] = $this->saveImage('mem_', $avatar);
            }
            if (!is_null($sex)) {
                if (!in_array($sex, array(
                    0,
                    1
                ))) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => 'Invalid parameters'
                    ));
                } else {
                    $data['sex'] = $sex;
                }
            }
            if (empty($data)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Nothing to update'
                ));
            }
            // Start transaction
            $member->startTrans();
            if ($member->where(array(
                'id' => $id
            ))->save($data)) {
                // Update successful,get the new information
                $result = $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'avatar',
                    'sex',
                    'status',
                    'is_vip',
                    'email',
                    'register_time',
                    'last_time',
                    'upgrade_time'
                ))->where(array(
                    'id' => $id
                ))->limit(1)->select();
                foreach ($result as &$v) {
                    $v['register_time'] = date("Y-m-d H:i:s", $v['register_time']);
                    $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                    $v['upgrade_time'] = $v['upgrade_time'] ? date("Y-m-d H:i:s", $v['upgrade_time']) : $v['upgrade_time'];
                    $v['avatar'] = $v['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$v['avatar']}" : $V['avatar'];
                }
                // Commit transaction
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
                    'result' => 'Update member failed'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Update order status
     */
    public function update_order_status() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            if ($id < 0 || !in_array($status, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            $order = M('Order');
            // Start transaction
            $order->startTrans();
            if ($order->where(array(
                'id' => $id
            ))->save(array(
                'status' => $status
            ))) {
                // Update status successful,commit transaction
                $order->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 'Update order status successful'
                ));
            } else {
                // Update status failed,rollback transaction
                $order->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Update order status failed'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * User push list
     */
    public function user_push_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $is_vip = isset($_POST['is_vip']) ? intval($_POST['is_vip']) : $this->redirect('/');
            $is_system = isset($_POST['is_system']) ? intval($_POST['is_system']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 0 || !in_array($is_system, array(
                0,
                1
            )) || !in_array($is_vip, array(
                0,
                1
            )) || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => 'Invalid parameters'
                ));
            }
            if ($is_system) {
                $result = D('Notification')->getUserNotificationList($user_id, $is_vip, $page, $pageSize);
                if (!empty($result)) {
                    foreach ($result as &$v) {
                        $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    }
                }
            } else {
                $result = D('Goods')->getUserNotificationList($user_id, $page, $pageSize);
                if (!empty($result)) {
                    foreach ($result as &$v) {
                        $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                        $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                        $v['image'] = D('GoodsImage')->apiGetGoodsImageList($v['id']);
                    }
                }
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
        $mail->SMTPDebug = 1;
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

    /**
     * Generate the order number
     *
     * @param int $user_id
     *            Order user id
     * @return string
     */
    private function generateOrderNumber($user_id) {
        return strtoupper(md5(substr((rand(10000000, 99999999) . $user_id . time()), 0, 16)));
    }

    /**
     * Save image from base64 encode
     *
     * @param string $prefix
     *            File name prefix
     * @param string $imgCode
     *            Base64 encode image
     * @return string boolean
     */
    private function saveImage($prefix, $imgCode) {
        $fileName = $prefix . time() . rand(1000, 9999) . ".png";
        $img = base64_decode($imgCode);
        if (file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/uploads/{$fileName}", $img)) {
            return "/uploads/{$fileName}";
        } else {
            return false;
        }
    }

}