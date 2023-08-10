<?php

namespace App\Security\Permissions;

class MoviePermissions
{
    public const RATED = 'movie.rated';
    public const EDIT = 'movie.edit';
    public const DELETE = 'movie.delete';
    public const All = [
        self::RATED,
        self::EDIT,
        self::DELETE,
    ];
}
