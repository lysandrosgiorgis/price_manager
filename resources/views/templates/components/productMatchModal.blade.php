<div class="modal fade" id="productMatchModal" tabindex="-1" aria-labelledby="productMatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productMatchModalLabel">{{ __('Match Product') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('productMatchModalForm').submit();" >{{ __('Upload file') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function findProductMatch(id){
        const productMatchModal = document.getElementById('productMatchModal');
        productMatchModal.querySelector('.modal-body').innerHTML = '';
        $url = '{{ route('catalog.product.getMatchingProducts') }}?id=' + id;
        getAjax($url,function(status, response){
            if (status) {
                const results = JSON.parse(response);

                const productName = document.createElement('div');
                productName.innerHTML = `<h5>${results.name}</h5>`;
                productMatchModal.querySelector('.modal-body').appendChild(productName);
                const productImage = document.createElement('div');
                productImage.classList.add('mb-2');
                productImage.innerHTML = `<img src="${results.image}" class="img-fluid" width="200"/>`;
                productMatchModal.querySelector('.modal-body').appendChild(productImage);
                for(var index in results.matches){
                    const product = results.matches[index];
                    console.log(product);
                    const productMatch = document.createElement('div');
                    productMatch.classList.add('row');
                    productMatch.classList.add('mb-2');
                    productMatch.innerHTML = `
                        <div class="col-1">
                            <button type="button" class="btn btn-primary" onclick="connectCompanyProductToProduct('${id}', '${product.id}')" >
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        <div class="col-2">
                            <img src="${product.image}" class="img-thumbnail" />
                        </div>
                        <div class="col-9">
                            <h5>${product.name}</h5>
                        </div>
                    `;
                    productMatchModal.querySelector('.modal-body').appendChild(productMatch);
                }
                modalInstance = new Modal(productMatchModal);
                modalInstance.show();
            }
        });
    }

    function connectCompanyProductToProduct(companyProductId, productId){
        const productMatchModal = document.getElementById('productMatchModal');
        $url = '{{ route('catalog.product.connectCompanyProductToProduct') }}?company_product_id=' + companyProductId + '&product_id=' + productId;
        getAjax($url, function(status, response){
            if (status) {
                const results = JSON.parse(response);
                if(results.status){
                    productMatchModal.querySelector('.modal-body').innerHTML = '';
                    productMatchModal.querySelector('.modal-body').innerHTML = `<h5>${results.message}</h5>`;
                    setTimeout(function(){
                        modalInstance.hide();
                    }, 2000);
                }
            }
        });
    }
</script>
