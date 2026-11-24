<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Word;

class WordPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Word $word): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Word $word): bool
    {
        return $word->user_id !== null
            && $user->id === $word->user_id;
    }

    public function delete(User $user, Word $word): bool
    {
        return $word->user_id !== null
            && $user->id === $word->user_id;
    }
}
