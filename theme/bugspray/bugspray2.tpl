<!DOCTYPE html>

<html>
	<head>
    	<link rel="stylesheet" href="../../admin/tpl/css/bootstrap.min.css" />
        <script src="../../admin/tpl/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="font-awesome.min.css" />
		<title>{$settings.site_name|strip_tags} &bull; {$pagedata.title|strip_tags}</title>
	</head>
	<body>
		<header>
			<div class="middle">
				<h2>{$settings.site_name}</h2> 
 				<nav>
					<ul>
						<li><a href=""><i class="fa fa-fw fa-home"></i>{$lang.home}</a></li>
						<li><a href="projects"><i class="fa fa-fw fa-folder"></i>{$lang.projects}</a>
							<ul>
								{loop="$projects"}<li><a href="project/{$key}" title="{$value.description}">{$value.name}</a></li>{/loop} 
							</ul>
						</li>
						<li>{if="$loggedIn == false"}<a href="login"><i class="fa fa-fw fa-user"></i>{$lang.login}</a>{else}<a href="logout"><i class="fa fa-fw fa-lock"></i>{$lang.logout}</a>{/if}</li>
					</ul>
				</nav>
				<div class="hidden clear"></div>
			</div>
		</header>
		<section class="content padded middle">
            {include="template.$pagedata.template"}
            
		</section>
	</body>
</html>