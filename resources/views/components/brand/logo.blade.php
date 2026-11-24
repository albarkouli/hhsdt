@props([
    "variant" => "bilingual",
    "tone" => "light",
    "size" => "md",
    "href" => "/",
])

@php
$h = ["xs" => 24, "sm" => 32, "md" => 40, "lg" => 56, "xl" => 72][$size] ?? 40;
$w = $h;
$onDark = $tone === "dark";
$enColor = $onDark ? "text-[#f7f4ef]" : "text-[#f7f4ef]";
$arColor = "text-[#f7f4ef]";

// Build Arabic name programmatically — exact tatweel count, guaranteed
$brandEn = 'wesalkom';
$tatweel = "\u{0640}";
$brandAr = 'و' . 'ص' . str_repeat($tatweel, 12) . 'ا' . 'ل' . 'ك' . 'م';
$brandSep = ' | ';

$textSizes = [
    "xs" => ["en" => "text-[13px]", "ar" => "text-[12px]"],
    "sm" => ["en" => "text-[16px]", "ar" => "text-[14px]"],
    "md" => ["en" => "text-[20px]", "ar" => "text-[18px]"],
    "lg" => ["en" => "text-[28px]", "ar" => "text-[24px]"],
    "xl" => ["en" => "text-[36px]", "ar" => "text-[30px]"],
];
$ts = $textSizes[$size] ?? $textSizes["md"];
@endphp

@if($href === false)
<div {{ $attributes->merge(["class" => "inline-flex items-center gap-3", "dir" => "ltr"]) }}>
@else
<a href="{{ $href }}" {{ $attributes->merge(["class" => "inline-flex items-center gap-3 group", "dir" => "ltr"]) }}>
@endif

    <img src="/brand/symbol-gold.svg" alt="wesalkom"
         width="{{ $w }}" height="{{ $h }}"
         class="shrink-0" />

    @if($variant === "bilingual")
        <span class="flex items-center leading-none" style="font-family:'Readex Pro','Manrope','Alexandria',sans-serif" dir="ltr">
            <span class="font-bold {{ $ts['en'] }} {{ $enColor }} lowercase" style="letter-spacing:-0.01em;">{{ $brandEn }}</span>
            <span class="{{ $ts['en'] }} text-[#344453] mx-1.5 font-light">{{ $brandSep }}</span>
            <span class="font-bold {{ $ts['ar'] }} {{ $arColor }}" dir="rtl">{{ $brandAr }}</span>
        </span>
    @elseif($variant === "en")
        <span class="font-bold {{ $ts['en'] }} {{ $enColor }} lowercase" style="font-family:'Readex Pro',sans-serif;letter-spacing:-0.01em;">{{ $brandEn }}</span>
    @elseif($variant === "ar")
        <span class="font-bold {{ $ts['en'] }} {{ $arColor }}" style="font-family:'Readex Pro','Alexandria',sans-serif;" dir="rtl">{{ $brandAr }}</span>
    @endif

@if($href === false)
</div>
@else
</a>
@endif