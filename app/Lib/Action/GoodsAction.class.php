<?php

/**
 * Goods Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsAction extends AdminAction {

    /**
     * Goods overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $goods = D('Goods');
            $total = $goods->getGoodsCount("app");
            if ($total) {
                $rows = $goods->getGoodsList($page, $pageSize, $order, $sort, "app");
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

}