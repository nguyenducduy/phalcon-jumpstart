{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="post_list">
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
                    <label class="control-label">Uid</label>
                    <input type="text" name="fuid" value="{% if formData['fuid'] is defined %}{{ formData['fuid'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Pcid</label>
                    <input type="text" name="fpcid" value="{% if formData['fpcid'] is defined %}{{ formData['fpcid'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Title</label>
                    <input type="text" name="ftitle" value="{% if formData['ftitle'] is defined %}{{ formData['ftitle'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Summary</label>
                    <input type="text" name="fsummary" value="{% if formData['fsummary'] is defined %}{{ formData['fsummary'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Content</label>
                    <input type="text" name="fcontent" value="{% if formData['fcontent'] is defined %}{{ formData['fcontent'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group" >
                    <label class="control-label">Tags</label>
                    <input type="text" name="ftags" value="{% if formData['ftags'] is defined %}{{ formData['ftags'] }}{% endif %}" class="form-control input-sm" />
                </div>
                <div class="form-group">
                    <div id="uploadCover" class="dropzone"></div>
                    <input type="hidden" name="fcover" value="{% if formData['fcover'] is defined %}{{ formData['fcover'] }}{% endif %}" id="uploadCoverInput"/>
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
                <div class="form-group" >
                    <label class="control-label">Type</label>
                    <select name="ftype" class="form-control input-sm">
                        <option value="0">- - - -</option>
                        {% for type in typeList %}
                            <option value="{{ type['value'] }}" {% if formData['ftype'] is defined and formData['ftype'] == type['value'] %}selected="selected"{% endif %}>{{ type['name'] }}</option>
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
        $('div#uploadCover').dropzone({
            url: root_url + 'admin/post/uploadcover',
            paramName: 'fcover',
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
                    var path = response.jsondata.fcover.path
                    $("#uploadCoverInput").val(path.replace("/public", ""));
                    toastr.success("File upload OK!");
                });
            },
        });
    });
</script>
{% endblock %}
