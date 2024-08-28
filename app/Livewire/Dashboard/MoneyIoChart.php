<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MoneyIoChart extends Component
{

    public function render()
    {
        $name = 'Some Name'; // Define your variable locally
        return view('livewire.dashboard.money-io-chart', compact('name'));
    }
}
