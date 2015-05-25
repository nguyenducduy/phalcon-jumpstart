<form class="tab-pane transition scale fade in active" id="signin-form" autocomplete="off">
  <h3 class="modal-title">Đăng nhập</h3>
  <div class="form-control space-top-2x">
    <input type="email" name="si_email" id="si_email" readonly onfocus="$(this).removeAttr('readonly');" required>
    <label for="si_email">Email</label>
    <span class="error-label"></span>
    <span class="valid-label"></span>
  </div>
  <div class="form-control">
    <input type="password" name="si-password" id="si-password" readonly onfocus="$(this).removeAttr('readonly');" required>
    <label for="si-password">Mật khẩu</label>
    <a class="helper-link" href="#">Quên mật khẩu?</a>
    <span class="error-label"></span>
    <span class="valid-label"></span>
  </div>
  <label class="checkbox space-top-2x">
    <input type="checkbox"> Duy trì đăng nhập
  </label>
  <div class="clearfix modal-buttons">
    <div class="pull-right">
      <button type="button" class="btn btn-flat btn-default waves-effect" data-dismiss="modal">Thoát</button>
      <button type="submit" class="btn btn-flat btn-primary waves-effect waves-primary">Đồng ý</button>
    </div>
    <!-- Switching forms (Fake nav tab) -->
    <div class="form-switch pull-left"><a class="btn btn-flat btn-primary waves-effect waves-primary" href="#form-1">Đăng ký</a></div>
  </div>
</form>