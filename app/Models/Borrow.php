<?php

// File: app/Models/Borrow.php
namespace App\Models;

use App\Enums\BorrowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Borrow extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Perubahan: Mengganti 'date_returned' dengan 'due_date' dan 'returned_at'.
     */
    protected $fillable = [
        'user_id', 
        'book_id', 
        'date_borrowed', 
        'due_date',      // Batas waktu pengembalian
        'returned_at',   // Tanggal pengembalian aktual
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * Perubahan: Menambahkan casting untuk kolom baru.
     */
    protected function casts(): array
    {
        return [
            'date_borrowed' => 'datetime',
            'due_date'      => 'datetime',
            'returned_at'   => 'datetime',
            'status'        => BorrowStatus::class, // Disarankan menggunakan Enum Class
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Scopes

    /**
     * Scope untuk peminjaman yang masih aktif (belum dikembalikan).
     */
    public function scopeActive($query)
    {
        return $query->where('status', BorrowStatus::Borrowed);
    }

    /**
     * Scope untuk peminjaman yang sudah melewati batas waktu (due_date).
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', BorrowStatus::Borrowed)
                    ->where('due_date', '<', now());
    }

    /**
     * Scope untuk peminjaman yang sudah selesai.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', BorrowStatus::Returned);
    }

    // Accessors & Mutators (menggunakan syntax modern)

    /**
     * Mengecek apakah peminjaman sudah jatuh tempo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === BorrowStatus::Borrowed && $this->due_date->isPast(),
        );
    }

    /**
     * Menghitung sisa hari peminjaman.
     * Mengembalikan 0 jika sudah dikembalikan atau sudah lewat jatuh tempo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function daysRemaining(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status !== BorrowStatus::Borrowed || $this->isOverdue) {
                    return 0;
                }
                // diffInDays(now(), false) akan memberikan nilai negatif jika due_date di masa depan
                return now()->diffInDays($this->due_date, false);
            }
        );
    }
}
