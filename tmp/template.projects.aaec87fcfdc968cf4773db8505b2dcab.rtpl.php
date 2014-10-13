<?php if(!class_exists('raintpl')){exit;}?><h2><?php echo $lang["projects"];?></h2>
<?php $counter1=-1; if( isset($projects) && is_array($projects) && sizeof($projects) ) foreach( $projects as $key1 => $value1 ){ $counter1++; ?>
<p class="project"><a href="http://localhost/issues2/project/<?php echo $key1;?>"><?php echo $value1["name"];?></a>: <?php echo $value1["description"];?></p>
<?php } ?>