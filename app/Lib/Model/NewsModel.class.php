<?php

/**
 * easy_news table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NewsModel extends Model {

    /**
     * Add a news
     *
     * @param string $title
     *            News title
     * @param int $language
     *            News language
     * @param string $content
     *            News content
     * @param array $image
     *            News images
     * @return array
     */
    public function addNews($title, $language, $content, array $image) {
        $data = array(
            'title' => $title,
            'language' => $language,
            'content' => $content,
            'add_time' => time()
        );
        // Start transaction
        $this->startTrans();
        if ($this->add($data)) {
            $news_id = $this->getLastInsID();
            // Add successful,add news image
            if (D('NewsImage')->addNewsImage($news_id, $data['add_time'], $image)) {
                // Add image successful,commit transaction
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => 'Add news successful'
                );
            } else {
                // Add images failed,rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Add news image(s) failed'
                );
            }
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add news failed'
            );
        }
    }

    /**
     * Delete news
     *
     * @param array $id
     *            News id
     * @return array
     */
    public function deleteNews(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // Delete successful,delete the news image
            if (D('NewsImage')->deleteNewsImage($id)) {
                // Delete news image successful, commit transaction
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => 'Delete news succeddful'
                );
            } else {
                // Delete news image failed, rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Delete news image failed'
                );
            }
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete news failed'
            );
        }
    }

    /**
     * Get news count
     *
     * @param string $keyword
     *            Keyword
     * @return int
     */
    public function getNewsCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'title' => array(
                'like',
                "%{$keyword}%"
            )
        ))->count());
    }

    /**
     * Get news list
     *
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     * @param string $order
     *            Order field
     * @param string $sort
     *            Sort
     * @param string $keyword
     *            Keyword
     */
    public function getNewsList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'title' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * Update a news
     *
     * @param int $id
     *            News id
     * @param string $title
     *            News title
     * @param int $language
     *            News language
     * @param string $content
     *            News content
     * @param array|null $image
     *            News images
     * @return array
     */
    public function updateNews($id, $title, $language, $content, $image) {
        $update_time = time();
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'title' => $title,
            'language' => $language,
            'content' => $content,
            'update_time' => $update_time
        ))) {
            // Update news successful,update news image
            if (D('NewsImage')->updateNewsImage($id, $update_time)) {
                // Update news image successful,add new news image
                if ($image) {
                    if (D('NewsImage')->addNewsImage($id, $update_time, $image)) {
                        // Add new news image successful,commit transaction
                        $this->commit();
                        return array(
                            'status' => true,
                            'msg' => 'Update news successful'
                        );
                    } else {
                        // Add new news image failed,rollback transaction
                        $this->rollback();
                        return array(
                            'status' => false,
                            'msg' => 'Add new news image failed'
                        );
                    }
                } else {
                    // No new news image,commit transaction
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => 'Update news successful'
                    );
                }
            }
        } else {
            // Update news failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Update news failed'
            );
        }
    }

}