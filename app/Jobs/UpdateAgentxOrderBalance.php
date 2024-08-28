<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateAgentxOrderBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentId;

    /**
     * Create a new job instance.
     *
     * @param int $paymentId The ID of the payment.
     */
    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     *
     * This method handles the updating of the Agentx order balance.
     */
    public function handle(): void
    {
        $payment = Payment::find($this->paymentId);
        if (!$payment) {
            Log::error("Payment ID {$this->paymentId} not found.");
            return;
        }

        $order = Order::find($payment->payable_identifier);
        if (!$order) {
            Log::error("Order for payment ID {$this->paymentId} not found.");
            return;
        }

        $this->sendHttpPostRequest(env('AGENTX_DOMAIN') . 'v1/orders/upload-pop', [
            'order_id' => $order->agentx_order_id,
            'amount' => $payment->received_amount
        ]);
    }

    /**
     * Send a HTTP POST request to a given URL with JSON-encoded data using Guzzle.
     *
     * @param string $url The URL to send the request to.
     * @param array $data The data to send in the request.
     * @return void
     */
    private function sendHttpPostRequest(string $url, array $data): void
    {
        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'json' => $data,
                'headers' => [
                    'Authorization' => 'Bearer ' . env('MASTER_KEY'),
                    'Content-Type' => 'application/json',
                    'Production' => config('app.env') === 'production' ? 'true' : 'false',
                ]
            ]);

            Log::info("AGENTX RESP");
            Log::info($response->getBody()->getContents());

        } catch (RequestException $e) {
            Log::error("AGENTX ERROR");
            Log::error($e->getMessage());
            if ($e->hasResponse()) {
                Log::error($e->getResponse()->getBody()->getContents());
            }
        }
    }
}
