<?php

namespace App\Livewire;

use App\Enums\RemittanceStatus;
use App\Models\Location;
use App\Models\WithdrawalMethod;
use App\Models\Remittance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class RemittanceAccepted extends Component
{
    use WithPagination;
    use RemittanceMethods;
    use Toast;
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

    #[On('disbursement.failed')]
    public function bookCreated()
    {
        // dd('Refresh');
        //$this->refresh();
    }

    public function updatedQ($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $requests = Remittance::where('status', RemittanceStatus::ACCEPTED)
            ->when(filled(trim($this->q)), function ($query) {
                $query->where(function ($query) {
                    $query->where('receiver_email', 'LIKE', '%' . trim($this->q) . '%')
                        ->orWhere('reference', 'LIKE', '%' . trim($this->q) . '%')
                        ->orWhere('receivable', 'LIKE', '%' . trim((int)$this->q * 100) . '%')
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
