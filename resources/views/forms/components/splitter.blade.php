@php
    $label = $getLabel();
    $isDotted = $isDotted();
@endphp
<div class="relative flex items-center">
    <div @class($borderClasses = [
        'flex-grow border-gray-200 dark:border-gray-700 border-t',
        'border-dotted' => $isDotted,
    ])></div>
    @if(filled($label))
        <span class="flex-shrink px-2 text-gray-500 text-sm">{{ $label }}</span>
        <div @class($borderClasses)></div>
    @endif
</div>