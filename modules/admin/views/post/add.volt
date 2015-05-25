{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="post_add">
    {{content()}}
    {{ flash.output() }}
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ url(redirectUrl) }}">
                <i class="fa fa-angle-double-left"></i> &nbsp; {{ lang.get('label_back_button') }}
            </a>
        </div>
    </div>

    <form method="post" action="" enctype="multipart/form-data" id="addPost">
        <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <h2 class="md-title">{{ lang.get('label_overview') }}</h2>
                <p class="mb30">
                    {{ lang.get('label_overview_description') }}
                </p>
            </div>

            <div class="col-sm-8 col-md-8">
                <div class="form-group" >
                    <label class="control-label">Category</label>
                    <select name="fpcid" class="input-sm">
                        <option value="0">Root</option>
                        {% for cat in categoryList %}
                            <option value="{{ cat['id'] }}" {% if formData['fpcid'] == cat['id'] %}selected="selected"{% endif %}>{{ cat['name'] }}</option>
                            {% if cat['children'] != null %}
                                {% for child in cat['children'] %}
                                <option value="{{ child['id'] }}" {% if formData['fpcid'] == cat['id'] %}selected="selected"{% endif %}>- {{ child['name'] }}</option>
                                {% endfor %}
                            {% endif %}
                        {% endfor %}
                    </select>
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
                    <div id="myEditor">{{ formData['fcontent'] }}</div>
                    <input type="hidden" name="fcontent" value="" id="fcontent"/>
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
<script type="text/javascript" src="{{ static_url('plugins/ace/ace.js') }}"></script>
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

        $('#myEditor').markdownEditor({
          // Activate the preview:
          preview: true,
          fullscreen: false,
          // This callback is called when the user click on the preview button:
          onPreview: function (content, callback) {
                // Example of implementation with ajax:
                $.ajax({
                    url: root_url + 'admin/post/preview',
                    type: 'POST',
                    dataType: 'html',
                    data: {content: content},
                })
                .done(function(result) {
                    // Return the html:
                    callback(result);
                });
            },
            imageUpload: true, // Activate the option
            uploadPath: root_url + 'admin/post/upload' // Path of the server side script that receive the files
        });

        $( "#addPost" ).submit(function( event ) {
            var markdownContent = $('#myEditor').markdownEditor('content');
            $('#fcontent').val(markdownContent);
        });
    });
</script>
{% endblock %}
