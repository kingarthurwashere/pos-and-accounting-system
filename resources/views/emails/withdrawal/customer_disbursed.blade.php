@component('mail::message')
# Requested Amount Disbursed  
## {{ $withdrawalRequest->reference }}

Your withdrawal request has been completed.

@component('mail::table')
|                        |                                  |
| ---------------------- | -------------------------------- |
| **Request Amount**     | USD {{ $withdrawalRequest->formattedAmount() }} |
| **Date Disbursed**     | {{ $withdrawalRequest->disburse_datetime }}     |
| **Approver**           | {{ $withdrawalRequest->approver->name }}       |
| **Disburser**          | {{ $withdrawalRequest->disburser->name }}      |
| **Disbursement Location** | {{ $withdrawalRequest->disbursementLocation->city->name }} - {{ $withdrawalRequest->disbursementLocation->alias }} |
| **Type**               | {{ $withdrawalRequest->requestType->name }}    |
| **Payout Method**      | {{ $withdrawalRequest->method->name }}         |
| **Amount Disbursed**   | USD {{ $withdrawalRequest->formattedAmount() }} |
@endcomponent

{{-- Uncomment below if you have a valid URL to view details --}}
{{-- @component('mail::button', ['url' => ''])
View Details
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
