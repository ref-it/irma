<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ldap\Committee;
use App\Ldap\Role;
use App\Ldap\User;
use Illuminate\Http\Request;

class Committees extends Controller
{
    public function all(Request $request)
    {
        $committees = $this->prepareData(function (string $dn){
            return str_contains($dn, "ou=Committees");
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

    private function prepareData(callable $filter = null) : array
    {
        /** @var User $ldapUser */
        $ldapUser = \Auth::user()->ldap();
        $userDn = $ldapUser->getDn();

        $roles = Role::query()->where('uniqueMember', $userDn)->get();

        $committeeDns = $roles->map(function ($item){
            return $item->getParentDn();
        })->filter($filter)->toArray();
        //Issue: you cannot query "DN in (x,y,z)" - therefore multiple single finds collected
        $committees = collect();
        foreach ($committeeDns as $committeeDn){
            $committees->add(Committee::find($committeeDn));
        }
        // returns array of committees like "stura" => "Studierendenrat"
        // FIXME: has issues with all() -> not distinguishable in multi realm setup
        return $committees
            ->keyBy(function ($item){
                // change key
                return $item->getFirstAttribute('ou');
            })->map(function ($item){
                // change value
                return $item->getFirstAttribute('description');
            })->toArray();
    }
}
