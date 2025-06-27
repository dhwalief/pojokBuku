<?php

namespace App\Models;

use App\Enums\BorrowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'date_borrowed',
        'date_return',
        'status', // 'status' can be 'dipinjam' (borrowed) or 'dikembalikan' (returned)
    ];

    protected $casts = [
        'date_borrowed' => 'datetime',
        'date_returned_should_be' => 'datetime',
        'date_returned_actual' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BorrowStatus::class, /
        ];
    }

    // RELASI: Satu record peminjaman dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI: Satu record peminjaman merujuk pada satu Buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
