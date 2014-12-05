<!DOCTYPE html>

<html>
    <head>
        <title>Bugspray Installer</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="style.css" />
    </head>
    
    <body class="no-spacing">
        <header class="padded">
            <h2 class="centered"><b>Bugspray</b> Installation Completed!</h2>
        </header>
        <section class="centered padded content form-horizontal">
            Bugspray was successfully installed upon your webhost.<br />
            It's recommended to rename the 'install' directory to something silly like 'asdf', to prevent people with bad intentions.<br /><br />
			<b>Site Information</b>
			
			<div class="form-group">
				<label class="col-sm-4 control-label">Site Name</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$settings.site_name}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Base URL</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$settings.base_url}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Debug mode</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$settings.debug_mode == '1' ? 'On' : 'Off' }" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Admin email</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$admin.email}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Language</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$settings.language|ucfirst}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Theme</label>
				<div class="col-sm-6">
					<input readonly class="form-control" value="{$settings.theme|ucfirst}" />
				</div>
			</div>
            <div class="form-group">
				<div class="col-sm-6 col-sm-offset-4">
                    <a class="btn btn-primary" href="{$settings.base_url}/admin/new_project">Create your first project!</a>
                </div>
			</div>
        </section>
    </body>
</html>