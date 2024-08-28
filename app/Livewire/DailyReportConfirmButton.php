<?php

namespace App\Livewire;

use App\Livewire\Forms\DailyReportConfirmRequestForm;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;
use Livewire\Attributes\Rule;

class DailyReportConfirmButton extends Component
{
    use Toast;
    public bool $myModal = false
    ;

    public DailyReportConfirmRequestForm $requestForm;

    public Collection $usersSearchable;
 
    
    public function mount()
    {
        // Fill options when component first renders
        $this->search();
    }
 
    // Also called as you type
    public function search(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = User::where('id', '!=', auth()->user()->id)->get();
 
        $this->usersSearchable = User::query()
            ->where('id', '!=', auth()->user()->id)
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);     // <-- Adds selected option
    }

    public function render()
    {
        return view('livewire.daily-report.confirm-button', [
            'users' => User::where('id', '!=', auth()->user()->id)->get()
        ]);
    }

    public function confirmDailyReport()
    {
        $this->requestForm->validate();
        $this->requestForm->confirmDailyReport();
        $this->myModal = false;
        $this->dispatch('daily-report-confirmed'); 

        $selected_user = User::find($this->requestForm->assignedVerifier);

        $this->toast(
            type: 'success',
            title: "Confirmed And Sent To `{$selected_user->name}` for VERIFICATION",
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-check',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
