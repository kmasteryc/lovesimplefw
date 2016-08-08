<?php
namespace LoveSimple\Models;

use LoveSimple\Model;

class Tag extends Model{
    public $fillable = ['*'];
    public function articles(){
        return $this->belongsToMany(Article::class);
    }
}