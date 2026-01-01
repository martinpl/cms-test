{{-- TODO: push hack, animation, clean out --}}

<div data-slot="select" style="anchor-scope: all">
    {{ $slot }}
    <select
        onchange="
            const trigger = this.closest('[data-slot=select]').querySelector('[data-slot=select-trigger]');
            trigger.removeAttribute('data-placeholder');
            trigger.querySelector('[data-slot=select-value]').textContent = this.options[this.selectedIndex].text;
    "
        hidden>
        @stack('select')
        @php
            $ref = new ReflectionClass($__env);
            $prop = $ref->getProperty('pushes');
            $prop->setAccessible(true);
            // clear one
            $pushes = $prop->getValue($__env);
            $pushes['select'] = [];
            $prop->setValue($__env, $pushes);
        @endphp
    </select>
</div>
