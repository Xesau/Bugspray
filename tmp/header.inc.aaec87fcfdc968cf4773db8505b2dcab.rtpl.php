<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="http://localhost/issues2/theme/bugspray/./style.css" />
		<link rel="stylesheet" href="http://localhost/issues2/theme/bugspray/./font-awesome.min.css" />
		<title><?php echo strip_tags( $settings["site_name"] );?> &bull; <?php echo strip_tags( $pagedata["title"] );?></title>
	</head>
	<body>
		<header>
			<div class="middle">
				<h2><?php echo $settings["site_name"];?></h2> 
 				<nav>
					<ul>
						<li><a href="http://localhost/issues2/"><i class="fa fa-fw fa-home"></i><?php echo $lang["home"];?></a></li>
						<li><a href="http://localhost/issues2/projects"><i class="fa fa-fw fa-folder"></i><?php echo $lang["projects"];?></a>
							<ul>
								<?php $counter1=-1; if( isset($projects) && is_array($projects) && sizeof($projects) ) foreach( $projects as $key1 => $value1 ){ $counter1++; ?><li><a href="http://localhost/issues2/project/<?php echo $key1;?>" title="<?php echo $value1["description"];?>"><?php echo $value1["name"];?></a></li><?php } ?> 
							</ul>
						</li>
						<li><?php if( $loggedIn == false ){ ?><a href="http://localhost/issues2/login"><i class="fa fa-fw fa-user"></i><?php echo $lang["login"];?></a><?php }else{ ?><a href="http://localhost/issues2/logout"><i class="fa fa-fw fa-lock"></i><?php echo $lang["logout"];?></a><?php } ?></li>
					</ul>
				</nav>
				<div class="hidden clear"></div>
			</div>
		</header>
		<section class="content padded middle">