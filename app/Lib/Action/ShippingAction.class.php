<?php

/**
 * Shipping Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ShippingAction extends AdminAction {

    /**
     * Add a shipping type
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $type = isset($_POST['shipping_type']) ? intval($_POST['shipping_type']) : $this->redirect('/');
            $this->ajaxReturn(D('Shipping')->addShipping($name, $business_model, $type));
        } else {
            $this->display();
        }
    }

    /**
     * Delete shipping type
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', ($_POST['id'])) : $this->redirect('/');
            $this->ajaxReturn(D('Shipping')->deleteShipping((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Shipping type overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $shipping = D('Shipping');
            $total = $shipping->getShippingCount($keyword);
            if ($total) {
                $rows = $shipping->getShippingList($page, $pageSize, $order, $sort, $keyword);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->display();
        }
    }

    /**
     * Update a shipping type
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $type = isset($_POST['shipping_type']) ? intval($_POST['shipping_type']) : $this->redirect('/');
            $this->ajaxReturn(D('Shipping')->updateShipping($id, $name, $business_model, $type));
        } else {
            $this->assign('shipping', M('Shipping')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}