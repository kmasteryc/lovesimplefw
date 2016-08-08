<?php
namespace LoveSimple\Models;

use LoveSimple\Model;

class Article extends Model{
    public $fillable = ['*'];
    public function tags(){
        return $this->hasMany(Tag::class);
    }
}