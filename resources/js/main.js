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
