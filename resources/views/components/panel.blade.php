@props(['title', 'icon' => 'tech', 'iconImage' => null, 'iconBare' => false, 'class' => ''])
<section {{ $attributes->merge(['class' => "panel $class"]) }}>
    @if ($title)
        <h2 class="section-title">
            <span @class(['icon-bare' => $iconBare]) aria-hidden="true">
                @if ($iconImage)
                    <img class="section-icon-image" src="{{ asset($iconImage) }}" alt="">
                @else
                    <x-icon :name="$icon" size="15" />
                @endif
            </span>
            {{ $title }}
        </h2>
    @endif
    {{ $slot }}
</section>
