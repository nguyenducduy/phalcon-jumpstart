{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="user_list">
    {{ flash.output() }}
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ config.app_baseUri ~ redirectUrl }}">
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
                    <label class="control-label">Email</label>
                    <input type="text" name="femail" value="{% if formData['femail'] is defined %}{{ formData['femail'] }}{% endif %}" class="form-control input-sm" disabled="disabled" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Role</label>
                    <select name="frole" class="form-control input-sm">
                        <option value="0">- - - -</option>
                        {% for role in roleList %}
                            <option value="{{ role['value'] }}" {% if formData['frole'] is defined and formData['frole'] == role['value'] %}selected="selected"{% endif %}>{{ role['name'] }}</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <div id="uploadAvatar" class="dropzone"></div>
                    <input type="hidden" name="favatar" value="{% if formData['favatar'] is defined %}{{ formData['favatar'] }}{% endif %}" id="uploadAvatarInput"/>
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
<script type="text/javascript">
    $(document).ready(function() {
        Dropzone.autoDiscover = false;
        $('div#uploadAvatar').dropzone({
            url: root_url + 'admin/user/uploadavatar',
            paramName: 'favatar',
            maxFileSize: 2,
            maxFiles: 1,
            init: function() {
                this.on("maxfilesexceeded", function(file){
                    toastr.error("Cannot upload more than 1 file!");
                });
                this.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<button class='btn btn-default btn-sm'><i class='fa fa-times'></i></button>");
                    var _this = this;
                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        _this.removeFile(file);
                    });
                    file.previewElement.appendChild(removeButton);
                });
                this.on("success", function(file, response) {
                    var path = response.jsondata.favatar.path
                    $("#uploadAvatarInput").val(path.replace("/public", ""));
                    toastr.success("File upload OK!");
                });
            },
        });
    });
</script>
{% endblock %}
