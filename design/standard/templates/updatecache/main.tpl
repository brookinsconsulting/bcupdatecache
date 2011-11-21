{section show=is_set($parameters.created_count)}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> 
    Requested {$parameters.created_count} nodes</h2>
</div>
{/section}

{literal}
<script type="text/javascript">
<!--
    function lips_inc( el )
    {
        if ( !isNaN( el.value ) )
        {
            el.value++;
        }
    }

    function lips_inc_max( el, max )
    {
        if ( !isNaN( el.value ) && el.value <= max - 1 )
        {
            el.value++;
        }
    }

    function lips_dec( el )
    {
        if ( !isNaN( el.value ) && 1 <= el.value )
        {
            el.value -= 1; // double minus cannot be used here
        }
    }
// -->
</script>
{/literal}

<form name="lips" action={"updatecache/cache"|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Cache updater for eZ publish'|i18n('generator')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{*
<div class="block">
    <label>Number of nodes to generate under each node</label>

    <p>
        <input name="Parameters[count]" value="{$parameters.count|wash}" size="5" />
    </p>
</div>
*}

<div class="block">
    <p>
        This module loads all pages from a specified subtree. The purpose is to regenerate cache for all pages. Typically useful after
        you have cleared all cache for an eZ publish installation.
    </p>
    <p>
        <em>Warning: This will give lots of requests to your server, and potentionally give the server high load.</em>
    </p>
</div>


<div class="block">
    <label>Subtree to generate cache for</label>

    <p>
        {let node=fetch('content','node',hash('node_id',$parameters.node))}
        {section show=$node}
            {foreach $node.path as $path_item}
                {$path_item.name|wash} /
            {/foreach}
            {$node.name|wash}
            (Node id: {$node.node_id} )
            <input type="hidden" name="Parameters[node]" value="{$parameters.node|wash}" size="5" />
        {section-else}
            No node selected.
        {/section}
        {/let}
    </p>
    <p>
        <input type="submit" name="BrowseButton" value="Select node" />
    </p>
</div>

<div class="block">
    <label>Base url</label>
    <p>
        This is the url to your user-site (example: http://ez.no, http://example.com/index.php/mysiteaccess)
    </p>

    <p>
        <input name="Parameters[base_url]" value="{$parameters.base_url|wash}" size="40" />
    </p>
</div>



</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
        <input class="button" type="submit" name="GenerateButton" value="Update Cache" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
