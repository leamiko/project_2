<?php

/**
 * Goods Category Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class CategoryAction extends AdminAction {

    /**
     * Add a child category
     */
    public function add_child() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('ChildCategory')->addChildCategory($name, $business_model, $parent_id, $image));
        } else {
            $this->assign('parent_category', M('ParentCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'is_delete' => 0,
                'business_model' => 1
            ))->field(array(
                'id, name'
            ))->select());
            $this->display();
        }
    }

    /**
     * Add a parent category
     */
    public function add_parent() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $parent_category = D('ParentCategory');
            $this->ajaxReturn($parent_category->addParentCategory($name, $business_model, $image));
        } else {
            $this->display();
        }
    }

    /**
     * Child category overview
     */
    public function child_category() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $child_category = D('ChildCategory');
            $total = $child_category->getChildCategoryCount();
            if ($total) {
                $rows = $child_category->getChildCategoryList($page, $pageSize, $order, $sort);
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
     * Delete child category(s)
     */
    public function delete_child() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $child_category = D('ChildCategory');
            $this->ajaxReturn($child_category->deleteChildCategory((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Delete parent category(s)
     */
    public function delete_parent() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $parent_category = D('ParentCategory');
            $this->ajaxReturn($parent_category->deleteParentCategory((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Delete parent/child category image
     */
    public function delete_image() {
        if ($this->isAjax()) {
            $filename = isset($_POST['filename']) ? $_SERVER['DOCUMENT_ROOT'] . $_POST['filename'] : $this->redirect('/');
            if (file_exists($filename)) {
                if (unlink($filename)) {
                    $this->ajaxReturn(array(
                        'status' => true
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => 'Delete image failed'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Image already deleted'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Get parent category by business model
     */
    public function getParentCategory() {
        if ($this->isAjax()) {
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $this->ajaxReturn(M('ParentCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'business_model' => $business_model,
                'is_delete' => 0
            ))->select());
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Parent category overview
     */
    public function parent_category() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $parent_category = D('ParentCategory');
            $total = $parent_category->getParentCategoryCount();
            if ($total) {
                $rows = $parent_category->getParentCategoryList($page, $pageSize, $order, $sort);
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
     * Update child category
     */
    public function update_child() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $parent_id = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('ChildCategory')->updateChildCategory($id, $name, $business_model, $parent_id, $image));
        } else {
            $child_category = M('ChildCategory')->where(array(
                'id' => $id
            ))->find();
            $child_category['src'] = "http://{$_SERVER['HTTP_HOST']}{$child_category['image']}";
            $this->assign('child_category', $child_category);
            $this->assign('parent_category', M('ParentCategory')->where(array(
                'is_delete' => 0,
                'business_model' => $child_category['business_model']
            ))->field(array(
                'id',
                'name'
            ))->select());
            $this->display();
        }
    }

    /**
     * Update parent category
     */
    public function update_parent() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('ParentCategory')->updateParentCategory($id, $name, $business_model, $image));
        } else {
            $parent_category = M('ParentCategory')->where(array(
                'id' => $id
            ))->find();
            $parent_category['src'] = "http://{$_SERVER['HTTP_HOST']}{$parent_category['image']}";
            $this->assign('parent_category', $parent_category);
            $this->display();
        }
    }

    /**
     * Upload parent/child category image
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('CATEGORY_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'File size is too large,please choose another one.'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array(strtoupper($fileParts['extension']), C('CATEGORY_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
                    $uploadFileName = $this->generateTargetFileName($fileParts['extension']);
                    $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
                    move_uploaded_file($tempFile, $targetFile);
                    $this->ajaxReturn(array(
                        'status' => true,
                        'src' => 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $uploadFileName,
                        'filename' => '/uploads/' . $uploadFileName
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => 'Unsupported image format'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Generate the upload file name
     *
     * @param string $extension
     *            file extension
     * @return string
     */
    private function generateTargetFileName($extension) {
        return 'cate_' . time() . rand(1000, 9999) . "." . $extension;
    }

}