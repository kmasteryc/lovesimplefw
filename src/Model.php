<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 06/08/2016
 * Time: 21:21
 */
namespace LoveSimple;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();

        $capsule = new Manager;

        $capsule->addConnection(config('db_config'));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

