<?php

// File: app/Models/Borrow.php
namespace App\Models;

use App\Enums\BorrowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'date_borrowed', 
        'date_returned', 'status'
    ];

    protected function casts(): array
    {
        return [
            'date_borrowed' => 'datetime',
            'date_returned' => 'datetime',
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
    public function scopeActive($query)
    {
        return $query->where('status', BorrowStatus::Borrowed);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', BorrowStatus::Borrowed)
                    ->where('date_returned', '<', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', BorrowStatus::Returned);
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->status === BorrowStatus::Borrowed && $this->date_returned < now();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->status !== BorrowStatus::Borrowed) {
            return 0;
        }
        
        return max(0, $this->date_returned->diffInDays(now(), false));
    }
}
