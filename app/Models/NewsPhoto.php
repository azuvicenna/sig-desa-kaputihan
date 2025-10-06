<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPhoto extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
