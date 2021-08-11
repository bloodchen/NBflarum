<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (!$schema->hasColumn('users', 'opayaddress')) {
            $schema->table('users', function (Blueprint $table) use ($schema) {
                $table->string('opayaddress', 150)->index();
            });
        }
    },
    'down' => function (Builder $schema) {
        $schema->table('users', function (Blueprint $table) use ($schema) {
            $table->dropColumn('opayaddress');
        });
    }
];