<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CompleteAgentxRemittance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $reference;

    /**
     * Create a new job instance.
     * 
     * @param string $reference Reference for the remittance completion.
     */
    public function __construct(string $reference)
    {
        $this->reference = $reference;
    }

    /**
     * Execute the job.
     *
     * Sends a POST request to complete a remittance.
     */
    public function handle(): void
    {
        $url = env('AGENTX_DOMAIN') . 'v1/remittances/complete';
        Log::info('AGENTX DOMAIN ' . $url);

        $this->sendHttpPostRequest($url, [
            'reference' => $this->reference,
        ]);
    }

    /**
     * Send a HTTP POST request using Guzzle.
     *
     * @param string $url The URL to send the request to.
     * @param array $data The data to send in the request.
     * @throws \Exception If the request fails.
     */
    private function sendHttpPostRequest(string $url, array $data)
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

            $body = $response->getBody()->getContents();
            Log::info('AGENTX Remittance RESP');
            Log::info($body);

        } catch (RequestException $e) {
            Log::error('AGENTX Remittance ERROR');
            Log::error($e->getMessage());
            if ($e->hasResponse()) {
                Log::error($e->getResponse()->getBody()->getContents());
            }
            throw new \Exception('HTTP request failed: ' . $e->getMessage());
        }
    }
}
