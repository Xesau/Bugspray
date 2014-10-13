<?php if(!class_exists('raintpl')){exit;}?><h2 class="left"><?php echo $lang["project"];?>: <?php echo $project["name"];?></h2>
<a href="<?php if( $loggedIn ){ ?>newissue/<?php echo $project["id"];?><?php }else{ ?>../login<?php } ?>" class="button right"><?php echo $lang["new_issue"];?></a>
<div class="clear"></div>
<div class="description">
<?php echo $project["description"];?>
</div>
<?php $counter1=-1; if( isset($latest_issues) && is_array($latest_issues) && sizeof($latest_issues) ) foreach( $latest_issues as $key1 => $value1 ){ $counter1++; ?>
	<p class="issue">
		<a href="http://localhost/issues2/issue/<?php echo $key1;?>"><?php echo $value1["name"];?></a> <small>(<?php echo ( substr( $value1["description"], 0,30 ) );?>...)</small>
	</p>
<?php } ?>