<div class="col-12 @isset($input['wide']) col-md-12 @endif mb-3 {{ isset($input['class']) ? $input['class'] : '' }}">
    <label class="label" for="{{ $input['id'] }}">
        {{ $input['label'] }}
    </label>
    <input type="text"
           class="form-control"
           @if(!isset($input['disabled']) || $input['disabled'] == 0)name="{{ $input['name'] }}" @endif
           value="{{ $input['value'] }}"
           @if(isset($input['disabled']) && $input['disabled'] == 1)disabled="disabled" @endif
           id="{{ $input['id'] }}" />
    @if(isset($input['error']) && $input['error'])
        <div class="text-danger">{{ $form['error'] }}</div>
    @endif
    @if(isset($input['disabled']) && $input['disabled'] == 1)
        <input type="hidden" class="form-control"
               name="{{ $input['name'] }}"
               value="{{ $input['value'] }}" />
    @endif
</div>
