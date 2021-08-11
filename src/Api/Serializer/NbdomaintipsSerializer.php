<?php

namespace Chen\NbdomainLogin\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;

class NbdomaintipsSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'nbdomaintips';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($nbdomaintips)
    {
        return [
            'user_id' 		=> $nbdomaintips->user_id,
            'post_id'    	=> $nbdomaintips->post_id,
            'sender_id'     => $nbdomaintips->sender_id,
            'tips_amount'   => $nbdomaintips->tips_amount,
            'trx'    		=> $nbdomaintips->trx,
			'date'    		=> $nbdomaintips->date,
        ];
    }
}
