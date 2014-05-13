<?php

/**
 * Application configuration
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
                    'text' => 'Add a Goods',
                    'url' => '/goods/add'
                )
            )
        ),
        'Area' => array(
            'text' => 'Area',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Area Management',
                    'url' => '/area/index'
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
        'Publish' => array(
            'text' => 'Publish',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Publish Overview',
                    'url' => '/publish/index'
                )
            )
        ),
        'Shipping' => array(
            'text' => 'Shipping',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Shipping Type Overview',
                    'url' => '/shipping/index'
                )
            )
        ),
        'News' => array(
            'text' => 'News',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'News Overview',
                    'url' => '/news/index'
                ),
                'add' => array(
                    'text' => 'Add a News',
                    'url' => '/news/add'
                )
            )
        ),
        'Advertisement' => array(
            'text' => 'Advertisement',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'Ad overview',
                    'url' => '/advertisement/index'
                ),
                'add' => array(
                    'text' => 'Add an Ad',
                    'url' => '/advertisement/add'
                )
            )
        ),
        'Setting' => array(
            'text' => 'Setting',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'System Notifications',
                    'url' => '/setting/index'
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
    'ADVERTISEMENT_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'CATEGORY_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'GOODS_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'NEWS_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'ADVERTISEMENT_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'GOODS_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'CATEGORY_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'NEWS_MAX_UPLOAD_FILE_SIZE' => 2097152,
    // Mail
    'EMAIL' => array(
        'SMTP_HOST' => 'smtp.gmail.com',
        'SMTP_PORT' => '465',
        'SENDER_MAIL' => 'easybuybuy2014@gmail.com',
        'SENDER_PWD' => 'MAXS204gems',
        'SENDER_NAME' => 'EasyBuyBuy',
        'REPLY_EMAIL' => '',
        'REPLY_NAME' => '',
        'SECURE_PRO' => 'ssl'
    ),
    // JPush configuration
    'JPush' => array(
        'app_key' => '5df39a7723b31e8abc5a826d',
        'master_secret' => '7d718cd8946d52c1afee84b0',
        'platform' => 'android,ios'
    )
);
return array_merge(require 'config.inc.php', $app_config);