<?php

/*
 * This file is part of fof/reactions.
 *
 * Copyright (c) 2020 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chen\NbdomainLogin\Validator;

use Flarum\Foundation\AbstractValidator;

class NbdomaintipsValidator extends AbstractValidator
{
    protected $rules = [
        'trx' => [
            'required',
            'string',
        ],
        'post_id' => [
            'required',
            'integer',
        ],
        'user_id' => [
            'required',
            'integer',
        ],
        'sender_id' => [
            'required',
            'integer',
        ],
        /*'type' => [
            'required',
            'string',
            'regex:/icon|emoji/i',
        ],
        'enabled' => [
            'nullable',
            'bool',
        ],*/
    ];
}
