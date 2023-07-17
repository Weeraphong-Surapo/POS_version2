<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <!-- partial:partials/_sidebar.html -->

  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-category">เมณู</li>
      <li class="nav-item">
        <a class="nav-link" href="#/dashboard">
          <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
          <span class="menu-title">หน้าหลัก</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#/salepic">
          <span class="icon-bg"><i class="mdi mdi-desktop-mac menu-icon"></i></span>
          <span class="menu-title">จอขายแบบรูปภาพ</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#/salebarcode">
          <span class="icon-bg"><i class="mdi mdi-monitor-multiple menu-icon"></i></span>
          <span class="menu-title">จอขายแบบรายการ</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#" style="cursor:pointer;" onclick="$('#ui-basic').toggle('toggle')" aria-expanded="false" aria-controls="ui-basic">
          <span class="icon-bg"><i class="mdi mdi-crosshairs-gps menu-icon"></i></span>
          <span class="menu-title">จัดการสินค้า</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="#/product">สินค้า</a></li>
            <li class="nav-item"> <a class="nav-link" href="#/categorie">หมวดหมู่</a></li>
            <li class="nav-item"> <a class="nav-link" href="#/subcategorie">ประเภท</a></li>
            <li class="nav-item"> <a class="nav-link" href="#/discount">ส่วนลดสินค้า</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#" style="cursor:pointer;" onclick="$('#customer').toggle('toggle')" aria-expanded="false" aria-controls="ui-basic">
          <span class="icon-bg"><i class="mdi mdi-folder-account menu-icon"></i></span>
          <span class="menu-title">จัดการลูกค้า</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="customer">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="#/listgroup">รายชื่อลูกค้า</a></li>
            <li class="nav-item"> <a class="nav-link" href="#/groupcustomer">กลุ่มลูกค้า</a></li>
          </ul>
        </div>
      </li>
      <?php
      if ($_SESSION['type'] == 999) :
      ?>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#" style="cursor:pointer;" onclick="$('#employee').toggle('toggle')" aria-expanded="false" aria-controls="ui-basic">
            <span class="icon-bg"><i class="mdi mdi-clipboard-account menu-icon"></i></span>
            <span class="menu-title">จัดการพนักงาน</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="employee">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="#/listemployee">รายชื่อพนักงาน</a></li>
              <li class="nav-item"> <a class="nav-link" href="#/position">ตำแหน่ง</a></li>
            </ul>
          </div>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#" style="cursor:pointer;" onclick="$('#saleprice').toggle('toggle')" aria-expanded="false" aria-controls="ui-basic">
          <span class="icon-bg"><i class="mdi mdi-chart-bar menu-icon"></i></span>
          <span class="menu-title">รายงานการขาย</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="saleprice">
          <ul class="nav flex-column sub-menu">
            <?php
              if ($_SESSION['type'] == 999) :
            ?>
              <li class="nav-item"> <a class="nav-link" href="#/storysale">ประวัติการซื้อ</a></li>
              <li class="nav-item"> <a class="nav-link" href="#/reportproduct">ยอดการขายสินค้า</a></li>
              <li class="nav-item"> <a class="nav-link" href="#/reportproductmonth">ยอดการขายรายเดือน</a></li>
              <li class="nav-item"> <a class="nav-link" href="#/reportyear">ยอดการขายประจำปี</a></li>
            <?php
            else:
              echo '<li class="nav-item"> <a class="nav-link" href="#/mystorysale">ประวัติการขาย</a></li>';
            endif;?>
          </ul>
        </div>
      </li>

    </ul>
  </nav>
  <div class="main-panel">
    <div class="content-wrapper">