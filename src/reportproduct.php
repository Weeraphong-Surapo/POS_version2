<?php include("../config/connect.php"); ?>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">ยอดขายสินค้า</h4>
        <canvas id="barChart" style="height:100px"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="myTable">
            <thead>
              <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th style="text-align: center;">จำนวนขาย</th>
                <th style="text-align: right;">ขายใด้</th>
                <th style="text-align: right;">ส่วนลด/บาท</th>
                <th style="text-align: right;">รายรับ/บาท</th>
                <th style="text-align: right;">ต้นทุน/บาท</th>
                <th style="text-align: right;">กำไร/ขาดทุน</th>
                <th style="text-align: right;">ROI</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $check_order = $conn->query("SELECT * FROM tb_order");
              $numk_order = $check_order->num_rows;
              if($numk_order >= 1){
              $all_product = $conn->query("SELECT * FROM tb_product");
              foreach ($all_product as $row) :
                $all_qty = $conn->query("SELECT sum(product_qty) as count_qty FROM tb_order WHERE product_id = $row[product_id]");
                $row_qty = $all_qty->fetch_array();
                $count_qty = $row_qty['count_qty'] >= 1 ? $row_qty['count_qty'] : 0;

                $all_detail = $conn->query("SELECT * FROM tb_order WHERE product_id = $row[product_id]");
                $price_prduct = $conn->query("SELECT sum(product_total_price) as sum_price FROM tb_order WHERE product_id = '$row[product_id]'");
                $row_price_product = $price_prduct->fetch_array();
                if($all_detail->num_rows >= 1){
                  $all_detail2 = $conn->query("SELECT sum(total_cost) as total FROM tb_order WHERE product_id = $row[product_id]");
                  
                  $row_detail2 = $all_detail2->fetch_array();
                  $row_detail = $all_detail->fetch_array();

                  $discount = $row_detail['total_discount'];
                  $total_get = $row_price_product['sum_price'] - $discount;
                  $total_cost = $row_detail2['total'];
                  $profit = $total_get - $total_cost;
                  $roi = $profit/$total_cost * 100;
                }else{
                  $roi = 0;
                  $profit = 0;
                  $total_cost = 0;
                  $total_get = 0;
                  $discount = 0;
                }
              ?>
                <tr>
                  <td><?= $row['product_code']; ?></td>
                  <td><?= $row['product_name']; ?></td>
                  <td style="text-align: center;"><?= $count_qty ?></td>
                  <td style="text-align: right;"><?= $row_price_product['sum_price'];?></td>
                  <td style="text-align: right;"><?= number_format($discount,2)?></td>
                  <td style="text-align: right;"><?= number_format($total_get,2)?></td>
                  <td style="text-align: right;"><?= number_format($total_cost,2)?></td>
                  <td style="text-align: right;"><?= number_format($profit,2)?></td>
                  <td style="text-align: right;"><?= number_format($roi,2) ?> %</td>
                </tr>
              <?php endforeach; 
              }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

$array_product_name = array();
$array_product_price = array();
$all_products = $conn->query("SELECT * FROM tb_product");
foreach($all_products as $row){
  $product_name = '"'.$row['product_name'].'"';
  array_push($array_product_name,$product_name);
}

$all_price_prduct = $conn->query("SELECT * FROM tb_product");
foreach($all_price_prduct as $row){
  $price_prduct = $conn->query("SELECT sum(product_total_price) as sum_price FROM tb_order WHERE product_id = '$row[product_id]'");
  $row_price_product = $price_prduct->fetch_array();
  array_push($array_product_price,$row_price_product['sum_price']);
}
?>
<script>
  var table = $('#myTable').DataTable({
        "language": {
            "sProcessing": "กำลังดำเนินการ...",
            "sLengthMenu": "แสดง_MENU_ แถว",
            "sZeroRecords": "ไม่พบข้อมูล",
            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
            "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
            "sInfoPostFix": "",
            "sSearch": "ค้นหา:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "เิริ่มต้น",
                "sPrevious": "ก่อนหน้า",
                "sNext": "ถัดไป",
                "sLast": "สุดท้าย"
            }
        }
    });
  $(function() {
    /* ChartJS
     * -------
     * Data and config for chartjs
     */
    'use strict';
    var data = {
      labels: [<?php echo implode(',',$array_product_name) ?>],
      datasets: [{
        label: 'ขายใด้',
        data: [<?php echo implode(',',$array_product_price)?>],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        fill: false
      }]
    };

    var options = {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      },
      tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += tooltipItem.yLabel.toLocaleString('th-TH', {
                            style: 'currency',
                            currency: 'THB',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        return label;
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value.toLocaleString('th-TH', {
                                style: 'currency',
                                currency: 'THB',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }]
            },
      legend: {
        display: false
      },
      elements: {
        point: {
          radius: 0
        }
      }

    };

    // Get context with jQuery - using jQuery's .get() method.
    if ($("#barChart").length) {
      var barChartCanvas = $("#barChart").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: data,
        options: options
      });
    }

  });
</script>