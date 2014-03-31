<?php

/**
 * Publish Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class PublishAction extends AdminAction {

    /**
     * Publish overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $publish = D('Publish');
            $total = $publish->getPublishCount($keyword);
            if ($total) {
                $rows = $publish->getPublishList($page, $pageSize, $order, $sort, $keyword);
                foreach ($rows as &$v) {
                    $v['publish_time'] = date("Y-m-d H:i:s", $v['publish_time']);
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

}