window.getAjax = function (url, callback) {
    var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open('GET', url);
    request.onreadystatechange = function() {
        if (request.readyState>3 && request.status==200){
            callback(1, request.responseText);
        }
    };
    request.onerror = function() {
        callback(0, request.responseText);
    };
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.send();
    return request;
}

window.postAjax = function (url, data, callback) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
        function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
    ).join('&');

    var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    request.open('POST', url);
    request.onreadystatechange = function() {
        if (request.readyState>3 && request.status==200) {
            callback(1, request.responseText);
        }
    };
    request.onerror = function() {
        callback(0, request.responseText);
    };
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(params);
    return request;
}

window.autocomplete = function (autocompleteSource, autocompleteTarget) {
    var currentOption;
    var autocompleteListsCount = document.getElementsByClassName('autocomplete-input').length;

    autocompleteSource.parentNode.style.position = "relative";
    autocompleteSource.classList.add('autocomplete-input');
    autocompleteSource.setAttribute('data-autocomplete-list-id', "autocomplete-list-" + (autocompleteListsCount + 1));
    autocompleteSource.setAttribute('autocomplete', "off");

    autocompleteSource.addEventListener("input", function(e) {
        var $this = this;
        var string_to_match = $this.value.toLowerCase();
        var autocomplete_list_id = $this.getAttribute('data-autocomplete-list-id');
        var url = $this.getAttribute('data-source');
        url = url + (url.indexOf('?') > -1 ? '&' : '?') + 'name=' + string_to_match;
        removeLists();

        if (string_to_match.length > 1) {
            getAjax(url, function(status, response) {
                if (status) {
                    currentOption = -1;
                    var autocomplete_list = document.createElement("DIV");
                    autocomplete_list.setAttribute("id", autocomplete_list_id);
                    autocomplete_list.setAttribute("class", "autocomplete-list shadow border");
                    autocomplete_list.style.marginTop = "5px";
                    autocomplete_list.style.maxHeight = "300px";
                    autocomplete_list.style.overflowY = "auto";

                    var autocomplete_items = document.createElement("DIV");
                    autocomplete_items.setAttribute("class", "list-group list-group-flush autocomplete-items");
                    response = JSON.parse(response);
                    for(var index in response) {
                        const item = response[index];

                        var autocomplete_item = document.createElement("DIV");
                        autocomplete_item.setAttribute('class', 'list-group-item list-group-item-action');
                        autocomplete_item.setAttribute("data-value", item.value);
                        autocomplete_item.innerHTML = item.label;

                        autocomplete_item.addEventListener("click", function(e) {
                            autocompleteSource.value = this.innerHTML;
                            autocompleteTarget.value = this.getAttribute("data-value");
                            autocomplete_items.remove();
                        });
                        autocomplete_items.appendChild(autocomplete_item);
                    }
                    autocomplete_list.appendChild(autocomplete_items);
                    $this.parentNode.appendChild(autocomplete_list);
                }
            });

        }
    });

    autocompleteSource.addEventListener("keydown", function(e) {
        var autocomplete_list_id = this.getAttribute('data-autocomplete-list-id');
        var autocomplete_list = document.getElementById(autocomplete_list_id);

        if ( autocomplete_list ) {
            var autocomplete_items = autocomplete_list.querySelector('.autocomplete-items').getElementsByTagName("div");

            if (e.keyCode == 40) {
                currentOption++;
                selectItem(autocomplete_items);
            } else if (e.keyCode == 38) {
                currentOption--;
                selectItem(autocomplete_items);
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentOption > -1) {
                    if (autocomplete_items) {
                        autocomplete_items[currentOption].click();
                    }
                }
            }
        }
    });

    function selectItem(autocomplete_items) {
        if (autocomplete_items) {
            Array.from(autocomplete_items).forEach(autocomplete_item => {
                autocomplete_item.classList.remove("active");
            });

            currentOption = currentOption >= autocomplete_items.length ? 0 : currentOption;
            currentOption = currentOption < 0 ? autocomplete_items.length - 1 : currentOption;

            autocomplete_items[currentOption].classList.add("active");
        }
    }

    function removeLists(element) {
        var autocomplete_lists = document.getElementsByClassName("autocomplete-list");
        Array.from(autocomplete_lists).forEach(autocomplete_list => {
            if (element != autocomplete_list && element != autocompleteSource) {
                autocomplete_list.remove();
            }
        });
    }

    document.addEventListener("click", function(e) {
        removeLists(e.target);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    $('.price-input span')
        .dblclick(function () {
            $(this).parent().addClass('edit');
        });
});
