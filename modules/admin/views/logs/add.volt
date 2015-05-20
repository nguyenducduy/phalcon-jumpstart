{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="logs_add">
    {{ flash.output() }}
    <div class="row">
        <div class="col-lg-12">
            <a href="/{{ redirectUrl }}">
                <i class="fa fa-angle-double-left"></i> &nbsp; {{ lang.get('label_back_button') }}
            </a>
        </div>
    </div>

    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <h2 class="md-title">{{ lang.get('label_overview') }}</h2>
                <p class="mb30">
                    {{ lang.get('label_overview_description') }}
                </p>
            </div>

            <div class="col-sm-4 col-md-4">
                <div class="form-group" >
                    <label class="control-label">Name</label>
                    <input type="text" name="fname" value="{% if formData['fname'] is defined %}{{ formData['fname'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Type</label>
                    <select name="ftype" class="form-control input-sm">
                        <option value="0">- - - -</option>
                        {% for key, type in typeList %}
                            <option value="{{ key }}" {% if formData['ftype'] is defined and formData['ftype'] == key %}selected="selected"{% endif %}>{{ type }}</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group" >
                    <label class="control-label">Content</label>
                    <input type="text" name="fcontent" value="{% if formData['fcontent'] is defined %}{{ formData['fcontent'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Datecreated</label>
                    <input type="text" name="fdatecreated" value="{% if formData['fdatecreated'] is defined %}{{ formData['fdatecreated'] }}{% endif %}" class="form-control input-sm" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel-footer">
                <span>
                    <input type="submit" name="fsubmit" value="{{ lang.get('label_submit_button') }}" class="btn btn-success mr5" />
                    <button type="reset" class="btn btn-default">{{ lang.get('label_reset_button') }}</button>
                </span>
                <span class="text-required">*</span> {{ lang.get('label_star_required') }}
            </div>
        </div>
    </form>
</div>

{% endblock %}
