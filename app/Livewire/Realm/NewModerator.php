<?php

namespace App\Livewire\Realm;

use App\Ldap\Community;
use App\Ldap\User;
use LdapRecord\LdapRecordException;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

class NewModerator extends Component
{
    #public string $search = "";

    #[Rule('required|string')]
    public string $dn = "";

    #[Rule('required|string')]
    public string $realm_uid = "";


    public function mount(Community $uid) : void
    {
        $this->realm_uid = $uid->getFirstAttribute('ou');
    }

    #[Title('realms.new_mod_title')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $c = Community::findOrFailByUid($this->realm_uid);
        $userList = $c->membersGroup()->members()->get();
        $moderators = $c->moderatorsGroup()->members()->get();

        return view('livewire.realm.new-moderator', [
            'community' => $c,
            'selectable_users' => $userList->except($moderators->keys()),
        ]);
    }

    public function save()
    {
        $this->validate();
        try{
            $user = User::findOrFail($this->dn);
            $realm = Community::findOrFailByUid($this->realm_uid);
            $realm->moderatorsGroup()->members()->attach($user);
            return redirect()->route('realms.mods', ['uid' => $this->realm_uid])
                ->with('message', __('Added new Moderator'));
        } catch (LdapRecordException $exception){
            $this->addError('dn', $exception->getMessage());
            return false;
        }
    }


}
