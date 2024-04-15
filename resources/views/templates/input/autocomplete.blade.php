<div class="col-12 @isset($input['wide']) col-md-12 @endif mb-3 {{ isset($input['class']) ? $input['class'] : '' }}">
    <label class="label" for="{{ $input['id'] }}">
        {{ $input['label'] }}
    </label>
    <div>
        <input type="text"
               class="form-control"
               name="{{ $input['autocomplete_name'] }}"
               value="{{ $input['autocomplete_value'] }}"
               data-source="{{ $input['source'] }}"
               @if(isset($input['disabled']) && $input['disabled'] == 1)disabled="disabled" @endif
               id="{{ $input['id'] }}-source" />
        <input type="hidden"
               class="form-control"
               name="{{ $input['name'] }}"
               value="{{ $input['value'] }}"
               id="{{ $input['id'] }}-target" />
    </div>
    @if(isset($input['error']) && $input['error'])
        <div class="text-danger">{{ $form['error'] }}</div>
    @endif
</div>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        autocomplete(document.getElementById("{{ $input['id'] }}-source"), document.getElementById("{{ $input['id'] }}-target"));
    });
</script>
