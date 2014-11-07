<h2 class="no-spacing pull-left">{$issue.project|projectData:'short'}#{$issue.id} {$lang.issue}: {$issue.name}</h2>
<button class="pull-right">{$lang.comment}</button>
<div class="clear"></div>
<div class="information">
	Author: {$issue.author|userData:'displayname'}
</div>

<p>{$issue.description}</p>