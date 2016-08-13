<?php
namespace LoveSimple\Models;

use LoveSimple\Model;
use Illuminate\Support\Collection;

class Tag extends Model
{
//    public $table = 'tags';
    protected $fillable = ['tag_title'];
    public $timestamps = false;

//    public function __construct(){
//        parent::__construct();
//    }

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
                $item = htmlspecialchars($item);
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