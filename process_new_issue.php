<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( LOGGED_IN )
{
    if( !empty( $_GET[ 'id' ] ) )
    {
        if( !empty( $_POST[ 'title' ] ) && !empty( $_POST[ 'description' ] ) )
        {
            if( Settings::get( 'issue_security' ) == 'false' || empty( $_POST[ 'security' ] ) || ( $_POST[ 'security' ] != 'public' || $_POST[ 'security' ] != 'private' ) )
                $_POST[ 'security' ] == 'public';

            if( Settings::get( 'issue_priority' ) == 'false' || empty( $_POST[ 'priority' ] ) || ( $_POST[ 'priority' ] != 'low' || $_POST[ 'priority' ] != 'medium' || $_POST[ 'priority' ] != 'high' ) )
                $_POST[ 'priority' ] == 'medium';

            if( Settings::get( 'issue_labels' ) == 'false' || empty( $_POST[ 'label' ] ) )
                $_POST[ 'label' ] == NULL;
            
            DB::table( prefix( 'issues' ) )->insert([
                'project' => $_GET[ 'id' ],
                'name' => $_POST[ 'title' ],
                'description' => $_POST[ 'description' ],
                'label' => $_POST[ 'label' ],
                'security' => $_POST[ 'security' ],
                'priority' => $_POST[ 'priority' ],
                'author' => USERID,
                'date_created' => time()
            ]);
            
            header('Location: '. Settings::get('base_url') . '/issue/' . DB::mysqli()->insert_id );
        }
        else
            echo 'ERR data missing';
    }
    else
        echo 'ERR no project';
}
else
    echo 'ERR not logged in';