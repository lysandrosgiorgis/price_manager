@isset($buttons['top'])
    <div class="text-end mb-1">
        @foreach($buttons['top'] as $button)
            @if($button['type'] == 'link')
                <a href="{{ $button['href'] }}" class="{{ $button['class'] }} btn mb-1 ms-1"  data-bs-toggle="tooltip" title="{{ $button['label'] }}">
                    @if($button['icon'])<span class="{{ $button['icon'] }}" aria-hidden="true"></span>@endif
                    <span class="@if(isset($button['hideLabel'])) visually-hidden @else d-none d-xl-inline @endif ">{{ $button['label'] }}</span>
                </a>
            @endif
            @if($button['type'] == 'button')
                <button type="button"  onclick="{{ $button['action'] }}" class="{{ $button['class'] }} btn mb-1 ms-1"  data-bs-toggle="tooltip" title="{{ $button['label'] }}">
                    @if($button['icon'])<span class="{{ $button['icon'] }}" aria-hidden="true"></span>@endif
                    <span class="@if(isset($button['hideLabel'])) visually-hidden @else d-none d-xl-inline @endif ">{{ $button['label'] }}</span>
                </button>
            @endif
        @endforeach
    </div>
@endisset
<div class="table-responsive list-table position-relative">
<table class="table table-hover">
    <thead>
        <tr>
            @foreach($columns as $columnIndex => $column)
                <th class="{{ isset($column['class']) ? $column['class'] : '' }} column-{{ $columnIndex }}" @isset($column['width'])width="{{ $column['width'] }}"@endisset>
                    <span class="{{ isset($column['hideLabel']) ? 'visually-hidden' : '' }}">{{ $column['label'] }}</span>
                </th>
            @endforeach
            @isset($listActions)
                <th @isset($listActions['width'])width="{{ $listActions['width'] }}"@endisset>{{ __('Actions') }}</th>
            @endisset
        </tr>
    </thead>
    <tbody>
        @foreach($list_items as $itemIndex => $listItem)
            <tr class="{{ isset($listItem['class']) ? $listItem['class'] : '' }}">
                @foreach($columns as $columnIndex => $column)
                    <td class="{{ isset($column['class']) ? $column['class'] : '' }} column-{{ $columnIndex }}" @isset($column['width']) width="{{ $column['width'] }}" @endisset>
                        {{ $listItem[$columnIndex]  }}
                    </td>
                @endforeach
                @isset($listItem['actions'])
                    <td class="columnActions text-end" >
                        @foreach($listItem['actions'] as $actionIndex => $action)
                            @if($action['type'] == 'link')
                                <a href="{{ $action['href'] }}"
                                   title="{{ $action['label'] }}"
                                   class="{{ $action['class'] }}"
                                   title="{{ $action['label'] }}"
                                   @isset($action['tooltip'])data-bs-toggle="tooltip" data-bs-title="{{ $action['label'] }}"@endisset
                                >
                                    @isset($action['icon'])
                                        <span class="{{ $action['icon'] }}" aria-hidden="true" ></span>
                                    @endisset
                                    <span class="{{ isset($action['hideLabel']) ? 'visually-hidden' : '' }}">{{ $action['label'] }}</span>
                                </a>
                            @endif
                            @if($action['type'] == 'button')
                                <button onclick="{{ $action['action'] }}"
                                   title="{{ $action['label'] }}"
                                   class="{{ $action['class'] }}"
                                   title="{{ $action['label'] }}"
                                   @isset($action['tooltip'])data-bs-toggle="tooltip" data-bs-title="{{ $action['label'] }}"@endisset
                                >
                                    @isset($action['icon'])
                                        <span class="{{ $action['icon'] }}" aria-hidden="true" ></span>
                                    @endisset
                                    <span class="{{ isset($action['hideLabel']) ? 'visually-hidden' : '' }}">{{ $action['label'] }}</span>
                                </button>
                            @endif
                        @endforeach
                    </td>
                @endisset
            </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $pagination->links() }}
