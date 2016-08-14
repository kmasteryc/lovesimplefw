<?php
namespace LoveSimple\Models;

use LoveSimple\Model;
use Illuminate\Support\Collection;

class Tag extends Model
{
    protected $fillable = ['tag_title'];
    public $timestamps = false;

    public function setTagTitleAttribute($value){
        $this->attributes['tag_title'] = $value;
        $this->attributes['tag_slug'] = str_slug($value);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public static function getTagIdsFromString($str)
    {
        return Collection::make(explode(',', $str))
            ->filter(function ($item) {
                return trim($item) != '';
            })
            ->map(function ($item) {
                $item = htmlspecialchars(trim($item));
//                todo: Cant use mass assignment here. Why?
                $tag = Tag::where('tag_title', $item)->first();

                if ($tag === null) {
                    $tag = new Tag;
                    $tag->tag_title = $item;
                    $tag->save();
                }
                return $tag;
            })
            ->pluck('id')->toArray();
    }

}