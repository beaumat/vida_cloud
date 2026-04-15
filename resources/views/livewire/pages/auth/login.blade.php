<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirect(route('dashboard'));
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="mb-3 input-group">
            <x-text-input wire:model="form.name" id="name" class="form-control" type="text" name="name" required
                autofocus placeholder="Username" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
        </div>
        <div class="mb-3 input-group">
            <x-text-input wire:model="form.password" id="password" class="form-control" type="password" name="password"
                required autocomplete="current-password"  placeholder="Password"/>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-12">
                <x-primary-button class="text-md btn btn-sm btn-info btn-block">
                    {{ __('Log-In') }}
                </x-primary-button>
            </div>
            <!-- /.col -->
        </div>
    </form>



</div>
