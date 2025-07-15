<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case DEACTIVATED = 'deactivated';
    case SUSPENDED = 'suspended';
    case PENDING = 'pending';
    case DELETED = 'deleted';
}
