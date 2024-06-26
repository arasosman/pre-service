<?php

namespace App\Models;

use App\Observers\CommentObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $blog_id
 * @property int $user_id
 * @property string $comment
 * @property-read Blog $blog
 * @property-read User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[ObservedBy([CommentObserver::class])]
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'user_id',
        'comment',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
