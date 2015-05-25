<form class="tab-pane transition scale fade" id="signup-form">
  <h3 class="modal-title">Đăng ký</h3>
  <div class="form-control space-top-2x">
    <input type="email" name="su-email" id="su-email" required>
    <label for="su-email">Email</label>
    <span class="error-label"></span>
    <span class="valid-label"></span>
  </div>
  <div class="form-control">
    <input type="password" name="su-password" id="su-password" required>
    <label for="su-password">Mật khẩu</label>
    <span class="error-label"></span>
    <span class="valid-label"></span>
  </div>
  <div class="form-control">
    <input type="password" name="su-password-repeat" id="su-password-repeat" required>
    <label for="su-password-repeat">Nhập lại mật khẩu</label>
    <span class="error-label"></span>
    <span class="valid-label"></span>
  </div>
  <label class="checkbox space-top-2x">
    <input type="checkbox"> Nhận thông báo khi có bài viết mới
  </label>
  <div class="clearfix modal-buttons">
    <div class="pull-right">
      <button type="button" class="btn btn-flat btn-default waves-effect" data-dismiss="modal">Thoát</button>
      <button type="submit" class="btn btn-flat btn-primary waves-effect waves-primary">Đồng ý</button>
    </div>
    <!-- Switching forms (Fake nav tab) -->
    <div class="form-switch pull-left"><a class="btn btn-flat btn-primary waves-effect waves-primary" href="#form-2">Đăng nhập</a></div>
  </div>
</form>