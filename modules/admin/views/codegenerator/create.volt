{% extends "layouts/main.volt" %}

{% block content %}
<style type="text/css">
    .contentpanel .label {
        font-size:10px;
    }
    .ckbox label, .rdio label {
          display: inline-block;
    }
</style>
<div class="contentpanel" rel="codegenerator_list">
    {{ content() }}
    {{ flash.output() }}

    <form class="form-horizontal" method="post" action="" target="_blank">
        <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="panel-btns" style="display: none;">
                    <a href="#" class="panel-minimize tooltips" data-toggle="tooltip" title="" data-original-title="Minimize Panel"><i class="fa fa-minus"></i></a>
                </div><!-- panel-btns -->
                <h4 class="panel-title">MODEL</h4>
                <p> Create Model class for table <code>{{ tableName }}</code></p>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Namespace</label>
                    <div class="col-lg-3 col-sm-10 col-xs-10">
                        <input type="text" name="fnamespace" value="Model" class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Class</label>
                    <div class="col-lg-3">
                        <input type="text" name="fmodelname" value="{{ formData['modelName'] }}" class="form-control input-sm">
                    </div>
                    <div class="col-lg-1"><code>extends</code></div>
                    <div class="col-lg-3">
                        <input type="text" name="fmodelextends" value="FlyModel" class="form-control input-sm">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Table Alias</label>
                    <div class="col-lg-7">
                        <div class="input-group input-group-sm col-lg-6">
                            <span class="input-group-addon">{{ tableName }}</span>
                            <input type="text" name="ftablealias" value="{{ formData['tableAlias'] }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Column Name</th>
                                    <th>Class Property</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {% for column in formData['columns'] %}
                                <input type="hidden" name="fnullable[{{ column['name'] }}]" value="{{ column['isNotNull'] }}" />
                                <input type="hidden" name="ftype[{{ column['name'] }}]" value="{{ column['typename'] }}" />
                                <input type="hidden" name="fprimary[{{ column['name'] }}]" value="{{ column['isPrimary'] }}" />
                                <tr>
                                    <td class="col-lg-3" style="line-height: 40px">
                                        <code>{{ column['name'] }}</code>
                                        <span class="label label-default">
                                            {{ column['typename']|lower }}
                                            {% if column['size'] > 0 %}
                                                ({{ column['size'] }})
                                            {% endif %}
                                        </span>
                                        {% if column['name'] in formData['indexesCol'] %}
                                            &nbsp; <span class="label label-info">
                                                Index
                                            </span>
                                        {% endif %}
                                        {% if column['isPrimary'] == true %}
                                            &nbsp; <span class="label label-danger">
                                                Primary
                                            </span>
                                        {% endif %}
                                    </td>
                                    <td class="col-lg-2">
                                        <input type="text" name="fproperty[{{ column['name'] }}]" value="{{ column['property'] }}" class="form-control input-sm"/>
                                    </td>
                                    <td class="col-lg-6">
                                        <div class="col-lg-3">
                                            <div class="ckbox ckbox-primary col-lg-12">
                                                <input type="checkbox" name="ffilterable[{{ column['name'] }}]" value="{{ column['name'] }}" id="filter{{ column['name'] }}" >
                                                <label for="filter{{ column['name'] }}" style="display: inline-block">Filterable</label>
                                            </div>
                                            <div class="ckbox ckbox-warning col-lg-12">
                                                <input type="checkbox" name="fsortable[{{ column['name'] }}]" value="{{ column['name'] }}" id="sortable{{ column['name'] }}" >
                                                <label for="sortable{{ column['name'] }}" style="display: inline-block">Sortable</label>
                                            </div>
                                        </div>
                                        {% if column['isNumeric'] == true %}
                                            <div class="col-lg-9">
                                                <input type="text" value="" name="fconstant[{{ column['name'] }}]" placeholder="Constant value" class="form-control input-sm"/>
                                            </div>
                                        {% else %}
                                            <div class="ckbox ckbox-success col-lg-3">
                                                <input type="checkbox" name="fsearchable[{{ column['name'] }}]" value="{{ column['name'] }}" id="search{{ column['name'] }}" >
                                                <label for="search{{ column['name'] }}" style="display: inline-block">Searchable</label>
                                            </div>
                                        {% endif %}
                                    </td>
                                </tr>
                                {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="panel-btns" style="display: none;">
                    <a href="#" class="panel-minimize tooltips" data-toggle="tooltip" title="" data-original-title="Minimize Panel"><i class="fa fa-minus"></i></a>
                </div><!-- panel-btns -->
                <h4 class="panel-title">CONTROLLER</h4>
                <p> Create Controller class for table <code>{{ tableName }}</code></p>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Type</label>
                    <div class="col-lg-2" style="padding-top:10px;">
                        <div class="rdio rdio-primary">
                            <input type="radio" name="fcontrollertype" value="normal" id="radioSuccess" checked="checked">
                            <label for="radioSuccess">Normal</label>
                        </div>
                    </div>
                    <div class="col-lg-2" style="padding-top:10px;">
                        <div class="rdio rdio-success">
                            <input type="radio" name="fcontrollertype" value="ko" id="radioPrimary" disabled="disable">
                            <label for="radioPrimary">KnockoutJS</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Module</label>
                    <div class="col-lg-3">
                        <select name="fmodulename" class="form-control input-sm">
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Class</label>
                    <div class="col-lg-3">
                        <div class="input-group input-group-sm col-lg-12">
                            <input type="text" name="fcontrollername" value="{{ formData['controllerName'] }}" class="form-control">
                            <span class="input-group-addon">Controller</span>
                        </div>
                    </div>
                    <div class="col-lg-1"><code>extends</code></div>
                    <div class="col-lg-3">
                        <input type="text" name="fcontrollerextends" value="FlyController" class="form-control input-sm" disabled="disabled">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Record Per Page</label>
                    <div class="col-lg-3">
                        <input type="text" name="frecordperpage" value="30" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Column Name</th>
                                    <th>Label</th>
                                    <th>Validation &nbsp; <a href="javascript:void(0)" data-toggle="modal" data-target=".box-info-validation"><i class="fa fa-info-circle"></i></a></th>
                                    <th>Input Type</th>
                                    <th>Exclude</th>
                                </tr>
                            </thead>
                            <tbody>
                                {% for column in formData['columns'] %}
                                <tr>
                                    <td class="col-lg-3" style="line-height: 40px">
                                        <code>{{ column['name'] }}</code>
                                        <span class="label label-default">
                                            {{ column['typename']|lower }}
                                            {% if column['size'] > 0 %}
                                                ({{ column['size'] }})
                                            {% endif %}
                                        </span>
                                        {% if column['name'] in formData['indexesCol'] %}
                                            &nbsp; <span class="label label-info">
                                                Index
                                            </span>
                                        {% endif %}
                                        {% if column['isPrimary'] == true %}
                                            &nbsp; <span class="label label-danger">
                                                Primary
                                            </span>
                                        {% endif %}
                                    </td>
                                    <td class="col-lg-2">
                                        <input type="text" name="flabel[{{ column['name'] }}]" value="{{ column['label'] }}" class="form-control input-sm"/>
                                    </td>
                                    <td class="col-lg-2">
                                        <select class="form-control input-sm" name="fvalidation[{{ column['name'] }}]">
                                            <option value="none">None</option>
                                            <option value="email">Email</option>
                                            <option value="presenceof">PresenceOf</option>
                                            <option value="numericality">Numericality</option>
                                            <option value="uniqueness">Uniqueness</option>
                                        </select>
                                    </td>
                                    <td class="col-lg-2">
                                        <select class="form-control input-sm" name="finputtype[{{ column['name'] }}]">
                                            <option value="none">None</option>
                                            <option value="dropzone">Dropzone</option>
                                        </select>
                                    </td>
                                    <td class="col-lg-2">
                                        <div class="ckbox ckbox-primary col-lg-12">
                                            <input type="checkbox" name="fexclude_i[{{ column['name'] }}]" value="{{ column['name'] }}" id="excludeindex{{ column['name'] }}" >
                                            <label for="excludeindex{{ column['name'] }}" style="display: inline-block">Index</label>
                                        </div>
                                        <div class="ckbox ckbox-success col-lg-12">
                                            <input type="checkbox" name="fexclude_ae[{{ column['name'] }}]" value="{{ column['name'] }}" id="excludeaddedit{{ column['name'] }}" >
                                            <label for="excludeaddedit{{ column['name'] }}" style="display: inline-block">Add/Edit</label>
                                        </div>
                                    </td>
                                </tr>
                                {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel-footer">
                <span>
                    <input type="submit" name="fsubmit" value="GENERATE" class="btn btn-success mr5" />
                    <button type="reset" class="btn btn-default">RESET</button>
                </span>
            </div>
        </div>
    </form>
</div>

<div class="modal fade box-info-validation" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
          <div class="modal-header">
              <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
              <h4 class="modal-title">Validating Data Integrity</h4>
          </div>
          <div class="modal-body">
              <h4>PresenceOf</h4>
              <p>Validates that a field’s value isn’t null or empty string. This validator is automatically added based on the attributes marked as not null on the mapped table.</p>
              <h4>Email</h4>
              <p>Validates that field contains a valid email format.</p>
              <h4>Numericality</h4>
              <p>Validates that a field has a numeric format.</p>
              <h4>Uniqueness</h4>
              <p>Validates that a field or a combination of a set of fields are not present more than once in the existing records of the related table.</p>
          </div>
      </div>
    </div>
</div>
{% endblock %}