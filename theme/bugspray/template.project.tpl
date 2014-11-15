<a href="{if="LOGGED_IN"}../newissue/{$project.id}{else}../login{/if}" class="btn btn-primary pull-right">{$lang.new_issue}</a>
        
<div class="pull-left half-width fixed">
    <div class="pic pull-left">
        <img class="pull-left padded" width=100 src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $project.id . '.png')"}{$project.id}{else}default{/if}.png" />
    </div>
    <div class="pull-left left-margin">
        <h3 class="no-spacing">{$lang.project}: {$project.name}</h3>
        {$project.description}<br /><br />
        {$lang.lead}: {$project.project_lead|userData:'displayname'}
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<h2>{$lang.issues}</h2>
<hr />
{loop="latest_issues"}
<p class="issue">
    {$value.project|projectData:'short'}#{$key} <a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
</p>
{/loop}
{if="count($latest_issues) == 0"}{$lang.no_issues}{/if}