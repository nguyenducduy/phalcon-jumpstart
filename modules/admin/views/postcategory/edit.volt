{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="postcategory_list">
    {{ flash.output() }}
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ url(redirectUrl) }}">
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
                    <label class="control-label">Category</label>
                    <select name="fparent" class="input-sm">
                        <option value="0">Root</option>
                        {% for cat in categoryList %}
                            <option value="{{ cat['id'] }}" {% if formData['fparent'] == cat['id'] %}selected="selected"{% endif %}>{{ cat['name'] }}</option>
                            {% if cat['children'] != null %}
                                {% for child in cat['children'] %}
                                <option value="{{ child['id'] }}" {% if formData['fparent'] == cat['id'] %}selected="selected"{% endif %}>- {{ child['name'] }}</option>
                                {% endfor %}
                            {% endif %}
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group" >
                    <label class="control-label">Name</label>
                    <input type="text" name="fname" value="{% if formData['fname'] is defined %}{{ formData['fname'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Description</label>
                    <input type="text" name="fdescription" value="{% if formData['fdescription'] is defined %}{{ formData['fdescription'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Status</label>
                    <select name="fstatus" class="form-control input-sm">
                        <option value="0">- - - -</option>
                        {% for status in statusList %}
                            <option value="{{ status['value'] }}" {% if formData['fstatus'] is defined and formData['fstatus'] == status['value'] %}selected="selected"{% endif %}>{{ status['name'] }}</option>
                        {% endfor %}
                    </select>
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
