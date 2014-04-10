<?php

/**
 * Common function library
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */

/**
 * Push message to app
 *
 * @param string $message
 *            Message content
 * @param number $receiver_type
 *            Receiver type
 * @param string $receiver_value
 *            Receiver value
 * @param number $is_system
 *            Is system message(1:Yes,0:No)
 * @param string $goods_id
 *            Goods id
 * @return boolean
 */
function push($message, $receiver_type = 4, $receiver_value = '', $is_system = 1, $goods_id = null) {
    vendor('jpush.JPushClient');
    $jPush_config = C('JPush');
    $client = new JPushClient($jPush_config['app_key'], $jPush_config['master_secret']);
    $params = array(
        "receiver_type" => $receiver_type,
        "receiver_value" => $receiver_value,
        "sendno" => 1,
        "send_description" => "",
        "override_msg_id" => ""
    );
    $extras = array(
        'is_system' => $is_system
    );
    $goods_id && $extras['goods_id'] = $goods_id;
    $result = $client->sendNotification($message, $params, $extras);
    return $result->getCode() ? false : true;
}