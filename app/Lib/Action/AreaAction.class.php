<?php

/**
 * Area Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AreaAction extends AdminAction {

    /**
     * Add an area
     */
    public function add() {
        if ($this->isAjax()) {
            $zip_code = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : $this->redirect('/');
            $name_zh = isset($_POST['name_zh']) ? trim($_POST['name_zh']) : $this->redirect('/');
            $name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : $this->redirect('/');
            $name_ar = isset($_POST['name_ar']) ? trim($_POST['name_ar']) : $this->redirect('/');
            $this->ajaxReturn(D('Area')->addArea($zip_code, $name_zh, $name_en, $name_ar));
        } else {
            $this->display();
        }
    }

    /**
     * Delete area
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', ($_POST['id'])) : $this->redirect('/');
            $this->ajaxReturn(D('Area')->deleteArea((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Area management
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $area = D('Area');
            $total = $area->getAreaCount();
            if ($total) {
                $rows = $area->getAreaList($page, $pageSize, $order, $sort);
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
            $this->display();
        }
    }

    /**
     * Update an area
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $zip_code = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : $this->redirect('/');
            $name_zh = isset($_POST['name_zh']) ? trim($_POST['name_zh']) : $this->redirect('/');
            $name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : $this->redirect('/');
            $name_ar = isset($_POST['name_ar']) ? trim($_POST['name_ar']) : $this->redirect('/');
            $this->ajaxReturn(D('Area')->updateArea($id, $zip_code, $name_zh, $name_en, $name_ar));
        } else {
            $this->assign('area', M('Area')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}