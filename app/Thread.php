<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //
    use RecordsActivity;
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['creator', 'channel'];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        /* считаем ответы для каждого запроса */
        static::addGlobalScope('replyCount', function ($builder) {
            /* if you want to count the number of results
            * from a relationship without actually loading
            * which will place a {relation}_count column on your resulting models
            * in this case - replies_count (see App\Filters\ThreadFilters) */
            $builder->withCount('replies');
        });
        /* событие удаления */
        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }
    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    /**
     * A thread belongs to a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * A thread is assigned a channel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    /**
     * A thread may have many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    /**
     * Add a reply to the thread.
     *
     * @param $reply
     */
    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder       $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    /* Scopes are defined by prefixing the name of a method with scope */
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
    /**
     * Determine if the thread has been updated since the user last read it.
     *
     * @param  User $user
     * @return bool
     */
    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }
}
