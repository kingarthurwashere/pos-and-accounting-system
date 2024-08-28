@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'MikhmanPOS')
    <img src="{{ asset('images/dxb_logo.png') }}" style="width: 120px; height: auto" class="logo" alt="POS Logo">
@else
    {{ $slot }}
@endif
</a>
</td>
</tr>
