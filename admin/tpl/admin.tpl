{include="header.inc"}
{if="isset($status)"}
<div class="status {$status.type}">
    {$lang.error_message[$status.language_key]}
</div>
{/if}
{include="template.$pagedata.template"}

{include="footer.inc"}