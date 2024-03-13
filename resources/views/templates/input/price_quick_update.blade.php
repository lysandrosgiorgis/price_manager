<div class="price-input">
    <span>{{ $price_formatted }}</span>
    <div class="input-group">
        <input class="form-control" type="text" id="input-price-{{ $product_id }}"
               value="{{ $price }}" />
        <div class="input-group-append">
            <button class="btn btn-success" type="button" onclick="updateProductPrice({{ $product_id }});"><i
                    class="fa fa-save"></i></button>
            <button class="btn btn-danger" type="button"
                    onclick="$(this).parent().parent().parent().removeClass('edit');"><i class="fa fa-times"></i></button>
        </div>
    </div>
</div>
