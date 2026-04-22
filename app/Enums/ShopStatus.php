<?php

namespace App\Enums;

enum ShopStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case BANNED = 'banned';
}
