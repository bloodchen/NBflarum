<?php

namespace Chen\NbdomainLogin\Listener;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\UserSerializer;

class AddUserOpayAddressAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(UserSerializer::class)) {
            $event->attributes += [
                'opayaddress'        => $event->model->opayaddress,
            ];
        }
    }
}
