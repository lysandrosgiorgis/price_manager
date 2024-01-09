window.changeUrlStatus = function (urlId, newStatus, btn, container = null){
    let icon = btn.querySelector('.fa');
    icon.classList = 'fa fa-spin fa-circle-notch';
    icon.setAttribute('class', 'fa fa-spin fa-circle-notch');
    getAjax(
        APP_URL+'/competition/product-url/update-status/'+urlId+'?status='+newStatus,
        function(status, response){
            if(status){
                icon.setAttribute('class', 'fa fa-check');
                if(container !== null){
                    container.remove();
                }
            }else{
                btn.classList.remove('btn-danger').remove('btn-success').add('btn-warning');
                icon.setAttribute('class', 'fa fa-times');
            }
        }
    );
}

window.massChangeUrlStatus = function (productId, newStatus, btn){
    let icon = btn.querySelector('.fa');
    icon.classList = 'fa fa-spin fa-circle-notch';
    icon.setAttribute('class', 'fa fa-spin fa-circle-notch');
    getAjax(
        APP_URL+'/competition/product-url/mass-update-status/'+productId+'?status='+newStatus,
        function(status, response){
            if(status){
                icon.setAttribute('class', 'fa fa-check');
            }else{
                btn.classList.remove('btn-danger').remove('btn-success').add('btn-warning');
                icon.setAttribute('class', 'fa fa-times');
            }
        }
    );
}

window.acceptProductUrl = function (urlId, btn, container = null){
    let icon = btn.querySelector('.fa');
    icon.classList = 'fa fa-spin fa-circle-notch';
    getAjax(
        APP_URL+'/competition/product-url/accept/'+urlId,
        function(){
            icon.classList = 'fa fa-check';
        }
    );
}
