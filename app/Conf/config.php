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
        )
    )
);
return array_merge(require 'config.inc.php', $app_config);