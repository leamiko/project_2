<?php

/**
 * easy_advertisement table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdvertisementModel extends Model {

    /**
     * Add an advertisement
     *
     * @param string $title
     *            Advertisement title
     * @param int $language
     *            Advertisement language
     * @param int $business_model
     *            Business model(1:b2c,2:b2b)
     * @param string $content
     *            Advertisement content
     * @param array $image
     *            Advertisement images
     * @return array
     */
    public function addAdvertisement($title, $language, $business_model, $content, array $image) {
        $data = array(
            'title' => $title,
            'language' => $language,
            'business_model' => $business_model,
            'content' => $content,
            'add_time' => time()
        );
        // Start transaction
        $this->startTrans();
        if ($this->add($data)) {
            $advertisement_id = $this->getLastInsID();
            // Add successful,add advertisement image
            if (D('AdvertisementImage')->addAdvertisementImage($advertisement_id, $data['add_time'], $image)) {
                // Add image successful,commit transaction
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => 'Add advertisement successful'
                );
            } else {
                // Add images failed,rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Add advertisement_id image(s) failed'
                );
            }
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add advertisement failed'
            );
        }
    }

    /**
     * Delete advertisement
     *
     * @param array $id
     *            Advertisement id
     * @return array
     */
    public function deleteAdvertisement(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // Delete successful,delete the advertisement image
            if (D('AdvertisementImage')->deleteAdvertisementImage($id)) {
                // Delete advertisement image successful, commit transaction
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => 'Delete advertisement succeddful'
                );
            } else {
                // Delete advertisement image failed, rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Delete advertisement image failed'
                );
            }
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete advertisement failed'
            );
        }
    }

    /**
     * Get advertisement count
     *
     * @param string $keyword
     *            Keyword
     * @return int
     */
    public function getAdvertisementCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'title' => array(
                'like',
                "%{$keyword}%"
            )
        ))->count());
    }

    /**
     * Get advertisement list
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
    public function getAdvertisementList($page, $pageSize, $order, $sort, $keyword) {
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
     * Update an advertisement
     *
     * @param int $id
     *            Advertisement id
     * @param string $title
     *            Advertisement title
     * @param int $language
     *            Advertisement language
     * @param int $business_model
     *            Business model (1:b2c,2:b2b)
     * @param string $content
     *            Advertisement content
     * @param array|null $image
     *            Advertisement images
     * @return array
     */
    public function updateAdvertisement($id, $title, $language, $business_model, $content, $image) {
        $update_time = time();
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'title' => $title,
            'language' => $language,
            'business_model' => $business_model,
            'content' => $content,
            'update_time' => $update_time
        ))) {
            // Update advertisement successful,update advertisement image
            if (D('AdvertisementImage')->updateAdvertisementImage($id, $update_time)) {
                // Update advertisement image successful,add new advertisement image
                if ($image) {
                    if (D('AdvertisementImage')->addAdvertisementImage($id, $update_time, $image)) {
                        // Add new advertisement image successful,commit transaction
                        $this->commit();
                        return array(
                            'status' => true,
                            'msg' => 'Update advertisement successful'
                        );
                    } else {
                        // Add new advertisement image failed,rollback transaction
                        $this->rollback();
                        return array(
                            'status' => false,
                            'msg' => 'Add new advertisement image failed'
                        );
                    }
                } else {
                    // No new advertisement image,commit transaction
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => 'Update advertisement successful'
                    );
                }
            }
        } else {
            // Update advertisement failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Update advertisement failed'
            );
        }
    }

}