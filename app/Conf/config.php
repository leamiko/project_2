<?php

/**
 * application configuration
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
$app_config = array(
    // Menu
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
        'Goods' => array(
            'text' => 'Goods',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Goods Overview',
                    'url' => '/goods/index'
                ),
                'add' => array(
                    'text' => 'Add A Goods',
                    'url' => '/goods/add'
                )
            )
        ),
        'Category' => array(
            'text' => 'Goods Category',
            'default' => 'parent',
            'children' => array(
                'parent' => array(
                    'text' => 'Parent Category',
                    'url' => '/category/parent_category'
                ),
                'child' => array(
                    'text' => 'Child Category',
                    'url' => '/category/child_category'
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
    'CATEGORY_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'CATEGORY_MAX_UPLOAD_FILE_SIZE' => 2097152,
    // Mail
    'EMAIL' => array(
        'SMTP_HOST' => 'smtp.qq.com',
        'SMTP_PORT' => '465',
        'SENDER_MAIL' => '635420322@qq.com',
        'SENDER_PWD' => 'lzjjie635420322',
        'SENDER_NAME' => 'Lzjjie',
        'REPLY_EMAIL' => '',
        'REPLY_NAME' => ''
    ),
    'SHOW_PAGE_TRACE' => true
);
return array_merge(require 'config.inc.php', $app_config);