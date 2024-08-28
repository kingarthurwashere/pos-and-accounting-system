<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Remittance;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RemittanceDailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //agentx_id
        $client = new Client();
        $response = $client->request('GET', "https://api.instabucks.co.zw/v1/remittances/awaiting-pickup-today");
        $statusCode = $response->getStatusCode(); // 200
        $data = $response->getBody()->getContents();

        $dataArray = json_decode($data, true);

        foreach ($dataArray as $i) {
            $exist = Remittance::where('reference', $i['reference'])->first();

            if (!$exist) {
                $city = City::where('slug', $i['city']['slug'])->first();

                Log::info('City');
                Log::info($city);
                Log::info('Locations');
                Log::info($city->locations);

                Remittance::create([
                    'reference' => $i['reference'],
                    'amount' => $i['amount'],
                    'receivable' => $i['receivable'],
                    'receiver_name' => $i['receiver_name'],
                    'funded_location_id' => $city->locations()->first()->id,
                    'receiver_email' => $i['receiver_email'],
                    //'receiver_email' => 'maunga.simbarashe@gmail.com',
                    'receiver_phone' => $i['receiver_phone'],
                    'sender_name' => $i['sender_name'],
                    'agent_name' => $i['agent_name'],
                    'due_at' => $i['updated_at'],
                ]);
            }
        }
    }
}
