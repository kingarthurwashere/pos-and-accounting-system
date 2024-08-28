@props(['stat', 'label', 'icon' => 'arrow-right', 'iconColor' => 'green-600' ])
<article
  class="flex  shadow-xl items-center gap-4 rounded-lg border border-gray-100 dark:border-gray-800 bg-white dark:bg-slate-900 p-6 sm:justify-between"
>
    <span class="rounded-full p-3 text-{{$iconColor}} sm:order-last">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-6 h-6"/>
    </span>

  <div>
    <p class="text-2xl font-bold font-medium text-gray-900 dark:text-gray-300">{{$stat}}</p>

    <p class="text-sm text-gray-500">{{$label}}</p>
  </div>
</article>