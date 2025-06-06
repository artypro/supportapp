<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'channel',
        'category_id',
        'subject',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'channel' => 'string',
    ];

    const STATUS_INCOMPLETE = 'Incomplete';
    const STATUS_NEW = 'New';
    const STATUS_PENDING = 'Pending';
    const STATUS_RESOLVED = 'Resolved';
    const STATUS_CLOSED = 'Closed';

    const CHANNEL_WEB = 'WEB';
    const CHANNEL_TLGM = 'TLGM';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
}