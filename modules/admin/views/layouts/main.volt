<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="{{ static_url('images/favicon.ico') }}">

        {{ get_title() }}

        <link href="{{ static_url('plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ static_url('min/index.php?g=cssAdmin&rev=' ~ config.cssVersion) }}" rel="stylesheet">
        <script src="{{ static_url('min/index.php?g=jquery&rev=' ~ config.jsVersion) }}"></script>

        <script type="text/javascript">
            var root_url = "{{ url.getBaseUri() }}";
        </script>
    </head>

    <body>
        {% include "layouts/header.volt" %}

        <section>
            <div class="mainwrapper">
                {% include "layouts/left-sidebar.volt" %}

                <div class="mainpanel">
                {% block content %}{% endblock %}
                </div>
            </div>
        </section>

        <script type="text/javascript" src="{{ static_url('plugins/bootstrap/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ static_url('min/index.php?g=jsAdmin&rev=' ~ config.jsVersion) }}"></script>
    </body>
</html>
