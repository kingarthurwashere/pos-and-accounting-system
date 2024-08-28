@component('mail::message')
# New Payment Alert

A new payment has been successfully received at one of the terminals.

@component('mail::table')
|                        |                              |
| ---------------------- | ----------------------------------- |
| **Transaction Amount** | USD {{ $payment->formattedReceivedAmount() }} |
| **Date & Time Received** | {{ $payment->received_amount_datetime->format('m/d/Y H:i:s') }} |
| **Terminal ID**        | {{ $payment->id }}       |
| **Terminal Location**  | {{ $payment->location->city->name }} - {{ $payment->location->alias }} |
| **Payment Method**     | {{ $payment->paymentSource->name }}       |
| **Transaction Type**   | {{ $payment->payable->name }}               |
| **Customer Name**      | {{ $payment->order->customer_name ?? 'N/A' }}  |
| **Inv Number**   | #{{ $payment->invoice_id }}          |
@endcomponent

Please review the transaction details and ensure that all records are updated accordingly.

This is an automated message, please do not reply directly to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
