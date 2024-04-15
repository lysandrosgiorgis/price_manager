
@isset($img)
    <div class="object-fit-contain ratio ratio-1x1 bg-white">
        <img src="{{ $img }}" class="img-fluid object-fit-contain p-2" width="{{ $width ?? 200 }}" />
    </div>
@endisset
