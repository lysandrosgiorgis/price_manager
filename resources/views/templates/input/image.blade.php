<div class="col-12 @isset($input['wide']) col-md-12 @endif mb-3 {{ isset($input['class']) ? $input['class'] : '' }}">
    <label class="label" for="{{ $input['id'] }}">
        {{ $input['label'] }}
    </label>
    <div>
        <div class="filemanager-activator d-inline-block border p-2 bg-white cursor-pointer"
             data-input="{{ $input['id'] }}"
             data-preview="{{ $input['id'] }}Preview"
        >
            <div id="{{ $input['id'] }}Preview" class="filemanager-preview-image">
                <img src="{{ asset(old($input['name'], $input['value'] ? $input['value'] : 'https://place-hold.it/100')) }}"
                     class="img-fluid " />
            </div>
            <input type="hidden"
                   class="form-control"
                   @if(!isset($input['disabled']) || $input['disabled'] == 0)name="{{ $input['name'] }}" @endif
                   value="{{ $input['value'] }}"
                   @if(isset($input['disabled']) && $input['disabled'] == 1)disabled="disabled" @endif
                   id="{{ $input['id'] }}" />
        </div>
    </div>

    @if(isset($input['error']) && $input['error'])
        <div class="text-danger">{{ $form['error'] }}</div>
    @endif
</div>
