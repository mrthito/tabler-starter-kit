<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategoryPost extends Model
{
    protected $table = 'post_category_posts';

    protected $fillable = [
        'post_id',
        'post_category_id',
    ];

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }
}
