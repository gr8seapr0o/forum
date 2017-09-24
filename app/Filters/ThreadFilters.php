<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popular', 'answered'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return $Builder
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query according to unanswered threads.
     *
     * @return $Builder
     */
    protected function answered()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder
            ->whereHas('replies')
            ->orderBy('replies_count', 'desc');

        /*return $this->builder
            ->join('replies', 'threads.id', '=', 'replies.thread_id')
            ->distinct()
            ->orderBy('replies_count', 'desc');*/
    }    
}
