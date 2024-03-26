<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ldap\Committee;
use App\Ldap\Group;
use App\Ldap\Role;
use App\Ldap\User;
use Illuminate\Http\Request;

class Groups extends Controller
{
    public function all(Request $request)
    {
        $committees = $this->prepareData(function (string $dn){
            return true;
        });
        return response()->json($committees);
    }

    public function fromCommunity(Request $request, string $community_uid)
    {
        $committees = $this->prepareData(function (string $dn) use ($community_uid){
            return str_contains($dn, "ou=Committees,ou=$community_uid");
        });
        return response()->json($committees);
    }

    private function prepareData(callable $filter) : array
    {
        /** @var User $ldapUser */
        $ldapUser = \Auth::user()->ldap();
        $userDn = $ldapUser->getDn();

        $roles = Role::query()->where('uniqueMember', $userDn)->get();

        $groupsQuery = Group::query()->orWhere('uniqueMember', '=', $userDn);
        foreach ($roles as $role){
            $groupsQuery->orWhere('uniqueMember', '=', $role->getDn());
        }
        $groups = $groupsQuery->get();

        $groupDns = $groups->map(function ($item){
            return $item->getDn();
        })->reject(function (string $dn){
            // throw out the committee roles. only memberships and permissions inside
            return str_contains($dn, 'ou=Committees');
        })->filter($filter)
        ->toArray();

        return $groupDns;
    }
}
