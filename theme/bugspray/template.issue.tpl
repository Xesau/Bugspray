<div class="entry full-width">
    <div class="btn-group pull-right phone-nofloat phone-space bottom">
        {if="hasPermission(USERID, 'bs_topic')"}
        {if="$issue.status == 'open'"}<button class="btn btn-danger">{$lang.close} <i class="glyphicon glyphicon-remove"></i></button>{else}
        <button class="btn btn-success">{$lang.open} <i class="glyphicon glyphicon-ok"></i></button>
        {/if}
        {/if}
        <button class="btn btn-primary">{$lang.edit} <i class="glyphicon glyphicon-pencil"></i></button>
    </div>
    <h3 class="no-spacing"><a href="{$settings.base_url}/project/{$issue.project}">{$issue.project|projectData:'short'}</a>-{$issue.id} {$issue.name}</h3>
    <div class="clearfix"></div>
    <b>{$lang.author}</b>: {$issue.author|userData:'displayname'}<br />
    {if="$settings.issue_labels == 'true'"}<label class="label" style="background-color: #{$issue.label|labelData:'bgcolor'}; color: #{$issue.label|labelData:'txtcolor'}">{$issue.label|labelData:'label'}</label>{/if}
</div>
<div class="entry full-width">
    {$issue.description}
</div>
<div class="entry full-width">
    <form method="post" action="{$settings.base_url}/send/comment/{$issue.id}">
        <textarea name="message" id="message" class="form-control full-width space bottom"></textarea>
        <button class="pull-right btn btn-primary">{$lang.save}</button>
    </form>
</div>
<b>{$lang.comments}</b>
{loop="$comments"}
<div class="entry">
</div>
{/loop}
{if="count($comments)==0"}<br />{$lang.no_comments}{/if}