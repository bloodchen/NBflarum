<?php

namespace Chen\NbdomainLogin\Command;

use Flarum\User\Exception\PermissionDeniedException;
use Chen\NbdomainLogin\Nbdomaintips;
use Chen\NbdomainLogin\Validator\NbdomaintipsValidator;
use Chen\NbdomainLogin\Command\CreateNbdomaintips;
use Illuminate\Support\Arr;

class CreateNbdomaintipsHandler
{
    /**
     * @var ReactionValidator
     */
    protected $validator;

    /**
     * @param ReactionValidator $validator
     */
    public function __construct(NbdomaintipsValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param CreateReaction $command
     *
     * @throws PermissionDeniedException
     *
     * @return Reaction
     */
    public function handle(CreateNbdomaintips $command)
    {
        $actor = $command->actor;
        $data = $command->data;

        //$actor->assertAdmin();

        $nbdomaintips = Nbdomaintips::build(
            Arr::get($data, 'user_id'),
            Arr::get($data, 'post_id'),
            Arr::get($data, 'sender_id'),
            Arr::get($data, 'tips_amount'),
            Arr::get($data, 'trx'),
            Arr::get($data, 'date'),
        );

        //$this->validator->assertValid($reaction->getAttributes());

        $nbdomaintips->save();

        return $nbdomaintips;
    }
}
