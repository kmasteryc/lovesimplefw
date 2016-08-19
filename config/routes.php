<?php
return [
    'default' => [
        'page' => 'HomeController@index'
    ],
    'GET' =>
        [
	        'auth' =>
		        [
			        'page' => 'AuthController@login',
		        ],
            'cate/index/' =>
                [
                    'page' => 'CateController@index',
                ],
            'cate/{id}/edit' =>
                [
                    'page' => 'CateController@edit',
                ],
            'cate/create/' =>
                [
                    'page' => 'CateController@create',
                ],
            'cate/{id}/delete' =>
                [
                    'page' => 'CateController@delete',
                ],
            'chuyen-muc/{cate_slug}' =>
                [
                    'page' => 'CateController@show',
                ],
            'article/index/' =>
                [
                    'page' => 'ArticleController@index',
                ],
            'article/{id}/edit' =>
                [
                    'page' => 'ArticleController@edit',
                ],
            'article/create/' =>
                [
                    'page' => 'ArticleController@create',
                ],
            'article/{id}/delete' =>
                [
                    'page' => 'ArticleController@delete',
                ],
            'chuyen-muc/{cate_slug}/{article_slug}' =>
                [
                    'page' => 'ArticleController@show',
                ],
            'tag/{tag_slug}' =>
                [
                    'page' => 'ArticleController@showByTag'
                ],
            'import/create' =>
                [
                    'page' => 'ImportController@create',
                ],
            'import/downloadimg' =>
                [
                    'page' => 'ImportController@downloadImg',
                ],
        ],
    'POST' =>
        [
	        'auth' =>
		        [
			        'page' => 'AuthController@doLogin',
		        ],
            'cate/store/' =>
                [
                    'page' => 'CateController@store',
                ],
            'article/store/' =>
                [
                    'page' => 'ArticleController@store',
                ],
            'import/store' =>
                [
                    'page' => 'ImportController@store',
                ],
        ],
    'PUT' =>
        [
            'cate/{id}' =>
                [
                    'page' => 'CateController@update',
                ],
            'article/{id}' =>
                [
                    'page' => 'ArticleController@update',
                ]
        ]
];
