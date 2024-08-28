<?php

namespace App\Livewire;

use App\Livewire\Forms\WithdrawalRequestForm;
use App\Models\WithdrawalRequestType;
use Livewire\Component;
use Mary\Traits\Toast;

class WithdrawalRequestCreate extends Component
{
    use Toast;

    public WithdrawalRequestForm $form;

    public function save()
    {

        try {
            $this->form->store();
            $this->dispatch('withdrawal-request.created');

            // Toast
            $this->toast(
                type: 'success',
                title: 'Your request was submitted succesfully',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-success',                  // Optional (daisyUI classes)
                timeout: 10000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: $th->getMessage(),
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }

        
    }

    public function render()
    {
        $types = WithdrawalRequestType::all();

        return view('livewire.withdrawal-request.create', compact('types'));
    }
}
