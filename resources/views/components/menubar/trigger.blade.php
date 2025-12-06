@php
    
$tag = $attributes->buttonTag();
$base = "focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground flex items-center rounded-sm px-2 py-1 text-sm font-medium outline-hidden select-none";
$hover = "hover:bg-accent hover:text-accent-foreground";

@endphp

<{{ $tag }} {{ $attributes->class("{$base} {$hover}") }} style="anchor-name: --menubar">
    {{ $slot }}
</{{ $tag }}>