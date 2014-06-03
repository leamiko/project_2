<?php

/**
 * Common function library
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */

/**
 * Push
 *
 * @param string $message
 *            Message content
 * @param int $receiver_type
 *            Receiver type(1:IMEI(Must special appKeys first),2:tag,3:alias,4:broadcast
 * @param string $receiver_value
 *            Relative with Reciever type
 * @param int $is_system
 *            (1:yes,0:no)
 * @param int|null $goods_id
 *            Goods id
 * @return boolean
 */
function push($message, $receiver_type = 4, $receiver_value = '', $is_system = 1, $goods_id = null) {
    vendor('jpush.JPushClient');
    $jPush_config = C('JPush');
    $url = 'http://api.jpush.cn:8800/v2/push';
    $param = '';
    $param .= '&sendno=1';
    $param .= '&app_key=' . $jPush_config['app_key'];
    $param .= '&receiver_type=' . $receiver_type;
    $param .= '&receiver_value=' . $receiver_value;
    $masterSecret = $jPush_config['master_secret'];
    $verification_code = md5(1 . $receiver_type . $receiver_value . $masterSecret);
    $param .= '&verification_code=' . $verification_code;
    $param .= '&msg_type=1';
    $message = json_encode(array(
        'n_content' => $message,
        'n_extras' => array(
            'ios' => array(
                'content-available' => 1
            ),
            'is_system' => $is_system,
            'goods_id' => $goods_id
        )
    ));
    $param .= '&msg_content=' . $message;
    $param .= '&platform=' . $jPush_config['platform'];
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = json_decode(curl_exec($ch));
    curl_close($ch);
    return $data->errcode ? false : true;
}