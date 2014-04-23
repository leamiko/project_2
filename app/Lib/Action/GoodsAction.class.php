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
     * Add a goods
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $sale_amount = isset($_POST['sale_amount']) ? trim($_POST['sale_amount']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $size = isset($_POST['size']) ? trim($_POST['size']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : $this->redirect('/');
            $color = isset($_POST['color']) ? trim($_POST['color']) : $this->redirect('/');
            $area = isset($_POST['area']) ? intval($_POST['area']) : $this->redirect('/');
            $pay_method = isset($_POST['pay_method']) ? intval($_POST['pay_method']) : $this->redirect('/');
            $quality = isset($_POST['quality']) ? trim($_POST['quality']) : $this->redirect('/');
            $guarantee = isset($_POST['guarantee']) ? trim($_POST['guarantee']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->addGoods($name, $p_cate_id, $c_cate_id, $price, $stock, $business_model, $sale_amount, $unit, $size, $weight, $color, $area, $pay_method, $quality, $guarantee, $description, $image));
        } else {
            $this->assign('parent_category', M('ParentCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'is_delete' => 0,
                'business_model' => 1
            ))->select());
            $this->assign('area', M('Area')->field(array(
                'id',
                'name_en'
            ))->order('name_en ASC')->select());
            $this->display();
        }
    }

    /**
     * Delete goods
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', ($_POST['id'])) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->deleteGoods((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Delete goods picture
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
     * Get child category by parent category id
     */
    public function getChildCategory() {
        if ($this->isAjax()) {
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $this->ajaxReturn(M('ChildCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'parent_id' => $p_cate_id,
                'is_delete' => 0
            ))->select());
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
            $total = $goods->getGoodsCount($keyword);
            if ($total) {
                $rows = $goods->getGoodsList($page, $pageSize, $order, $sort, $keyword);
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
     * Set bidding goods
     */
    public function set_bidding() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->setBiddingGoods($id, $c_cate_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Update a goods
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : $this->redirect('/');
            $business_model = isset($_POST['business_model']) ? intval($_POST['business_model']) : $this->redirect('/');
            $sale_amount = isset($_POST['sale_amount']) ? trim($_POST['sale_amount']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $size = isset($_POST['size']) ? trim($_POST['size']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : $this->redirect('/');
            $color = isset($_POST['color']) ? trim($_POST['color']) : $this->redirect('/');
            $area = isset($_POST['area']) ? intval($_POST['area']) : $this->redirect('/');
            $pay_method = isset($_POST['pay_method']) ? intval($_POST['pay_method']) : $this->redirect('/');
            $quality = isset($_POST['quality']) ? trim($_POST['quality']) : $this->redirect('/');
            $guarantee = isset($_POST['guarantee']) ? trim($_POST['guarantee']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? $_POST['image'] : $this->redirect('/');
            if (is_string($image)) {
                $image = null;
            } else {
                $image = (array) $image;
            }
            $this->ajaxReturn(D('Goods')->updateGoods($id, $name, $p_cate_id, $c_cate_id, $price, $stock, $business_model, $sale_amount, $unit, $size, $weight, $color, $area, $pay_method, $quality, $guarantee, $description, $image));
        } else {
            $goods = M('Goods')->where(array(
                'id' => $id
            ))->find();
            $goods_image = M('GoodsImage')->field(array(
                'id',
                'image'
            ))->where(array(
                'goods_id' => $id,
                'is_delete' => 0
            ))->select();
            $image_count = "";
            foreach ($goods_image as $k => &$v) {
                $v['src'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                $image_count .= "'" . $v['image'] . "',";
            }
            $this->assign('goods', $goods);
            $this->assign('parent_category', M('ParentCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'is_delete' => 0,
                'business_model' => $goods['business_model']
            ))->select());
            $this->assign('child_category', M('ChildCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'is_delete' => 0,
                'parent_id' => $goods['p_cate_id']
            ))->select());
            $this->assign('area', M('Area')->field(array(
                'id',
                'name_en'
            ))->order('name_en ASC')->select());
            $this->assign('goods_image', $goods_image);
            $this->assign('image_count', rtrim($image_count, ","));
            $this->display();
        }
    }

    /**
     * Delete goods image when edit a goods
     */
    public function update_image() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $goods_image = M('GoodsImage');
            // Start transaction
            $goods_image->startTrans();
            if ($goods_image->where(array(
                'id' => $id
            ))->save(array(
                'is_delete' => 1
            ))) {
                // Delete successful,commit transaction
                $goods_image->commit();
                $this->ajaxReturn(array(
                    'status' => true,
                    'msg' => 'Delete goods image successful'
                ));
            } else {
                // Delete failed,rollback transaction
                $goods_image->rollback();
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Delete goods image failed.'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Upload goods picture
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('GOODS_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => 'Image file is too large, please choose another picture.'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array(strtolower($fileParts['extension']), C('GOODS_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
        return "goods_" . time() . rand(1000, 9999) . "." . $extension;
    }

}