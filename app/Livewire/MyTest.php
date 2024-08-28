<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class MyTest extends Component
{
    public function render()
    {
        $orders = Order::get();
        $payments = Payment::orderBy('id', 'DESC')->take(10)->get();

        $multiLineChartModel = $payments
            ->reduce(
                function ($multiLineChartModel, $data) use ($payments) {
                    $index = $payments->search($data);

                    return $multiLineChartModel
                        ->addSeriesPoint($data->status, $index, $data->received_amount / 100,  ['id' => $data->id]);
                },
                LivewireCharts::multiLineChartModel()
                    ->setTitle('')
                    ->setAnimated(true)
                    ->withOnPointClickEvent('onPointClick')
                    ->setSmoothCurve()
                    ->multiLine()
                    ->setDataLabelsEnabled(true)
                    ->setColors(['#0f0', '#f30ab0'])
            );

        return view('livewire.my-test', [
            'multiLineChartModel' => $multiLineChartModel,
        ]);
    }
}
