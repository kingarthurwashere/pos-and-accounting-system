@component('mail::message')
# Withdrawal Request Rejected  
## {{ $withdrawalRequest->reference }}

Your withdrawal request was rejected.

@component('mail::table')
|                        |                                  |
| ---------------------- | -------------------------------- |
| **Requested Amount**   | USD {{ $withdrawalRequest->formattedAmount() }} |
| **Date Rejected**      | {{ $withdrawalRequest->rejection_datetime }}    |
| **Rejected By**           | {{ $withdrawalRequest->rejecter->name }}       |
| **Rejection Notes**    | {{ $withdrawalRequest->rejection_message }}    |
@endcomponent

{{-- Uncomment below if you have a valid URL to view details --}}
{{-- @component('mail::button', ['url' => ''])
View Details
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
