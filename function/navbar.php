<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="#/dashboard"><img src="assets/upload_logo/<?= $logo_shop['shop_img'] ?>" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="#/dashboard"><img src="assets/upload_logo/<?= $logo_shop['shop_img'] ?>" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item  dropdown d-none d-md-block">
        <a class="nav-link" id="go-button"> <i class="mdi mdi-fullscreen"></i> </a>
      </li>
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
          <div class="nav-profile-img">
            <img src="assets/upload_img_customer/<?= isset($img) ? $img : '' ?>" alt="image">
          </div>
          <div class="nav-profile-text">
            <p class="mb-1 text-black"><?= isset($fullname) ? $fullname : '' ?></p>
          </div>
        </a>
        <div class="dropdown-menu navbar-dropdown dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="profileDropdown" data-x-placement="bottom-end">
          <div class="p-3 text-center bg-primary">
            <img class="img-avatar img-avatar48 img-avatar-thumb" src="assets/upload_img_customer/<?= isset($img) ? $img : '' ?>" alt="">
          </div>
          <div class="p-2">
            <h5 class="dropdown-header text-uppercase pl-2 text-dark">เมณูระบบ</h5>
            <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="#/setingprofile">
              <span>ตั้งค่าโปร์ไฟล์</span>
              <i class="mdi mdi-account"></i>
            </a>
            <?php
            if ($_SESSION['type'] == 999) :
            ?>
              <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="#/setingshop">
                <span>ตั้งค่าร้าน</span>
                <i class="mdi mdi-city"></i>
              </a>
            <?php endif; ?>
            <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="#/setingPaper">
              <span>ตั้งค่ากระดาษปริ้นใบเสร็จ</span>
              <i class="mdi mdi-settings"></i>
            </a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="#" onclick="logout()">
              <span>ออกจากระบบ</span>
              <i class="mdi mdi-logout ml-1"></i>
            </a>
          </div>
        </div>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>