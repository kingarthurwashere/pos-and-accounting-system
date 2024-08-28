@component('mail::message')
# Receipt for Your Purchase

Dear {{ $payment->order->customer_name }},

Thank you for choosing Dxbrunners! Below are the details of your recent transaction:

@component('mail::table')
|                         |                                               |
|-------------------------|-----------------------------------------------|
| **Order No**            | #{{ $payment->order->order_id ?? $payment->order->id }}              |
| **Initiated By**        | {{ $payment->initialiser->name }}             |
| **Cashier**             | {{ $payment->cashier->name }}                 |
| **Transaction Amount**  | USD {{ $payment->formattedReceivedAmount() }} |
| **Balance Remaining**   | USD {{ $payment->order->formattedBalance() }} |
| **Date & Time Received**| {{ $payment->received_amount_datetime->format('m/d/Y H:i:s') }} |
| **Terminal**            | {{ $payment->location->city->name }} - {{ $payment->location->alias }} #{{ $payment->location->id }} |
| **Payment Method**      | {{ $payment->paymentSource->name }}           |
| **Transaction Type**    | {{ $payment->payable->name }}                 |
| **Customer Name**       | {{ $payment->order->customer_name ?? 'N/A' }} |
| **Inv Number**          | #{{ $payment->invoice_id }}                   |
@endcomponent

### Order Items Summary

<table style="width: 100%; border-collapse: collapse;">
  <tr>
    <th style="text-align: left; border-bottom: 1px solid #ccc;">Item Name</th>
    <th style="text-align: left; border-bottom: 1px solid #ccc;">Quantity</th>
    <th style="text-align: left; border-bottom: 1px solid #ccc;">Price Per Item</th>
    <th style="text-align: left; border-bottom: 1px solid #ccc;">Total Price</th>
  </tr>
  @foreach ($payment->order->items as $item)
  <tr>
    <td style="text-align: left; padding: 8px; border-bottom: 1px solid #eee;">{{ $item->name }}</td>
    <td style="text-align: left; padding: 8px; border-bottom: 1px solid #eee;">{{ $item->quantity }}</td>
    <td style="text-align: left; padding: 8px; border-bottom: 1px solid #eee;">USD {{ $item->formattedPrice() }}</td>
    <td style="text-align: left; padding: 8px; border-bottom: 1px solid #eee;">USD {{ $item->formattedTotal() }}</td>
  </tr>
  @endforeach
</table>

If you have any questions, reply to this email or contact us at [support@dxbrunners.co.zw](mailto:support@dxbrunners.co.zw).

Best Regards,  
Dxbrunners Team
@endcomponent
