<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;


class WithdrawalRequestIndex extends Component
{
    use WithPagination;
    use WithdrawalRequestMethods;
    use Toast;
    #[Rule('required')]
    public string $rejection_message = '';
    public string $method = 'CASH';

    #[Url(as: 'q')]
    public string $q;

    // protected $listeners = [
    //     'disbursement.failed' => '$refresh'
    // ];


    public function __construct()
    {
        $this->q = '';
    }

    public function updatedQ($value)
    {
        $this->resetPage();
    }



    public function render()
    {

        $requests = WithdrawalRequest::when(filled(trim($this->q)), function ($query) {
            $query->where('email', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('reference', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('type', 'LIKE', '%' . trim($this->q) . '%');
        })
            ->latest()
            ->paginate(6);
        $withdrawal_methods = WithdrawalMethod::get();
        $locations = Location::get();
        return view('livewire.withdrawal-request.index', [
            'requests' => $requests,
            'locations' => $locations,
            'methods' => $withdrawal_methods,
        ]);
    }
}
