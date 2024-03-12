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
        @if (isset($visibleColumns))
            <button class="btn btn-secondary dropdown-toggle mb-1 ms-1" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fa fa-columns"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                @foreach($columns as $columnIndex => $column)
                    <a class="dropdown-item">
                        <div class="form-check">
                            <input class="form-check-input toggle_column-checkbox" type="checkbox"
                                   value="{{ $columnIndex }}" id="column-{{ $columnIndex }}"
                                   data-list-id=""
                                   data-column-id="{{ $columnIndex }}"
                                   @if (isset($columnIndex) && in_array($columnIndex, $visibleColumns)) checked @endif>
                            <label class="form-check-label" for="column-{{ $columnIndex }}">
                                {{ $column['label'] }}
                            </label>
                        </div>
                    </a>
                @endforeach
            </ul>
        @endif
        @if (isset($filters))
            <button class="btn border mb-1 ms-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#filters" aria-controls="filters">
                <span class="fa fa-filter"></span>
                <span class="">Filters</span>
            </button>
        @endif
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
{{--                            {{ $listItem[$columnIndex]  }}--}}
                            {!! $listItem[$columnIndex] !!}
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
@if (isset($filters))
<div class="offcanvas offcanvas-end bg-white" tabindex="-1" id="filters" aria-labelledby="filtersLabel" style="visibility: visible;" aria-modal="true" role="dialog">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title border-bottom" id="filtersLabel">Filters</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @foreach ($filters as $filter)
            <div
                class="form-group list-column">
                <label for="filter_{{ $filter['name'] }}" class="mb-0">
                    {{ $filter['label'] }}</label>
                @switch($filter['type'])
                    @case('text')
                        <input name="{{ $filter['name'] }}" value="{{ $filter['value'] }}"
                               placeholder="{{ $filter['label'] }}" id="filter_{{ $filter['name'] }}"
                               type="text" class="form-control mb-3" />
                    @break
                    @case('select')
                        <select name="{{ $filter['name'] }}" id="filter_{{ $filter['name'] }}"
                                class="form-control mb-3">
                            @foreach ($filter['options'] as $option_label => $option_value)
                                <option value="{{ $option_value }}"
                                    {{ request($filter['name']) == $option_value ? 'selected' : '' }}>
                                    {{ $option_label }}
                                </option>
                            @endforeach
                        </select>
                    @break
                @endswitch
            </div>
        @endforeach
    </div>
    <div class="offcanvas-footer p-2">
        <button type="button" onclick="filter_list();" class="btn-outline-primary btn btn mb-2 w-100">
            <i class="fas fa-filter" aria-hidden="true"></i>
            Filter List
        </button>
        <button type="button" onclick="filters_remove();" class="btn btn-outline-danger btn mb-2 w-100">
            <i class="fas fa-remove" aria-hidden="true"></i>
            Remove filters
        </button>
    </div>
</div>
@endif
@if (isset($filters))
    <script>
        function filter_list() {
            let url = [];
            @foreach ($filters as $filter)
                const {{ $filter['name'] }} = document.getElementById('filter_{{ $filter['name'] }}').value;
                if ({{ $filter['name'] }} && {{ $filter['name'] }} !== '') {
                    url.push('{{ $filter['name'] }}=' + encodeURIComponent({{ $filter['name'] }}));
                }
            @endforeach

            if (url.length > 0) {
                location = '{{ route($filters_form) }}?' + url.join('&');
            } else {
                location = '{{ route($filters_form) }}';
            }
        }
    </script>
    <script>
        function filters_remove() {
            let url = '{{ route($filters_form) }}';
            location = url;
        }
    </script>
@endif
@if (isset($visibleColumns))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle_column-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', async function() {
                var columns = document.querySelectorAll('.column-' + checkbox.value);
                for (var column of columns) {
                    column.classList.toggle('d-none');
                }

            });
        });
    });
</script>
@endif
