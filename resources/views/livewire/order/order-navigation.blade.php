<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700 mb-4">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('order.show', $order->id) }}"
                wire:navigate
               class="{{ request()->routeIs('order.show') ? 'border-b-2 border-blue-600' : 'border-b-2 border-transparent' }} inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                Overview
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('order.invoices', $order->id) }}"
                wire:navigate
               class="{{ request()->routeIs('order.invoices') ? 'border-b-2 border-blue-600' : 'border-b-2 border-transparent' }} inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                Invoices
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('order.payments', $order->id) }}"
                wire:navigate
               class="{{ request()->routeIs('order.payments') ? 'border-b-2 border-blue-600' : 'border-b-2 border-transparent' }} inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                Payments
            </a>
        </li>
    </ul>    
</div>
