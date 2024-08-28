<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class WithdrawalRequestPending extends Component
{
    use WithPagination;
    use Toast;
    public string $rejection_message = '';
    public string $method = 'CASH';

    #[Url(as: 'q')]
    public string $q;

    public function __construct()
    {
        $this->q = '';
    }

    public function updatedQ($value)
    {
        $this->resetPage();
    }

    public function approve(int $id)
    {
        $withdrawal = WithdrawalRequest::find($id);
        $withdrawal->status = 'APPROVED';
        $withdrawal->approved_by = auth()->user()->id;
        $withdrawal->approval_datetime = Carbon::now();

        try {
            if ($withdrawal->save()) {
                $this->toast(
                    type: 'success',
                    title: 'Request has been approved',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
                $this->redirect('/withdrawal-requests/approved');
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Approval failed',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: 'Approval failed',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function reject(int $id)
    {
        $withdrawal = WithdrawalRequest::find($id);
        $withdrawal->status = 'REJECTED';
        $withdrawal->rejected_by = auth()->user()->id;
        $withdrawal->rejection_datetime = Carbon::now();
        $withdrawal->rejection_message = $this->rejection_message;

        try {
            if ($withdrawal->save()) {

                $this->toast(
                    type: 'success',
                    title: 'Request has been rejected',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-warning',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Rejection failed',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: 'Rejection failed',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function disburse(int $id)
    {
        $withdrawal = WithdrawalRequest::find($id);
        $withdrawal->status = 'DISBURSED';
        $withdrawal->withdrawal_method_slug = $this->method;
        $withdrawal->disbursed_by = auth()->user()->id;
        $withdrawal->disbursement_location_id = auth()->user()->login_location_id;
        $withdrawal->disburse_datetime = Carbon::now();

        try {
            if ($withdrawal->save()) {

                $this->toast(
                    type: 'success',
                    title: 'USD' . $withdrawal->formattedAmount() . ' disbursed as ' . $withdrawal->method->name . ' to ' . $withdrawal->email . '.',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } else {

                $this->toast(
                    type: 'error',
                    title: 'Disbursement failed',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } catch (\Throwable $th) {
            Log::error('Disbursement failed Pending');
            Log::error($th->getMessage());
            $this->toast(
                type: 'error',
                title: 'Disbursement failed!',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function render()
    {
        $requests = WithdrawalRequest::when(filled(trim($this->q)), function ($query) {
            $query->where('email', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('reference', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('type', 'LIKE', '%' . trim($this->q) . '%');
        })->where('status', 'PENDING')
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
