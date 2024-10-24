<?php

namespace App\Livewire;

use App\Ldap\User;
use Livewire\Component;
use App\Export\Mailman3\Mailman3;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{

    public function mount()
    {
        $username = Auth::user()->username;
        $user = User::findOrFailByUsername($username);
        $this->uid = $user->getFirstAttribute('uid');
        $this->fullName = $user->getFirstAttribute('cn');
        $this->email = $user->getFirstAttribute('mail');
    }

    public function export()
    {
        // Check if configuration is set correctly
        if (!getenv('MAILMAN3_LIST_OWNER') || env('MAILMAN3_LIST_OWNER') == '' ||
            !getenv('MAILMAN3_BASE_URL') || env('MAILMAN3_BASE_URL') == '' ||
            !getenv('MAILMAN3_USERNAME') || env('MAILMAN3_USERNAME') == '' ||
            !getenv('MAILMAN3_PASSWORD') || env('MAILMAN3_PASSWORD') == '' ||
            !getenv('MAILMAN3_API_BASE_URL') || env('MAILMAN3_API_BASE_URL') == '' ||
            !getenv('MAILMAN3_API_USERNAME') || env('MAILMAN3_API_USERNAME') == '' ||
            !getenv('MAILMAN3_API_PASSWORD') || env('MAILMAN3_API_PASSWORD') == ''
        ) {
            echo "Your Mailman 3 configuration is incomplete!";
        } else {
            $mm3 = new Mailman3();
            
            echo "<p>Connecting to Mailman ...</p>";

            // Try to log in to Mailman Postorius and Mailman API
            $mm3->login();
            $mm3->apiLogin();

            // Get a list of the mailing list addresses
            $lists = [];

            foreach ($lists as $address) {
                // Get list ID from address
                $id = str_replace('@', '.', $address);

                // Check if list is owned by specified list owner
                $owners = $mm3->getListRoster($address, 'owner');
                if(!in_array(env('MAILMAN3_LIST_OWNER'), $owners)) {
                    echo "<p>List not owned by the specified list owner and skipped: <strong>" . $address . "</strong>.</p>";
                } else {
                    // Get the email addresses of the current users
                    $dbmails = getMailinglistePerson($id);
                    $dbmails = array_map('strtolower', $dbmails);

                    // Get the email addresses on the mailing list
                    $mmmails = $mm3->getListRoster($address, 'member');
                    $mmmails = array_map('strtolower', $mmmails);

                    // Check if there are users to add and add them
                    $to_add = array_diff($dbmails, $mmmails);
                    if (count($to_add) > 0) {
                        echo "<p>Adding " . count($to_add) . " addresses to <strong>" . $address . "</strong>:</p><ul>";
                        foreach ($to_add as $entry) {
                            echo "<li>" . $entry . "</li>";
                        }
                        echo "</ul>";
                        $mm3->addListMembers($address, $to_add);
                        sleep(1);
                    } else {
                        echo "<p>Nothing to add to <strong>" . $address . "</strong>.</p>";
                    }

                    // Check if there are users to remove and remove them
                    $to_remove = array_diff($mmmails, $dbmails);
                    if (count($to_remove) > 0) {
                        echo "<p>Removing " . count($to_remove) . " addresses from <strong>" . $address . "</strong>:</p><ul>";
                        foreach ($to_remove as $entry) {
                            echo "<li>" . $entry . "</li>";
                        }
                        echo "</ul>";
                        $mm3->removeListMembers($address, $to_remove);
                        sleep(1);
                    } else {
                        echo "<p>Nothing to remove from <strong>" . $address . "</strong>.</p>";
                    }
                }
            }
        }

        $mm3->logout();
        echo "<p><strong>Finished!</strong></p>";
    }

    public function render()
    {
        return view('livewire.profile')->title(__('Profile'));
    }

    public function save()
    {
        $this->validate();
        if (Auth::user()->username !== $this->uid) {
            abort('500');
        }
        $user = User::findOrFailByUsername($this->uid);
        $user->setAttribute('mail', $this->email);
        $user->setAttribute('cn', $this->fullName);
        $user->save();
        return redirect()->route('profile')->with('message', __('Saved'));
    }
}
