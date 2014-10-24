<h2 class="left">{$issue.project|projectData:'short'}#{$issue.id} {$lang.issue}: {$issue.name}</h2>
<button class="right">{$lang.comment}</button>
<div class="clear"></div>
<div class="information">
	Author: {$issue.author|userData:'displayname'}
</div>

<p>{$issue.description}</p>