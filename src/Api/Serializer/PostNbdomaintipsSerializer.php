<?php

namespace Chen\NbdomainLogin\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
//use Chen\NbdomainLogin\Api\Serializer\NbdomaintipsSerializer;

class PostNbdomaintipsSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'post_nbdomaintips';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($postNbdomaintips)
    {
        return [
            'userId'     => $postNbdomaintips->user_id,
            'postId'     => $postNbdomaintips->post_id,
            'senderId' 	 => $postNbdomaintips->sender_id,
            'tipsAmount' => $postNbdomaintips->tips_amount,
            'date' 		 => $postNbdomaintips->date,
        ];
    }

    public function nbdomaintips($postNbdomaintips)
    {
        return $this->hasOne($postNbdomaintips, NbdomaintipsSerializer::class);
    }

    public function user($postNbdomaintips)
    {
        return $this->hasOne($postNbdomaintips, BasicUserSerializer::class);
    }

    public function post($postNbdomaintips)
    {
        return $this->hasOne($postNbdomaintips, BasicPostSerializer::class);
    }
}
