{include="template.projects"}
<div class="clearfix"></div>
<hr />
<h3>{$lang.issues}</h3>
<hr />
{loop="latest_issues"}
<p class="entry full-width clickable" onclick="location.href='{$settings.base_url}/issue/{$key}'">
    <a href="{$settings.base_url}/project/{$value.project}">{$value.project|projectData:'short'}</a>-<a href="{$settings.base_url}/issue/{$key}">{$key} {$value.name}</a>
    {$value.description|substr:0,40}...
    {if="$settings.issue_labels == 'true'"}<label class="label" style="background-color: #{$value.label|labelData:'bgcolor'}; color: #{$value.label|labelData:'txtcolor'}">{$value.label|labelData:'label'}</label>{/if}
    {$value.author|userData:'displayname'}
</p>
{/loop}
{if="count($latest_issues) == 0"}
{$lang.no_issues}
{/if}