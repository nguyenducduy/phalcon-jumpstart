{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="codegenerator_list">
    <div class="row pull-right" style="margin-right:0">
        <div class="col-lg-12 mb20">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {% for tblName in listTables %}
            {% if tblName not in blockTable %}
            <div class="col-xs-6 col-sm-3">
                <a class="tbl_button" href="{{ config.app_baseUri }}admin/codegenerator/create/table/{{ tblName }}" style="margin-bottom:10px;"><i class="fa fa-magic"></i> &nbsp; {{ tblName }}</a> <br/>
            </div>
            {% endif %}
            {% endfor %}
        </div>
    </div>
</div>
<style type="text/css">
    .tbl_button {
        display:block;
        border:1px solid #ccc;
        padding:10px;
        background-color: #ecf0f1;
        color: #333;
    }
</style>
{% endblock %}