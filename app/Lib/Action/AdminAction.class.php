<?php

/**
 * Backend Base Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminAction extends Action {

    /**
     * administrator information
     *
     * @var unknown
     */
    protected $admin_info = null;

    public function _initialize() {
        $this->admin_info = $this->isLogin() ? $this->isLogin() : $this->redirect(U('/login'));
    }

    /**
     * get main menu
     *
     * @return array
     */
    protected function getMenu() {
        $menu = C('menu');
        foreach ($menu as $key => $value) {
            unset($children_1);
            foreach ($value['children'] as $key1 => $value1) {
                $children_1[] = array(
                    'url' => $value1['url'],
                    'text' => $value1['text']
                );
            }
            $tmpArr[] = array(
                'text' => $value['text'],
                'isexpand' => true,
                'children' => $children_1
            );
        }
        return $tmpArr;
    }

    /**
     * check login
     *
     * @return Ambigous <mixed, NULL>
     */
    private function isLogin() {
        return session('admin_info');
    }

}