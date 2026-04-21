<?php

namespace App\Enums;

enum ProductStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case HIDDEN = 'hidden';
    case OUT_OF_STOCK = 'out_of_stock';
}
