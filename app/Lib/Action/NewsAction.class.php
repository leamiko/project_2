<?php

/**
 * News Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NewsAction extends AdminAction {

    /**
     * Add a news
     */
    public function add() {
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $author = isset($_POST['author']) ? trim($_POST['author']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $article = D('Article');
            $this->ajaxReturn($article->addArticle($title, $author, $content, 2));
        } else {
            $this->display();
        }
    }

    /**
     * Delete news
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $article = D('Article');
            $this->ajaxReturn($article->deleteArticle((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * News overview
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $article = D('Article');
            $total = $article->getArticleCount($keyword, 2);
            if ($total) {
                $rows = $article->getArticleList($page, $pageSize, $order, $sort, $keyword, 2);
                foreach ($rows as &$v) {
                    $v['content'] = mb_substr(strip_tags($v['content']), 0, 36, "utf-8");
                    $v['url'] = "http://{$_SERVER['HTTP_HOST']}/news/detail/type/{$v['type']}/id/{$v['id']}.html";
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
     * Update news
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $article = D('Article');
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $author = isset($_POST['author']) ? trim($_POST['author']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn($article->updateArticle($id, $title, $author, $content));
        } else {
            $this->assign('article', $article->where("id = {$id}")->find());
            $this->display();
        }
    }

    /**
     * Upload image in news content
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/news";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['upload']['size'] > C('NEWS_MAX_UPLOAD_FILE_SIZE')) {
                echo "<script type='text/javascript'>
                        window.parent.CKEDITOR.tools.callFunction(0, '', 'Image file is too large, please choose another picture.');
                        </script>";
            } else {
                $fileParts = pathinfo($_FILES['upload']['name']);
                $tempFile = $_FILES['upload']['tmp_name'];
                if (in_array($fileParts['extension'], C('NEWS_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
                    $uploadFileName = $this->generateTargetFileName($fileParts['extension']);
                    $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
                    move_uploaded_file($tempFile, $targetFile);
                    $fileName = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/news/' . $uploadFileName;
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
     * Generate upload file name
     *
     * @param string $extension
     *            extension
     * @return string
     */
    private function generateTargetFileName($extension) {
        return "news_" . time() . rand(1000, 9999) . "." . $extension;
    }

}