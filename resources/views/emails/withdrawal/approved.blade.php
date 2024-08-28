@component('mail::message')
# Withdrawal Request Approved  
## {{ $withdrawalRequest->reference }}

Your withdrawal request has been approved.

@component('mail::table')
|                        |                                  |
| ---------------------- | -------------------------------- |
| **Requested Amount**   | USD {{ $withdrawalRequest->formattedAmount() }} |
| **Date Approved**      | {{ $withdrawalRequest->approval_datetime }}     |
| **Approved By**           | {{ $withdrawalRequest->approver->name }}       |
| **Type**               | {{ $withdrawalRequest->requestType->name }}    |
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
