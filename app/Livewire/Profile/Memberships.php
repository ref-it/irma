<?php

namespace App\Livewire\Profile;

use App\Ldap\User;
use App\Ldap\Role;
use App\Models\RoleMembership;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class Memberships extends Component
{
    public bool $showOnlyActive = true;

    public function getMemberships(bool $onlyActive)
    {
        $query = RoleMembership::where('username', auth()->user()->username);
        if ($onlyActive) {
            $query->whereNull('until');
        }
        $roleMemberships = $query->get();
        $memberships = [];
        foreach ($roleMemberships as $row) {
            $role = Role::findOrFail('cn=' . $row->role_cn . ',' . $row->committee_dn);
            array_push($memberships, [
                'role' => $role,
                'from' => $row->from,
                'until' => $row->until,
                'decided' => $row->decided,
                'comment' => $row->comment,
            ]);
        }
        return $memberships;
    }

    public function render()
    {
        $memberships = $this->getMemberships($this->showOnlyActive);

        return view('livewire.profile.memberships', [
            'memberships' => $memberships,
        ])->title(__('Profile'));
    }

    public function exportPdf()
    {
        $memberships = $this->getMemberships(false);
        $pdf = Pdf::loadView('pdfs.memberships', [
            'fullName' => auth()->user()->full_name,
            'memberships' => $memberships,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'memberships-' . auth()->user()->username . '.pdf');;
    }
}
