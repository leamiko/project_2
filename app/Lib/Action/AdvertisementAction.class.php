<?php

/**
 * Advertisement Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdvertisementAction extends AdminAction {

    /**
     * Add an advertisement
     */
    public function add() {
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $language = isset($_POST['language']) ? trim($_POST['language']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : $this->redirect('/');
            $this->ajaxReturn(D('Advertisement')->addAdvertisement($title, $language, $type, $content, $image));
        } else {
            $this->display();
        }
    }

    /**
     * Delete advertisement
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Advertisement')->deleteAdvertisement((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Delete advertisement picture
     */
    public function delete_image() {
        if ($this->isAjax()) {
            $filename = isset($_POST['filename']) ? trim($_POST['filename']) : $this->redirect('/');
            if (empty($filename)) {
                $this->ajaxReturn(array(
                    'status' => true
                ));
            } else {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filename)) {
                    if (unlink($_SERVER['DOCUMENT_ROOT'] . $filename)) {
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
                        'status' => true
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Advertisement overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $advertisement = D('Advertisement');
            $total = $advertisement->getAdvertisementCount($keyword);
            if ($total) {
                $rows = $advertisement->getAdvertisementList($page, $pageSize, $order, $sort, $keyword);
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
     * Update advertisement
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $language = isset($_POST['language']) ? trim($_POST['language']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $image = isset($_POST['image']) ? $_POST['image'] : $this->redirect('/');
            if (is_string($image)) {
                $image = null;
            } else {
                $image = (array) $image;
            }
            $this->ajaxReturn(D('Advertisement')->updateAdvertisement($id, $title, $language, $type, $content, $image));
        } else {
            $this->assign('advertisement', M('Advertisement')->where(array(
                'id' => $id
            ))->find());
            $advertisement_images = M('AdvertisementImage')->field(array(
                'id',
                'image'
            ))->where(array(
                'advertisement_id' => $id,
                'is_delete' => 0
            ))->select();
            $image_count = "";
            foreach ($advertisement_images as &$v) {
                $v['src'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                $image_count .= "'{$v['image']}',";
            }
            $this->assign('advertisement_images', $advertisement_images);
            $this->assign('image_count', rtrim($image_count, ","));
            $this->display();
        }
    }

    /**
     * Update advertisement status
     */
    public function update_advertisement_status() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            $this->ajaxReturn(D('Advertisement')->updateAdvertisementStatus($id, $status, $type));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Update advertisement image
     */
    public function update_image() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $advertisement_image = M('AdvertisementImage');
            // Start transaction
            $advertisement_image->startTrans();
            if ($advertisement_image->where(array(
                'id' => $id
            ))->save(array(
                'is_delete' => 1
            ))) {
                // Delete successful,commit transaction
                $advertisement_image->commit();
                $this->ajaxReturn(array(
                    'status' => true,
                    'msg' => 'Delete advertisement image successful'
                ));
            } else {
                // Delete failed,rollback transaction
                $advertisement_image->rollback();
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Delete advertisement image failed.'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Upload image in advertisement content
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/ad";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['upload']['size'] > C('ADVERTISEMENT_MAX_UPLOAD_FILE_SIZE')) {
                echo "<script type='text/javascript'>
                        window.parent.CKEDITOR.tools.callFunction(0, '', 'Image file is too large, please choose another picture.');
                        </script>";
            } else {
                $fileParts = pathinfo($_FILES['upload']['name']);
                $tempFile = $_FILES['upload']['tmp_name'];
                if (in_array(strtolower($fileParts['extension']), C('ADVERTISEMENT_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
                    $uploadFileName = $this->generateTargetFileName($fileParts['extension']);
                    $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
                    move_uploaded_file($tempFile, $targetFile);
                    $fileName = '/uploads/ad/' . $uploadFileName;
                    $funcNum = $_GET['CKEditorFuncNum'];
                    echo "<script type='text/javascript'>
                    window.parent.CKEDITOR.tools.callFunction($funcNum, '$fileName');
                    </script>";
                } else {
                    echo "<script type='text/javascript'>
                            window.parent.CKEDITOR.tools.callFunction(0, '', 'Unsupported image format.');
                            </script>";
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Upload advertisement image
     */
    public function upload_advertisement_image() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('ADVERTISEMENT_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Image file is too large, please choose another picture.'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array(strtolower($fileParts['extension']), C('ADVERTISEMENT_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
                        'msg' => 'Unsupported image format.'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Generate upload file name
     *
     * @param string $extension
     *            extension
     * @return string
     */
    private function generateTargetFileName($extension) {
        return "ad_" . time() . rand(1000, 9999) . "." . $extension;
    }

}