<?php

/**
 * application configuration
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
$app_config = array(
    'menu' => array(
        'Administrator' => array(
            'text' => 'Administrator',
            'default' => 'management',
            'children' => array(
                'management' => array(
                    'text' => 'Management',
                    'url' => '/administrator/management'
                )
            )
        ),
        'Member' => array(
            'text' => 'Member',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Member Overview',
                    'url' => '/member/index'
                )
            )
        ),
        'API' => array(
            'text' => 'API',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'App API List',
                    'url' => '/open/index'
                )
            )
        )
    ),
    //邮件配置
    'THINK_EMAIL' => array(
        'SMTP_HOST'   => 'smtp.qq.com', //SMTP服务器
        'SMTP_PORT'   => '25', //SMTP服务器端口
        'SMTP_USER'   => '635420322@qq.com', //SMTP服务器用户名
        'SMTP_PASS'   => 'lzjjie635420322', //SMTP服务器密码
        'FROM_EMAIL'  => '635420322@qq.com', //发件人EMAIL
        'FROM_NAME'   => 'Lzjjie', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
    )
);
return array_merge(require 'config.inc.php', $app_config);