<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="/public/images/favicon.ico">
        {{ get_title() }}

        <link href="{{ config.app_baseUri }}public/min/index.php?g=cssCommon&rev={{ config.cssVersion }}" rel="stylesheet">
        <script src="{{ config.app_baseUri }}public/min/index.php?g=jquery&rev={{ config.jsVersion }}"></script>

    </head>
    <body>
        {% include "layouts/header.volt" %}

        {% block content %}{% endblock %}

        {% include "layouts/footer.volt" %}