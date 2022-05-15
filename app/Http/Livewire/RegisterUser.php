<?php

namespace App\Http\Livewire;

use App\Models\Domain;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Validation\Rules\Password;
use Mockery\Generator\StringManipulation\Pass\Pass;

class RegisterUser extends Component
{
    public User $user;

    public string $domain = '';

    /** extra, so password can stay hidden in $user */
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules() : array
    {
        return [
            'user.full_name' => ['required', 'string', 'max:255'],
            'user.username' => ['required', 'string', 'max:255', 'unique:App\Models\User,username', 'regex:/[a-z_\.]/i' ],
            'user.email' => [
                'required', 'string', 'email', 'max:255', 'unique:App\Models\User,email',
            ],
            'domain' => [Rule::exists(Domain::class, 'name')->where('forRegistration', true)],
            'password' => [
                'required',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'confirmed',
            ],
        ];
    }

    /**
     * Do some stuff if email after email was changed
     * @return void
     */
    public function updatedUserEmail(): void
    {
        $split = explode('@', $this->user->email);
        $this->domain = $split[1] ?? 'false';
        $this->validateOnly('email');
        $this->validateOnly('domain');
        // if mail is valid try to prefill the fullName of the user
        $this->user->full_name = ucwords(str_replace(['-', '_', '.'], ' ', $split[0]));
        $this->validateOnly('user.full_name');

    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.register-user')->layout('layouts.guest');
    }

    public function mount() : void
    {
        $this->user = new User();
    }

    public function store(){

        $this->validate();
        $this->user->password = Hash::make($this->password);
        $this->user->save();

        event(new Registered($this->user));

        Auth::login($this->user);

        return redirect(RouteServiceProvider::HOME);
    }
}
