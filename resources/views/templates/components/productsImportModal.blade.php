<div class="modal fade" id="productsImportModal" tabindex="-1" aria-labelledby="productsImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productsImportModalLabel">{{ __('Import Products') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('catalog.product.import') }}"
                      id="productsImportModalForm"
                      method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="productsImportModalFile" class="form-label">{{ __('Import file') }}</label>
                        <input class="form-control" type="file" id="productsImportModalFile" name="import" />
                    </div>
                    <div class="mb-3">
                        <span class="form-label">{{ __('Competitors') }}</span>
                        @foreach($importProducts['companies'] as $company)
                            <div class="form-check form-switch">
                                <input name="company[]"
                                       id="competitorCompany-{{ $company->id }}"
                                       value="{{ $company->id }}"
                                       class="form-check-input"
                                       checked="checked"
                                       type="checkbox" />
                                <label class="form-check-label" for="competitorCompany-{{ $company->id }}">{{ $company->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('productsImportModalForm').submit();" >{{ __('Upload file') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function showImportProducts(){
        const productsImportModal = document.getElementById('productsImportModal');
        modalInstance = new Modal(productsImportModal);
        modalInstance.show();
    }
</script>
