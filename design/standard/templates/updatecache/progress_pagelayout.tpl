<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    
<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <style type="text/css">
    {literal}
        html, body
        {
            width: 100%; height: 100%;
            overflow: hidden;
        }

        /* TODO: fix for IE */
        div.outer
        {
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: table;
            position: static;
        }

        div.middle
        {
            width: 100%;
            display: table-cell;
            vertical-align: middle;
            position: static;
        }

        div.inner
        {
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }

        h1
        {
            background-color: #dddddd;
        }
    {/literal}
    </style>
</head>

<body>

<div class="outer"><div class="middle"><div class="inner">

{$module_result.content}

</div></div></div>

<!--DEBUG_REPORT-->

</body>
</html>
