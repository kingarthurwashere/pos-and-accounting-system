<?php

namespace App\Livewire;

use App\Enums\RemittanceStatus;
use App\Models\Location;
use App\Models\WithdrawalMethod;
use App\Models\Remittance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class RemittanceIndex extends Component
{
    use WithPagination;
    use RemittanceMethods;
    use Toast;

    #[Rule('required')]
    public string $rejection_message = '';
    public string $method = 'CASH';

    #[Url(as: 'q')]
    public string $q;

    #[Url(as: 'page')]
    public string $page;

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
        $requests = Remittance::where('funded_location_id', auth()->user()->login_location_id)
            ->when(filled(trim($this->q)), function ($query) {
                $query->where(function ($query) {
                    $query->where('receiver_email', 'LIKE', '%' . trim($this->q) . '%')
                        ->orWhere('reference', 'LIKE', '%' . trim($this->q) . '%')
                        ->where('funded_location_id', auth()->user()->login_location_id)
                        ->orWhere('sender_name', 'LIKE', '%' . trim($this->q) . '%')
                        ->orWhere('receiver_name', 'LIKE', '%' . trim($this->q) . '%');
                });
            })
            ->latest()
            ->paginate(6);

        $withdrawal_methods = WithdrawalMethod::get();
        $locations = Location::get();

        return view('livewire.remittance.index', [
            'requests' => $requests,
            'locations' => $locations,
            'methods' => $withdrawal_methods,
        ]);
    }
}
