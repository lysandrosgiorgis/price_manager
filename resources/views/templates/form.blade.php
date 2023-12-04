<form method="post" action="{{ $form['action'] }}" enctype="multipart/form-data" id="{{ $form['id'] }}" class="form-horizontal">
    {{ csrf_field() }}
    @if($form['tabs'])
    <ul class="nav nav-tabs @if(count($form['tabs']) == 1) d-none @endif" id="{{ $form['id'] }}Tabs" role="tablist">
        @foreach($form['tabs'] as $tabIndex => $tab)
            <li class="nav-item " role="presentation">
                <button class="nav-link @isset($tab['active']) active @endisset"
                        id="{{ $form['id'] }}-{{ $tabIndex }}Btn"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $form['id'] }}-{{ $tabIndex }}"
                        type="button"
                        role="tab" aria-controls="{{ $form['id'] }}-{{ $tabIndex }}"
                        @isset($tab['active'])aria-selected="true"@endisset>{{ $tab['label'] }}</button>
            </li>
        @endforeach
    </ul>
    @endif
    <div class="tab-content">
        @foreach($form['tabs'] as $tabIndex => $tab)
            <div class="tab-pane @isset($tab['active'])active @endisset" id="{{ $form['id'] }}-{{ $tabIndex }}" role="tabpanel" aria-labelledby="{{ $form['id'] }}-{{ $tabIndex }}Btn">
                @isset($tab['fieldsets'])
                    @foreach($tab['fieldsets'] as $fieldsetIndex => $fieldset)
                        <fieldset id="{{ $form['id'] }}-{{ $tabIndex }}-{{ $fieldsetIndex }}" class="py-2 {{ isset($fieldset['class']) ? $fieldset['class'] : '' }}">
                            @isset($fieldset['legend'])
                            <legend>{{ $fieldset['legend'] }}</legend>
                            @endisset
                            @isset($fieldset['fields'])
                                <div class="row row-cols-md-2">
                                    @foreach($fieldset['fields'] as $fieldIndex => $field)
                                        @php
                                            $input = $field;
                                            $input['id'] = $form['id'].'-'.$tabIndex.'-'.$fieldsetIndex.'-'.$fieldIndex;
                                        @endphp
                                        @include('templates.input.'.$input['type'], $input)
                                    @endforeach
                                </div>
                            @endisset
                        </fieldset>
                    @endforeach
                @endisset
                @isset($tab['content']){{ $tab['content'] }}@endisset
            </div>
        @endforeach
    </div>
    <input type="hidden" name="saveAndEdit" id="saveAndEdit" value="0" />
</form>
@isset($form['buttons']['bottom'])
<div class="text-end">
    @foreach($form['buttons']['bottom'] as $button)
        @if($button['type'] == 'link')
            <a href="{{ $button['href'] }}" class="{{ $button['class'] }} btn mb-2 mb-md-0 ms-1"  data-bs-toggle="tooltip" title="{{ $button['label'] }}">
                @if($button['icon'])<span class="{{ $button['icon'] }}" aria-hidden="true"></span>@endif
                <span class="@if(isset($button['hideLabel'])) visually-hidden @else d-none d-xl-inline @endif ">{{ $button['label'] }}</span>
            </a>
        @endif
        @if($button['type'] == 'button')
            <button type="button"  onclick="{{ $button['action'] }}" class="{{ $button['class'] }} btn mb-2 mb-md-0 ms-1"  data-bs-toggle="tooltip" title="{{ $button['label'] }}">
                @if($button['icon'])<span class="{{ $button['icon'] }}" aria-hidden="true"></span>@endif
                <span class="@if(isset($button['hideLabel'])) visually-hidden @else d-none d-xl-inline @endif ">{{ $button['label'] }}</span>
            </button>
        @endif
    @endforeach
</div>
@endisset
