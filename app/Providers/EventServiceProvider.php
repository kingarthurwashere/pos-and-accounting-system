<?php

namespace App\Providers;

use App\Events\AgentCreated;
use App\Events\CityCreated;
use App\Events\LocationBalanceUpdated;
use App\Events\OrderPaymentReceived;
use App\Events\RefundDisbursed;
use App\Events\RemittanceDisbursed;
use App\Events\RemittanceFloatDeposited;
use App\Events\WithdrawalRequestDisbursed;
use App\Listeners\CreateAgentBalance;
use App\Listeners\CreateCityBalance;
use App\Listeners\CreditLocationBalanceRemittance;
use App\Listeners\DebitLocationBalanceRefund;
use App\Listeners\DebitLocationBalanceRemittance;
use App\Listeners\DebitLocationBalanceWithdrawalRequest;
use App\Listeners\DebitOrderBalanceRefund;
use App\Listeners\UpdateCityBalance;
use App\Listeners\UpdateOrderBalance;
use App\Listeners\UpdatePaymentStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AgentCreated::class => [
            CreateAgentBalance::class,
        ],
        OrderPaymentReceived::class => [
            UpdateOrderBalance::class,
        ],
        CityCreated::class => [
            CreateCityBalance::class,
        ],
        WithdrawalRequestDisbursed::class => [
            DebitLocationBalanceWithdrawalRequest::class,
        ],
        LocationBalanceUpdated::class => [
            UpdateCityBalance::class,
        ],
        RefundDisbursed::class => [
            DebitLocationBalanceRefund::class,
            DebitOrderBalanceRefund::class,
        ],
        RemittanceDisbursed::class => [
            DebitLocationBalanceRemittance::class,
        ],
        RemittanceFloatDeposited::class => [
            CreditLocationBalanceRemittance::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
