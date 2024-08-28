<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //agentx_id
        $client = new Client();
        $response = $client->request('GET', "https://api.instabucks.co.zw/v1/users/agents");
        $statusCode = $response->getStatusCode(); // 200
        $data = $response->getBody()->getContents();

        $dataArray = json_decode($data, true);

        foreach ($dataArray as $i) {
            if (!User::whereEmail($i['email'])->first()) {
                $new_user = User::create([
                    'name' => $i['personal_details']['first_name'] . ' ' . $i['personal_details']['last_name'],
                    'email' => $i['email'],
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'remember_token' => Str::random(10),
                    'profile_photo_path' => null,
                    'current_team_id' => null,
                ]);
            }

            Agent::create([
                'agentx_id' => $i['id'],
                'user_id' => $new_user->id,
                'name' => $i['personal_details']['first_name'] . ' ' . $i['personal_details']['last_name'],
                'email' => $i['email'],
                'phone' => $i['phone'],
            ]);
        }
    }
}
