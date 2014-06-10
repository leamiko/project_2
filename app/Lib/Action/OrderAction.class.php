<?php

/**
 * Order Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderAction extends AdminAction {

    /**
     * Order overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount($keyword);
            if ($total) {
                $rows = $orderModel->getOrderList($page, $pageSize, $order, $sort, $keyword);
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Total' => $total,
                'Rows' => $rows
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->display();
        }
    }

}