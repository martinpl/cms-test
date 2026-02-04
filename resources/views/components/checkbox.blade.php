@php
    $baseStyle = 'peer border-input dark:bg-input/30 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground dark:data-[state=checked]:bg-primary 
        data-[state=checked]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 
        aria-invalid:border-destructive size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed 
        disabled:opacity-50';
    $checkedStyle = 'checked:bg-primary dark:checked:bg-primary checked:border-primary';
@endphp

<div class="flex">
    <div class="relative inline-flex">
        <input type="checkbox" {{ $attributes->class([$baseStyle, $checkedStyle, 'appearance-none']) }} role="checkbox">
        <span
            class="text-transparent peer-checked:text-primary-foreground pointer-events-none grid place-content-center transition-none absolute inset-0">
            <x-icon name="check" class="size-3.5" />
        </span>
    </div>
</div>
