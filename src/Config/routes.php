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
        'home/create' => [
            'page' => 'home@create'
        ],
        'home/{id}' => [
            'page' => 'home@show',
        ],
        'bai-hat/{id}/edit' => [
            'page' => 'home@edit',
            'id' => '[1-50]',
            'name' => 'editHome'
        ],
        '{default}' => [
            'page' => 'home@index',
            'default' => '[a-zA-Z]+'
        ]
    ],
    'POST' => [
        'home' => [
            'page' => 'home@store'
        ]
    ],
    'PATCH' => [
        'home{id}' => [
            'page' => 'home@update'
        ]
    ],
    'DELETE' => [
        'home/{id}' => [
            'page' => 'home@delete'
        ]
    ]
];



//return [
//    '/home/dd/ss/{name}' => 'home@index',
//];