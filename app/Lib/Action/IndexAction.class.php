<?php

/**
 * Index Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class IndexAction extends AdminAction {

    /**
     * index
     */
    public function index() {
        $this->assign('menu', json_encode($this->getMenu()));
        $this->assign('administrator', $this->admin_info['username']);
        $this->assign('adminId', $this->admin_info['id']);
        $this->display();
    }

    /**
     * welcome
     */
    public function welcome() {
        $this->display();
    }

}