<h3 class="no-spacing">{$lang.admin.labels}</h3>
<hr />
{if="hasPermission( USERID, 'bs_labels' )"}
{loop="labels"}
<div class="label" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$value.label}</div> <a href="deletelabel/{$key}" class="glyphicon glyphicon-remove"></a>&nbsp;&nbsp;
{/loop}
{else}
{loop="labels"}
<div class="label" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$value.label}</div>&nbsp;&nbsp;
{/loop}
{/if} 
{if="hasPermission( USERID, 'bs_labels' )"}<hr />
<form class="form-horizontal pull-left half-width" method="post" action="{$settings.base_url}/admin/newlabel">
    <h3>{$lang.new_label}</h3>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.label}</label>
        <div class="col-sm-6">
            <input class="form-control" onchange="previewLabel()" id="new_label_name" name="label_name" /> <small>({$lang.live_preview|strtolower})</small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.text_color}</label>
        <div class="col-sm-6">
            <input class="form-control color" onchange="previewLabel()" id="new_label_txt" value="555555" name="text_color" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.background_color}</label>
        <div class="col-sm-6">
            <input class="form-control color" onchange="previewLabel()" id="new_label_bg" name="background_color" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-4">
            <input type="submit" class="btn btn-primary" value="{$lang.add}" />
        </div>
    </div>
</form>
<form class="form-horizontal pull-left half-width" method="post" action="{$settings.base_url}/admin/modifylabel">
    <h3>{$lang.modify_label} <select onchange="updateLabel(this)" name="label" class="form-control half-width inline"><option></option>{loop="labels"}<option value="{$key}">{$value.label}</option>{/loop}</select></h3>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.label}</label>
        <div class="col-sm-6">
            <input class="form-control" onchange="previewModifyLabel()" id="modify_label_name" name="label_name" disabled /> <small>({$lang.preview|strtolower})</small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.text_color}</label>
        <div class="col-sm-6">
            <input class="form-control color" onchange="previewModifyLabel()" id="modify_label_txt" value="555555" name="text_color" disabled />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.background_color}</label>
        <div class="col-sm-6">
            <input class="form-control color" onchange="previewModifyLabel()" id="modify_label_bg" name="background_color" disabled />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-4">
            <input type="submit" class="btn btn-primary" id="modify_label_submit" disabled value="{$lang.save}" />
        </div>
    </div>
</form>
<script src="js/jscolor.js"></script>
<script>
    function previewLabel()
    {
        document.getElementById( 'new_label_name' ).style.backgroundColor = '#' + document.getElementById( 'new_label_bg' ).value;
        document.getElementById( 'new_label_name' ).style.color = '#' + document.getElementById( 'new_label_txt' ).value;
    }
    
    function previewModifyLabel()
    {
        document.getElementById( 'modify_label_name' ).style.backgroundColor = '#' + document.getElementById( 'modify_label_bg' ).value;
        document.getElementById( 'modify_label_name' ).style.color = '#' + document.getElementById( 'modify_label_txt' ).value;
    }
    
    function updateModifyState()
    {
        document.getElementById( 'modify_label_bg' ).style.backgroundColor = '#' + document.getElementById( 'modify_label_bg' ).value;
        document.getElementById( 'modify_label_txt' ).style.backgroundColor = '#' + document.getElementById( 'modify_label_txt' ).value;
        
        document.getElementById( 'modify_label_bg' ).style.color = ( luminance( document.getElementById( 'modify_label_bg' ).value ) < 170 ? '#ffffff' : '#000000' );
        document.getElementById( 'modify_label_txt' ).style.color = ( luminance( document.getElementById( 'modify_label_txt' ).value ) < 170 ? '#ffffff' : '#000000' );
    }
    
    function updateLabel( elm )
    {
        var $labels = [];
{loop="labels"}
        $labels[ {$key} ] = [ '{$value.label}', '{$value.txtcolor}', '{$value.bgcolor}' ];
{/loop}
        
        $modifyLabelName = document.getElementById( 'modify_label_name' );
        $modifyLabelText = document.getElementById( 'modify_label_txt' );
        $modifyLabelBack = document.getElementById( 'modify_label_bg' );
        $modifyLabelBttn = document.getElementById( 'modify_label_submit' );

        if( elm.value == '' )
        {
            $modifyLabelName.disabled = 'disabled';
            $modifyLabelText.disabled = 'disabled';
            $modifyLabelBack.disabled = 'disabled';
            $modifyLabelBttn.disabled = 'disabled';
            $modifyLabelName.value = '';
            $modifyLabelText.value = '555555';
            $modifyLabelBack.value = 'FFFFFF';
        }
        else
        {
            $modifyLabelName.disabled = null;
            $modifyLabelText.disabled = null;
            $modifyLabelBack.disabled = null;
            $modifyLabelBttn.disabled = null;
            $modifyLabelName.value = $labels[ elm.value ][ 0 ];
            $modifyLabelText.value = $labels[ elm.value ][ 1 ];
            $modifyLabelBack.value = $labels[ elm.value ][ 2 ];
        }
        previewModifyLabel();
        updateModifyState();
    }
        
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    var luminance = function(color) {
        var rgb = hexToRgb(color);
        if (!rgb) return null;
            return 0.2126 * rgb.r + 0.7152 * rgb.b + 0.0722 * rgb.g;
    }
</script>
{else}
<div class="alert alert-info">{$lang.status.no_edit_permission}</div>{/if}