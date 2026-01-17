<?php

namespace App\Enums;

enum BorrowStatus : string
{
    case Borrowed = 'dipinjam';
    case Returned = 'dikembalikan';    
}
