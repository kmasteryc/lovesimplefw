<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 20:45
 */
return [
    'default' => [
       'page' => 'home@welcome'
    ],
    'GET' => [
        'home/index/{name}' => [
            'page' => 'home@getIndex'
        ]
    ],
    'POST' => [
        'home/index/' => [
            'page' => 'home@postIndex'
        ]
    ],
    'PATCH' => [

    ],
    'DELETE' => [

    ]
];