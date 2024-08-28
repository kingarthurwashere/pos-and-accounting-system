<div class="inline-flex rounded-md shadow-sm" role="group">
    <span>
        @if ($order->balance == '0')
            <x-mary-badge value="PAID" class="badge-success font-bold" />
        @endif

        @if ($order->balance === $order->total)
            <x-mary-badge value="NOT PAID" class="bg-red-800 font-bold" />
        @endif

        @if ($order->balance > 0 && $order->balance < $order->total)
            <x-mary-badge value="PARTIALLY PAID" class="badge-info font-bold" />
        @endif
    </span>
</div>