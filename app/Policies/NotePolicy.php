<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    

    public function owner(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }
}
