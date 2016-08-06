<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 06/08/2016
 * Time: 21:21
 */
namespace LoveSimple;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel{
    use DIContainer;
    public static $db;
    public function __construct()
    {
        parent::__construct();

        self::$db = $this->container('capsule');
    }
}

