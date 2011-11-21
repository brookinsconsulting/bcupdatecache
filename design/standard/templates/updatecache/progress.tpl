<h1>Please wait</h1>
<div class="bar" style="background-color: blue;">
<div class="status" style="background-color: red; width: {$parameters.created_count|mul(100)|div($parameters.total_count)|round}%" >
&nbsp;
</div>
</div>

<p>
Requested {$parameters.created_count} of {$parameters.total_count} pages (<b>{$parameters.created_count|mul(100)|div($parameters.total_count)|round}%</b>).
</p>

<p>
Start time: {$parameters.start_time|l10n('datetime')}, actual time: {$parameters.time|l10n('datetime')}<br />
{def $elapsed_time=$parameters.time|sub($parameters.start_time)}
Requests per second: {$parameters.created_count|div($elapsed_time)}<br />

<b>Estimated time to finish: {$parameters.time|sub($parameters.start_time)|div($parameters.created_count)|mul($parameters.total_count)|round|sum($parameters.start_time)|l10n('datetime')}</b>
</p>


<form id="li_form" name="li_form" action={"/updatecache/cache"|ezurl} method="post">
<div>
    <input type="hidden" name="ParametersSerialized" value="{$parameters_serialized|wash}" />
    <input type="hidden" name="GenerateButton" value="1" />
    <noscript>
        <input id="submit" type="submit" class="button" value="Continue" />
    </noscript>
</div>
</form>

{literal}
<script type="text/javascript">
<!--

    window.onload = new function()
    {
        // document.forms.li_form.submit();
        setTimeout( function()
        { 
            document.forms.li_form.submit(); 
        }, 
        50 );
    }

// -->
</script>
{/literal}
