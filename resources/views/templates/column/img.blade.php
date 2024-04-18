
@isset($img)
    <div class="object-fit-contain ratio @isset($ratio){{ $ratio }}@else ratio-1x1 @endif bg-white">
        <img src="{{ $img }}" class="img-fluid object-fit-contain p-2" width="{{ $width ?? 200 }}" />
    </div>
@endisset
