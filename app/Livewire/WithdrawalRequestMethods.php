<?php

namespace App\Livewire;

use App\Models\WithdrawalRequest;
use Carbon\Carbon;

trait WithdrawalRequestMethods
{
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


        if ($this->rejection_message == '') {
            $this->toast(
                type: 'warning',
                title: 'Please enter a rejection message',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-warning',                  // Optional (daisyUI classes)
                timeout: 10000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        } else {
            try {
                $withdrawal = WithdrawalRequest::find($id);
                $withdrawal->status = 'REJECTED';
                $withdrawal->rejected_by = auth()->user()->id;
                $withdrawal->rejection_datetime = Carbon::now();
                $withdrawal->rejection_message = $this->rejection_message;
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
    }

    public function disburse(int $id)
    {
        $withdrawal = WithdrawalRequest::find($id);
        if (!auth()->user()->location->locationFloatSuffices($withdrawal->amount)) {
            $this->toast(
                type: 'error',
                title: 'Not enough float at your location',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 8000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        } else {
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
    }
}
