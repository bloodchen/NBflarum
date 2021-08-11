<?php

namespace Chen\NbdomainLogin;

use Flarum\Database\AbstractModel;

class Nbdomaintips extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'post_nbdomaintips';

    /**
     * Create a nbdomaintips.
     *
     * @param $identifier
     * @param $type
     * @param bool $enabled
     *
     * @return static
     */
    public static function build($user_id, $post_id, $sender_id, $tips_amount, $trx, $date)
    {
        $nbdomaintips = new static();
        $nbdomaintips->user_id = $user_id;
        $nbdomaintips->post_id = $post_id;
        $nbdomaintips->sender_id = $sender_id;
        $nbdomaintips->tips_amount = $tips_amount;
        $nbdomaintips->trx = $trx;
        $nbdomaintips->date = $date;

        return $nbdomaintips;
    }
}
