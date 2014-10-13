<?php if(!class_exists('raintpl')){exit;}?><h2 class="left"><?php echo $lang["issue"];?>: <?php echo $issue["name"];?></h2>
<button class="right"><?php echo $lang["comment"];?></button>
<div class="clear"></div>
<div class="information">
	Author: <?php echo $issue["author"];?>
</div>

<p><?php echo $issue["description"];?></p>