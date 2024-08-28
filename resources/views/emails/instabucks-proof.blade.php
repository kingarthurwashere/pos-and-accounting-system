@component('mail::message')
# Receipt for Your Transaction

Dear {{ $remittance->receiver_name }},

Thank you for choosing Instabucks! Below are the details of your recent transaction:

@component('mail::table')
| | |
|-----------------------|----------------------------------|
| **Reference**        | {{ $remittance->reference }}    |
| **Time of disbursement** | {{ $remittance->disbursed_at }} |
| **Total Disbursed**  | ${{ $remittance->formattedReceivable() }} |
| **Disbursed as**     | {{ $remittance->method->name }} |
| **Disbursed to**     | {{ $remittance->receiver_name }} |
@endcomponent  If you have any questions, reply to this email or contact us at
[support@dxbrunners.co.zw](mailto:support@dxbrunners.co.zw).


Best Regards,
Instabucks Team
@endcomponent
