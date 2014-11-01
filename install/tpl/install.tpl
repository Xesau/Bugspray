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
            <form class="form-horizontal" method="post" action="complete.php">
                <div id="page_1">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Site Name</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="site_name" value="Bug Tracker" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Admin e-mail</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="email" name="admin_email" placeholder="your@email.here" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Base URL</label>
                        <div class="col-sm-6">
                            <input class="form-control" value="{$current_url.0|substr:0,-9}" name="base_url" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Language
                        </label>
                        <div class="col-sm-6">
                            <select class="form-control" name="language">
                                <option value="">Select a language</option>
                                {loop="$server.available_languages"}
                                <option value="{$value|basename:'.lang.php'}">{$value|basename:'.lang.php'}</option>{/loop}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Theme
                        </label>
                        <div class="col-sm-6">
                            <select class="form-control" name="theme">
                                <option value="">Select a theme</option>
                                {loop="$server.available_themes"}
                                <option value="{$value|basename}">{$value|basename}</option>
                                
                                {/loop}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-4">
                            <input class="btn btn-primary" value="Next page >" type="button" onclick="return nextPage1()" />
                        </div>
                    </div>
                </div>
                <div class="hidden">
                    <div id="page_2">
                        <div class="form-group">
                            <label class="control-label col-sm-4">MySQL host</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="db_host" value="localhost" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">MySQL username</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="db_username" value="root" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">MySQL password</label>
                            <div class="col-sm-6">
                                <input class="form-control" type="password" name="db_password" placeholder="********" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">MySQL database name</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="db_name" value="bugspray" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">MySQL table prefix</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="db_prefix" value="bs_" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="btn btn-primary pull-right" value="< Previous page" type="button" onclick="return pageBack2()" />
                            </div>
                            <div class="col-sm-6">
                                <input class="btn btn-primary" value="Next page >" type="button" onclick="return nextPage2();" />
                            </div>
                        </div>
                    </div>
                    <div id="page_3">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Admin name</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="admin_name" placeholder="John Newlands" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Admin password</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="admin_password" type="password" placeholder="********" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Admin password <small>(repeat)</small></label>
                            <div class="col-sm-6">
                                <input class="form-control" name="admin_repeat_password" type="password" placeholder="********" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="btn btn-primary pull-right" value="< Previous page" type="button" onclick="return pageBack3()" />
                            </div>
                            <div class="col-sm-6">
                                <input class="btn btn-primary" value="Next page >" type="button" onclick="return nextPage3();" />
                            </div>
                        </div>
                    </div>
                    <div id="page_4">
                        <div class="well well-lg">
                            <i class="glyphicon glyphicon-repeat rotate"></i> We are setting up Bugspray for you
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <script>
            $( '#page_2' ).slideUp();
            $( '#page_3' ).slideUp();
            $( '#page_4' ).slideUp();
            $( '.hidden' ).removeClass( 'hidden' );
            
            function nextPage1()
            {
                if( $( 'input[name=site_name]' ).val() == '' )
                { redenize( $( 'input[name=site_name]' ) ); return false; }
                
                if( $( 'input[name=admin_email]' ).val() == '' )
                { redenize( $( 'input[name=admin_email]' ) ); return false; }
                
                if( $( 'input[name=base_url]' ).val() == '' )
                { redenize( $( 'input[name=base_url]' ) ); return false; }
                
                if( $( 'select[name=language]' ).val() == '' )
                {   redenize( $( 'select[name=language]' ) ); return false; }
                
                if( $( 'select[name=theme]' ).val() == '' )
                {   redenize( $( 'select[name=theme]' ) ); return false; }
                
                $( '#page_2' ).slideDown();
                $( '#page_1' ).slideUp();
                
                return false;
            }
            
            function nextPage2()
            {
                if( $( 'input[name=db_host]' ).val() == '' )
                { redenize( $( 'input[name=db_host]' ) ); return false; }
                if( $( 'input[name=db_username]' ).val() == '' )
                { redenize( $( 'input[name=db_username]' ) ); return false; }
                if( $( 'input[name=db_password]' ).val() == '' )
                { redenize( $( 'input[name=db_password]' ) ); return false; }
                if( $( 'input[name=db_name]' ).val() == '' )
                { redenize( $( 'input[name=db_name]' ) ); return false; }
                
                
                $( '#page_3' ).slideDown();
                $( '#page_2' ).slideUp();
                
                return false;
            }
            
            function nextPage3()
            {
                if( $( 'input[name=admin_email]' ).val() == '' )
                { redenize( $( 'input[name=admin_email]' ) ); return false; }
                if( $( 'input[name=admin_password]' ).val() == '' || $( 'input[name=admin_password]' ).val().length < 8 )
                { redenize( $( 'input[name=admin_password]' ) ); return false; }
                if( $( 'input[name=admin_repeat_password]' ).val() == '' )
                { redenize( $( 'input[name=admin_repeat_password]' ) ); return false; }
                if( $( 'input[name=admin_password]' ).val() !== $( 'input[name=admin_repeat_password]' ).val() )
                { redenize( $( 'input[name=admin_password]' ) ); redenize( $( 'input[name=admin_repeat_password]' ) ); return false; }
                if( $( 'input[name=admin_name]' ).val() == '' )
                { redenize( $( 'input[name=admin_name]' ) ); return false; }
                
                $( '#page_4' ).slideDown();
                $( '#page_3' ).slideUp();
                
                setTimeout( function() {
                    document.forms[0].submit();
                }, 2000);
                
                return false;
            }
            
            function pageBack2()
            {
                $( '#page_1' ).slideDown();
                $( '#page_2' ).slideUp();
            }
            
            function pageBack3()
            {
                $( '#page_2' ).slideDown();
                $( '#page_3' ).slideUp();
            }
            
            function redenize( $elm )
            {
                $elm.addClass( 'inputError' );
                setTimeout( function() {
                    $elm.removeClass( 'inputError' );
                }, 800 );
            }
        </script>
    </body>
</html>