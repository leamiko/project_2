<?php

/**
 * easy_goods table's Model
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsModel extends Model {

    /**
     * Add goods
     *
     * @param string $name
     *            goods name
     * @param int $p_cate_id
     *            parent category id
     * @param int $c_cate_id
     *            child category id
     * @param float $price
     *            goods price
     * @param int $stock
     *            stock
     * @param int $business_model
     *            business model(1:b2c,2:b2b)
     * @param string $sale_amount
     *            amount for sale(only when business model is 2)
     * @param string $unit
     *            unit
     * @param string $size
     *            size
     * @param string $weight
     *            Weight
     * @param string $color
     *            color
     * @param int $area
     *            area
     * @param int $pay_method
     *            pay method(1:Paypal,2:Alipay)
     * @param string $quality
     *            Quality
     * @param string $guarantee
     *            guarantee
     * @param string $description
     *            description
     * @param array $image
     *            goods images
     * @return array
     */
    public function addGoods($name, $p_cate_id, $c_cate_id, $price, $stock, $business_model, $sale_amount, $unit, $size, $weight, $color, $area, $pay_method, $quality, $guarantee, $description, array $image) {
        $data = array(
            'name' => $name,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'item_number' => strtoupper(substr(md5(rand(100000, 999999)), 0, 16)),
            'price' => $price,
            'business_model' => $business_model,
            'unit' => $unit,
            'area' => $area,
            'pay_method' => $pay_method,
            'stock' => $stock,
            'is_delete' => 0,
            'add_time' => time()
        );
        strlen($sale_amount) && $data['sale_amount'] = intval($sale_amount);
        strlen($size) && $data['size'] = $size;
        strlen($weight) && $data['weight'] = intval($weight);
        strlen($color) && $data['color'] = $color;
        strlen($quality) && $data['quality'] = $quality;
        strlen($guarantee) && $data['guarantee'] = $guarantee;
        strlen($description) && $data['description'] = $description;
        // Start transaction
        $this->startTrans();
        if ($this->add($data)) {
            $goods_id = $this->getLastInsID();
            // Add successful,add goods image
            if (D('GoodsImage')->addGoodsImage($goods_id, $p_cate_id, $c_cate_id, $data['add_time'], $image)) {
                // Add image successful,commit transaction
                $this->commit();
                // Push user who subscribed
                $this->pushGoods($p_cate_id, $c_cate_id, $goods_id);
                return array(
                    'status' => true,
                    'msg' => 'Add goods successful'
                );
            } else {
                // Add images failed,rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Add goods picture(s) failed'
                );
            }
        } else {
            // Add failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Add goods failed'
            );
        }
    }

    /**
     * Goods list api
     *
     * @param int $p_cate_id
     *            Parent category id
     * @param int $c_cate_id
     *            Chile category id
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     */
    public function apiGetGoodsList($p_cate_id, $c_cate_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS g")->join(array(
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON p.id = g.p_cate_id",
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON c.id = g.c_cate_id"
        ))->field(array(
            'g.id',
            'g.c_cate_id',
            'g.p_cate_id',
            'g.name',
            'g.business_model',
            'g.item_number',
            'g.price',
            'g.business_model',
            'g.sale_amount',
            'g.unit',
            'g.size',
            'g.weight',
            'g.quality',
            'g.color',
            'g.area',
            'g.pay_method',
            'g.guarantee',
            'g.stock',
            'g.description',
            'g.add_time',
            'g.update_time',
            'c.name' => 'child_category',
            'p.name' => 'parent_category'
        ))->where(array(
            'g.p_cate_id' => $p_cate_id,
            'g.c_cate_id' => $c_cate_id,
            'g.is_delete' => 0
        ))->order("id ASC")->limit($offset, $pageSize)->select();
    }

    /**
     * Delele goods by goods id
     *
     * @param array $id
     *            Goods id
     * @return array
     */
    public function deleteGoods(array $id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,delete the goods image
            $goods_image = M('GoodsImage');
            // Start transaction
            $goods_image->startTrans();
            if ($goods_image->where(array(
                'goods_id' => array(
                    'in',
                    $id
                )
            ))->save(array(
                'is_delete' => 1
            ))) {
                // Delete successful,commit transaction
                $goods_image->commit();
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => 'Delete goods successful'
                );
            } else {
                // Delete failed,rollback transaction
                $goods_image->rollback();
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Delete goods failed'
                );
            }
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Delete goods failed'
            );
        }
    }

    /**
     * Delete goods by child category id
     *
     * @param array $child_id
     *            child category id
     * @return boolean
     */
    public function deleteGoodsByChildCateId(array $child_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'c_cate_id' => array(
                'in',
                $child_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,delete the goods image
            $goods_image = M('GoodsImage');
            // Start transaction
            $goods_image->startTrans();
            if ($goods_image->where(array(
                'c_cate_id' => array(
                    'in',
                    $child_id
                )
            ))->save(array(
                'is_delete' => 1
            ))) {
                // Delete successful,commit transaction
                $goods_image->commit();
                $this->commit();
                return true;
            } else {
                // Delete failed,rollback transaction
                $goods_image->rollback();
                $this->rollback();
                return false;
            }
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

    /**
     * Delete goods by parent category id
     *
     * @param array $parent_id
     *            parent category id
     * @return boolean
     */
    public function deleteGoodsByParentCateId(array $parent_id) {
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'p_cate_id' => array(
                'in',
                $parent_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            // Delete successful,delete the goods image
            $goods_image = M('GoodsImage');
            // Start transaction
            $goods_image->startTrans();
            if ($goods_image->where(array(
                'p_cate_id' => array(
                    'in',
                    $parent_id
                )
            ))->save(array(
                'is_delete' => 1
            ))) {
                // Delete successful,commit transaction
                $goods_image->commit();
                $this->commit();
                return true;
            } else {
                // Delete failed,rollback transaction
                $goods_image->rollback();
                $this->rollback();
                return false;
            }
        } else {
            // Delete failed,rollback transaction
            $this->rollback();
            return false;
        }
    }

    /**
     * Get goods detail
     *
     * @param int $id
     *            Goods id
     */
    public function getGoodsDetail($id) {
        return $this->table($this->getTableName() . " AS g")->join(array(
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON g.c_cate_id = c. id",
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON g.p_cate_id = p. id"
        ))->field(array(
            'g.id',
            'g.c_cate_id',
            'g.p_cate_id',
            'g.name',
            'g.business_model',
            'g.item_number',
            'g.price',
            'g.sale_amount',
            'g.unit',
            'g.is_bidding',
            'g.size',
            'g.weight',
            'g.quality',
            'g.color',
            'g.area',
            'g.pay_method',
            'g.guarantee',
            'g.stock',
            'g.description',
            'g.add_time',
            'g.update_time',
            'c.name' => 'child_category',
            'p.name' => 'parent_category'
        ))->where(array(
            'g.id' => $id
        ))->limit(1)->select();
    }

    /**
     * Get goods count
     *
     * @param string $keyword
     *            keyword
     * @return int
     */
    public function getGoodsCount($keyword) {
        $condition = array(
            'is_delete' => 0
        );
        empty($keyword) || $condition['name'] = array(
            'like',
            "%{$keyword}%"
        );
        return (int) ($this->where($condition)->count());
    }

    /**
     * Get goods list
     *
     * @param int $page
     *            current page
     * @param int $pageSize
     *            page size
     * @param string $order
     *            order field
     * @param string $sort
     *            sort
     * @param string $keyword
     *            keyword
     * @return array
     */
    public function getGoodsList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        $this->table($this->getTableName() . " AS g")->join(array(
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON g.c_cate_id = c. id",
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON g.p_cate_id = p. id",
            "INNER JOIN " . M('Area')->getTableName() . " AS a ON g.area = a.id"
        ))->field(array(
            'g.id',
            'g.c_cate_id',
            'g.p_cate_id',
            'g.name',
            'g.business_model',
            'g.item_number',
            'g.price',
            'g.sale_amount',
            'g.unit',
            'g.is_bidding',
            'g.size',
            'g.weight',
            'g.quality',
            'g.color',
            'g.pay_method',
            'g.guarantee',
            'g.stock',
            'g.description',
            'g.add_time',
            'g.update_time',
            'c.name' => 'child_category',
            'p.name' => 'parent_category',
            'a.name_en' => 'area'
        ))->order($order . " " . $sort)->limit($offset, $pageSize);
        $condition = array(
            'g.is_delete' => 0
        );
        empty($keyword) || $condition['g.name'] = array(
            'like',
            "%{$keyword}%"
        );
        return $this->where($condition)->select();
    }

    /**
     * Get user notification
     *
     * @param int $user_id
     *            User id
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     * @return array
     */
    public function getUserNotificationList($user_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    id, c_cate_id, p_cate_id, name, item_number, price, stock,
                    business_model, unit, is_bidding, pay_method, sale_amount,
                    size, weight, color, area,quality, guarantee, description,
                    is_delete, add_time, update_time
                FROM
                    easy_goods
                WHERE
                    is_delete = 0 AND
                    c_cate_id IN (
                    SELECT
                        c_cate_id
                    FROM
                        easy_subscription
                    WHERE
                        user_id = {$user_id})
                ORDER BY
                    id DESC
                LIMIT {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * Push new goods to user
     *
     * @param int $p_cate_id
     *            Parent category
     * @param int $c_cate_id
     *            Child category
     * @param int $goods_id
     *            Goods id
     */
    public function pushGoods($p_cate_id, $c_cate_id, $goods_id) {
        $result = M('Subscription')->field(array(
            'user_id'
        ))->where(array(
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id
        ))->select();
        if (!empty($result)) {
            $i = ceil(count($result) / 1000);
            for ($j = 1; $j <= $i; $j++) {
                $alias = "";
                $length = min(array(
                    ($j * 1000),
                    count($result)
                ));
                for ($k = ($j - 1) * 1000; $k < $length; $k++) {
                    if ($k != ($length - 1)) {
                        $alias .= $result[$k]['user_id'] . ",";
                    } else {
                        $alias .= $result[$k]['user_id'];
                    }
                }
                push("Hey!Here comes some new goods,let's shopping now.", 3, $alias, 0, $goods_id);
            }
        }
    }

    /**
     * Search goods
     *
     * @param int $business_model
     *            Business model(1:b2c,2:b2b)
     * @param string $keyword
     *            Keyword
     * @param int $zip_code_id
     *            area id
     * @param int $page
     *            Current page
     * @param int $pageSize
     *            Page size
     */
    public function searchGoods($business_model, $keyword, $zip_code_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $condition = array(
            'g.business_model' => $business_model,
            'g.is_delete' => 0,
            '_complex' => array(
                array(
                    'g.name' => array('like', "%{$keyword}%"),
                    'p.name' => array('like', "%{$keyword}%"),
                    'c.name' => array('like', "%{$keyword}%"),
                    '_logic' => 'OR'
                )
            )
        );
        $zip_code_id && $condition['a.id'] = $zip_code_id;
        return $this->table($this->getTableName() . " AS g")->join(array(
            "INNER JOIN " . M('ParentCategory')->getTableName() . " AS p ON p.id = g.p_cate_id",
            "INNER JOIN " . M('ChildCategory')->getTableName() . " AS c ON c.id = g.c_cate_id",
            "INNER JOIN " . M('Area')->getTableName() . " AS a ON a.id = g.area"
        ))->field(array(
            'g.id',
            'g.c_cate_id',
            'g.p_cate_id',
            'g.name',
            'g.business_model',
            'g.item_number',
            'g.price',
            'g.business_model',
            'g.sale_amount',
            'g.unit',
            'g.size',
            'g.weight',
            'g.quality',
            'g.color',
            'g.pay_method',
            'g.guarantee',
            'g.stock',
            'g.description',
            'g.add_time',
            'g.update_time',
            'c.name' => 'child_category',
            'p.name' => 'parent_category',
            'a.name_en' => 'area'
        ))->where($condition)->order("id ASC")->limit($offset, $pageSize)->select();
    }

    /**
     * Set bidding goods
     *
     * @param int $id
     *            Bidding goods id
     * @param int $c_cate_id
     *            Bidding goods child category id
     * @return array
     */
    public function setBiddingGoods($id, $c_cate_id) {
        // Check is exists bidding goods in this child category,start transaction
        $this->startTrans();
        if ($this->where(array(
            'c_cate_id' => $c_cate_id
        ))->count()) {
            // Set all the goods in this child category to no bidding
            $this->where(array(
                'c_cate_id' => $c_cate_id
            ))->save(array(
                'is_bidding' => 0
            ));
        }
        // Set the special goods to bidding
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'is_bidding' => 1
        ))) {
            // Set bidding goods successful, commit transaction
            $this->commit();
            return array(
                'status' => true,
                'msg' => 'Set bidding goods successful.'
            );
        } else {
            // Set bidding goods failed, rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Set bidding goods failed.'
            );
        }
    }

    /**
     * Update a goods
     *
     * @param int $id
     *            Goods id
     * @param string $name
     *            Goods name
     * @param int $p_cate_id
     *            Parent category id
     * @param int $c_cate_id
     *            Child category id
     * @param float $price
     *            Price
     * @param int $stock
     *            Stock
     * @param int $business_model
     *            Business model(1:B2C,2:B2B)
     * @param int $sale_amount
     *            Amount for sale
     * @param string $unit
     *            Unit
     * @param string $size
     *            Size
     * @param string $weight
     *            Weight
     * @param string $color
     *            Color
     * @param int $area
     *            Area
     * @param int $pay_method
     *            Pay method(1:Paypal,2:Alipay)
     * @param string $quality
     *            Quality
     * @param string $guarantee
     *            Guarantee
     * @param string $description
     *            Description
     * @param array|null $image
     *            Goods images
     * @return array
     */
    public function updateGoods($id, $name, $p_cate_id, $c_cate_id, $price, $stock, $business_model, $sale_amount, $unit, $size, $weight, $color, $area, $pay_method, $quality, $guarantee, $description, $image) {
        $update_time = time();
        // Start transaction
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'price' => $price,
            'business_model' => $business_model,
            'sale_amount' => strlen($sale_amount) ? intval($sale_amount) : null,
            'unit' => $unit,
            'size' => strlen($size) ? $size : null,
            'weight' => strlen($weight) ? intval($weight) : null,
            'color' => strlen($color) ? $color : null,
            'area' => $area,
            'pay_method' => $pay_method,
            'quality' => strlen($quality) ? $quality : null,
            'guarantee' => strlen($guarantee) ? $guarantee : null,
            'stock' => $stock,
            'description' => strlen($description) ? $description : null,
            'is_delete' => 0,
            'update_time' => $update_time
        ))) {
            // Update success,update goods image
            if (D('GoodsImage')->updateGoodsImage($id, $p_cate_id, $c_cate_id, $update_time)) {
                // Update goods images successful,add new goods image
                if ($image) {
                    if (D('GoodsImage')->addGoodsImage($id, $p_cate_id, $c_cate_id, $update_time, $image)) {
                        // Add new goods image successful,commit transaction
                        $this->commit();
                        return array(
                            'status' => true,
                            'msg' => 'Update goods successful'
                        );
                    } else {
                        // Add new goods image failed,rollback transaction
                        $this->rollback();
                        return array(
                            'status' => false,
                            'msg' => 'Add new goods image failed'
                        );
                    }
                } else {
                    // No new goods image,commit transaction
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => 'Update goods successful'
                    );
                }
            } else {
                // Update goods image failed, rollback transaction
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => 'Update goods image failed'
                );
            }
        } else {
            // Update failed,rollback transaction
            $this->rollback();
            return array(
                'status' => false,
                'msg' => 'Update goods failed'
            );
        }
    }

}