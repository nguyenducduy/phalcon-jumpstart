<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="{{ config.app_baseUri }}images/favicon.ico">

        {{ get_title() }}

        <link href="{{ config.app_baseUri }}plugins/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{ config.app_baseUri }}min/index.php?g=cssAdmin&rev={{ config.cssVersion }}" rel="stylesheet">
        <script src="{{ config.app_baseUri }}min/index.php?g=jquery&rev={{ config.jsVersion }}"></script>

        <script type="text/javascript">
            var root_url = "{{ config.app_baseUri }}";
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

        <script type="text/javascript" src="{{ config.app_baseUri }}plugins/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ config.app_baseUri }}min/index.php?g=jsAdmin&rev={{ config.jsVersion }}"></script>
    </body>
</html>
