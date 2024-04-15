<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" ></script>
<script src="{{ config('app.url') }}/vendor/laravel-filemanager/js/stand-alone-button.js" ></script>
<script>
    $(document).ready(function() {
        if ($('.filemanager-activator').length) {
            $('.filemanager-activator').filemanager('image', {multi: 'false', prefix: APP_URL + '/laravel-filemanager'});
        }
    });
</script>
