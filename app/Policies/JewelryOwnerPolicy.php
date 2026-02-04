<?php

namespace App\Policies;

use App\User; // <- این خط را اضافه کن
use App\Models\JewelryOwner;
use Illuminate\Auth\Access\HandlesAuthorization;

class JewelryOwnerPolicy
{
    use HandlesAuthorization;

    public function view(User $user, JewelryOwner $owner)
    {
        return $user->id === $owner->user_id;
    }

    public function update(User $user, JewelryOwner $owner)
    {
        return $user->id === $owner->user_id;
    }

    public function delete(User $user, JewelryOwner $owner)
    {
        return $user->id === $owner->user_id;
    }

    public function create(User $user)
    {
        return true;
    }
}
