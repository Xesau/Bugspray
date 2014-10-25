<div class="alert alert-danger">
    <b>{$lang.error}</b>: {$lang.page_not_found}<br />
    <a onclick="$('#info').removeClass('hidden'); $(this).remove(); return false;" href="#info">Show template info</a>
    <div id="info" class="hidden">
        {$template_info}
    </div>
</div>