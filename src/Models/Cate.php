<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 07/08/2016
 * Time: 21:12
 */
namespace LoveSimple\Models;

use LoveSimple\Model;

class Cate extends Model
{
    public $timestamps = false;
    protected $fillables = ['*'];


    public function setCateTitleAttribute($value)
    {
        $this->attributes['cate_title'] = $value;
        $this->attributes['cate_slug'] = str_slug($value);
        $this->attributes['cate_title_eng'] = str_replace("-", " ", str_slug($value));
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getMeAndMyChilds($include_self = true)
    {
        $query = Cate::where([
            'cate_parent' => $this->attributes['id']
        ]);
        if ($include_self) {
            $query->orWhere([
                'id' => $this->attributes['id']
            ]);
        }
        return $query;
    }
}