<div class="col-12 @isset($input['wide']) col-md-12 @endif mb-3 {{ isset($input['class']) ? $input['class'] : '' }}">
    <label class="label" for="{{ $input['id'] }}">
        {{ $input['label'] }}
    </label>
    <select class="form-select"
           @if(!isset($input['disabled']) || $input['disabled'] == 0)name="{{ $input['name'] }}" @endif
           @if(isset($input['disabled']) && $input['disabled'] == 1)disabled="disabled" @endif
           id="{{ $input['id'] }}" >
        @foreach($input['options'] as $option)
            <option value="{{ $option['value'] }}" @if($input['value'] == $option['value']) selected="selected" @endif >{{ $option['label'] }}</option>
        @endforeach
    </select>
    @if(isset($input['error']) && $input['error'])
        <div class="text-danger">{{ $form['error'] }}</div>
    @endif
    @if(isset($input['disabled']) && $input['disabled'] == 1)
        <input type="hidden" class="form-control"
               name="{{ $input['name'] }}"
               value="{{ $input['value'] }}" />
    @endif
</div>
