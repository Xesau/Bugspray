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
            <h2 class="centered"><b>Bugspray</b> Installation</h2>
        </header>
        <section class="centered padded content">
            <div class="alert alert-danger">
                {if="$error == 'mysql'"}
                Bugspray was unable to connect to the mysql server ({$mysql_server}) with username {$mysql_username}.
                {/if}
                {if="$error == 'fields'"}
                You did not enter all the required fields.
                {/if}
                {if="$error == 'password_length'"}
                The given password does not have a minimum length of 8 characters.
                {/if}
                {if="$error == 'password_equality'"}
                The given password does not equal the repeated password.
                {/if}<br /><br />
                Please go <a href="javascript:history.go(-1)">back</a> and try again.<br />
                (fields will be wiped)
            </div>
        </section>
    </body>
</html>