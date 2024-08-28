<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class PostTime extends Component
{
    public function render()
    {
        return view('livewire.post-time', [
            'date_time' => Carbon::now(),
        ]);
    }
}
