<h3 class="no-spacing">{$lang.admin.edit_project} <i>{$project.name}</i><a href="projects" class="btn btn-danger pull-right">{$lang.back}</a></h3>
<hr />
<form class="half-width form-horizontal" method="post" action="{$admin}/save_project/{$id}" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.name}</label>
        <div class="col-sm-6">
            <input class="form-control" name="name" value="{$project.name}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.short}</label>
        <div class="col-sm-6">
            <input class="form-control" name="short" value="{$project.short}" maxlength=4 />
            <label class="control-label">({'%x%'|str_replace:4,$lang.max_x_chars})</label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.description}</label>
        <div class="col-sm-6">
            <textarea class="form-control" name="description">{$project.description}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.leader_email}</label>
        <div class="col-sm-6">
            <input name="project_lead" value="{$project.project_lead|userData:'email'}" class="form-control" type="email" placeholder="john.doe@gmail.com" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.upload_picture}</label>
        <div class="col-sm-6">
            <input type="file" name="file" accept="image/*" />
            <img class="{if="!$hasImage"}hidden {/if}full-width auto-height form-control" id="imgpreview" src="{if="$hasImage"}../../content/project_imgs/{$id}.png{/if}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.creation_date}</label>
        <label class="col-sm-6 control-label" style="text-align: left;">{'d-m-Y h:i:s'|date:$project.date_created}</label>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            {if="$project.enabled == '0'"}
            <a href="enable_project/{$id}" class="btn btn-success pull-right">{$lang.enable}</a>
            {else}
            <a href="disable_project/{$id}" class="btn btn-danger pull-right">{$lang.disable}</a>
            {/if}
        </div>
        <div class="col-sm-6">
            <input type="submit" class="btn btn-primary" value="{$lang.save}">            
        </div>
    </div>
</form>
<script>
    $( 'input[name=file]' ).change( function( $event )
    {
        $( '#imgpreview' ).removeClass( 'hidden' ).attr( 'src', URL.createObjectURL( $event.target.files[0] ) );
    } );
    $( 'input[name=lead]' ).autocomplete({source:[{$lead_emails}]});
</script>