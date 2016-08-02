<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 23:13
 */
namespace LoveSimple\Service;
use League\Flysystem\FilesystemInterface;

class ConfigService{
    protected $config;
    public function __construct(FilesystemInterface $fs)
    {
        $this->config = json_decode($fs->read('menu.json',true));
    }

    public function getConfig()
    {
        return $this->config;
    }
}