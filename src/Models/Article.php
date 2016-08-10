<?php
namespace LoveSimple\Models;

use LoveSimple\Model;

class Article extends Model{
    public $fillable = ['*'];

    public function setArticleTitleAttribute($value)
    {
        $this->attributes['article_title'] = $value;
        $this->attributes['article_slug'] = str_slug($value);
        $this->attributes['article_title_eng'] = str_replace("-"," ",str_slug($value));
    }
    public function tags(){
        return $this->hasMany(Tag::class);
    }
    public function cate(){
        return $this->belongsTo(Cate::class);
    }
}