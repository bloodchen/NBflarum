<?php
namespace Chen\NbdomainLogin;

use Flarum\Database\AbstractModel;
use Flarum\Post\Post;
use Flarum\User\User;

class PostNbdomaintips extends AbstractModel
{
    protected $table = 'post_nbdomaintips';

    public $timestamps = true;

    public $dates = ['created_at', 'updated_at'];

    public function nbdomaintips()
    {
        return $this->belongsTo(Nbdomaintips::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
