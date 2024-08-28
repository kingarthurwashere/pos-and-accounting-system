<?php

namespace App\Livewire;

use App\Enums\RemittanceStatus;
use App\Models\Remittance;
use Carbon\Carbon;

trait RemittanceMethods
{
    public function approve(int $id)
    {
        $rem = Remittance::find($id);
        $rem->status = 'ACCEPTED';
        $rem->accepted_by = auth()->user()->id;
        $rem->accepted_at = Carbon::now();

        try {
            if ($rem->save()) {
                $this->toast(
                    type: 'success',
                    title: 'Remittance accepted',
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
                    title: 'Acceptance failed',
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
                title: $th->getMessage(),
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

        if ($this->rejection_message === '') {
            $this->toast(
                type: 'warning',
                title: 'Please enter a rejection messsage',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        } else {
            try {
                $rem = Remittance::find($id);
                $rem->status = RemittanceStatus::REJECTED;
                $rem->rejected_by = auth()->user()->id;
                $rem->rejected_at = Carbon::now();
                $rem->rejection_message = $this->rejection_message;

                if ($rem->save()) {

                    $this->toast(
                        type: 'success',
                        title: 'Remittance has been rejected',
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
                    title: $th->getMessage(),
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
        $rem = Remittance::find($id);
        if (!auth()->user()->location->locationFloatSuffices($rem->receivable)) {

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
            $rem->status = 'DISBURSED';
            //$rem->withdrawal_method_slug = $this->method;
            $rem->disbursed_by = auth()->user()->id;
            $rem->disburse_location_id = auth()->user()->login_location_id;

            try {
                if ($rem->save()) {

                    $this->toast(
                        type: 'success',
                        title: 'USD' . $rem->formattedReceivable() . ' disbursed as ' . $rem->method->name . ' to ' . $rem->receiver_name . '.',
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
                    title: $th->getMessage(),
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
