@props(["tone" => "gold", "size" => 40])

@php
$map = ["gold" => "symbol.svg", "ivory" => "symbol.svg", "navy" => "symbol-navy.svg"];
$file = $map[$tone] ?? "symbol.svg";
@endphp

<img src="/brand/{{ $file }}" alt="WESALKOM"
     width="{{ $size }}" height="{{ $size }}"
     class="shrink-0" {{ $attributes }} />