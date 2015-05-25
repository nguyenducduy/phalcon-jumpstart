<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        {{ get_title() }}

        <link href="{{ static_url('plugins/bootstrap/bootstrap.min.css"') }} rel="stylesheet" type="text/css">
        <link href="{{ static_url('min/index.php?g=cssAdmin&rev=' ~ config.cssVersion) }}" rel="stylesheet">
        <script src="{{ static_url('min/index.php?g=jquery&rev=' ~ config.jsVersion) }}"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <section>
            <div class="notfoundpanel">
                <h1>404!</h1>
                <h3>The page you are looking for has not been found!</h3>
                <p>The page you are looking for might have been removed, had its name changed, or unavailable. Maybe you could try a search:</p>
                <form action="http://themepixels.com/demo/webpage/chain/search-results.html">
                    <input type="text" class="form-control" placeholder="Search for page" /> <button class="btn btn-primary">Search</button>
                </form>
            </div><!-- notfoundpanel -->
        </section>
        <script type="text/javascript" src="{{ static_url('plugins/bootstrap/bootstrap.min.js') }}"></script>
        <script src="{{ static_url('min/index.php?g=jsAdmin&rev=' ~ config.jsVersion) }}"></script>
    </body>
</html>
