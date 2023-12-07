<?php

namespace App\Policies;

use App\Ldap\Committee;
use App\Ldap\Community;
use App\Models\RoleUserRelation;
use App\Models\User;

class MembershipPolicy
{
    public function create(User $user, Committee $committee, Community $community) : bool {
        return // add committee mods
            $user->can('moderator', [$committee, $community]);
    }

    public function edit(User $user, RoleUserRelation $membership, Committee $committee, Community $community) : bool {
        return $user->can('moderator', [$committee, $community]);
    }

    public function delete(User $user, RoleUserRelation $membership, Committee $committee, Community $community) : bool {
        return $user->can('moderator', [$committee, $community]);
    }

    public function view(User $user, RoleUserRelation $membership, Committee $committee, Community $community) : bool {
        return $user->can('member', [$committee, $community])
            || $user->can('superadmin');
    }

}
