<?php

namespace App\Livewire\Forms;

use App\Models\WithdrawalRequest;
use Livewire\Attributes\Rule;
use Livewire\Form;

class WithdrawalRequestForm extends Form
{

    public ?WithdrawalRequest $request;

    #[Rule('required')]
    public string $type = '';
    #[Rule('required')]
    public string $email = '';
    #[Rule('required')]
    public string $amount = '';

    public function fillForm(WithdrawalRequest $request)
    {
        $this->request = $request;

        $this->fill(
            $request->only('email', 'type', 'amount')
        );
    }

    public function store()
    {
        $this->validate();

        $data = $this->only('email', 'type', 'amount');

        $request = WithdrawalRequest::create($data);

        $this->reset();

        return $request;
    }
}
