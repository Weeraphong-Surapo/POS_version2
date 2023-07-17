<form action="" id="formSearch" class="d-flex justify-content-between">
  <select name="" id="searchYear" class="form-control">
    <option value="" disabled selected>เลือกปี</option>
    <?php
    $array_year = array();
    $y = date('Y') + 543;
    for ($i = 0; $i <= 3; $i++) {
      array_push($array_year, $y + $i);
    }
    for ($i = 0; $i < count($array_year); $i++) :
    ?>
      <option value="<?= $array_year[$i] ?>"><?= $array_year[$i] ?></option>
    <?php endfor; ?>
  </select>
</form>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h4 class="card-title">ยอดขายประจำเดือน</h4>
        <canvas id="barChart" style="height:100px"></canvas>
      </div>
    </div>
  </div>
</div>
<?php
include("../config/connect.php");
$array_price_month = array();
$total_month = 0;
if (isset($_GET['searchYear'])) {
  $year_search = explode('.', $_GET['searchYear']);
  $product = $conn->query("SELECT * FROM tb_sale WHERE by_year = '$year_search[0]'");
  $sum_total_month1 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 1 AND by_year = '$year_search[0]'");
  $row_month1 = $sum_total_month1->fetch_array();
  $sum_total_month2 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 2 AND by_year = '$year_search[0]'");
  $row_month2 = $sum_total_month2->fetch_array();
  $sum_total_month3 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 3 AND by_year = '$year_search[0]'");
  $row_month3 = $sum_total_month3->fetch_array();
  $sum_total_month4 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 4 AND by_year = '$year_search[0]'");
  $row_month4 = $sum_total_month4->fetch_array();
  $sum_total_month5 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 5 AND by_year = '$year_search[0]'");
  $row_month5 = $sum_total_month5->fetch_array();
  $sum_total_month6 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 6 AND by_year = '$year_search[0]'");
  $row_month6 = $sum_total_month6->fetch_array();
  $sum_total_month7 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 7 AND by_year = '$year_search[0]'");
  $row_month7 = $sum_total_month7->fetch_array();
  $sum_total_month8 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 8 AND by_year = '$year_search[0]'");
  $row_month8 = $sum_total_month8->fetch_array();
  $sum_total_month9 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 9 AND by_year = '$year_search[0]'");
  $row_month9 = $sum_total_month9->fetch_array();
  $sum_total_month10 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 10 AND by_year = '$year_search[0]'");
  $row_month10 = $sum_total_month10->fetch_array();
  $sum_total_month11 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 11 AND by_year = '$year_search[0]'");
  $row_month11 = $sum_total_month11->fetch_array();
  $sum_total_month12 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 12 AND by_year = '$year_search[0]'");
  $row_month12 = $sum_total_month12->fetch_array();
} else {
  $product = $conn->query("SELECT * FROM tb_sale");
  $sum_total_month1 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 1");
  $row_month1 = $sum_total_month1->fetch_array();
  $sum_total_month2 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 2");
  $row_month2 = $sum_total_month2->fetch_array();
  $sum_total_month3 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 3");
  $row_month3 = $sum_total_month3->fetch_array();
  $sum_total_month4 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 4");
  $row_month4 = $sum_total_month4->fetch_array();
  $sum_total_month5 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 5");
  $row_month5 = $sum_total_month5->fetch_array();
  $sum_total_month6 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 6");
  $row_month6 = $sum_total_month6->fetch_array();
  $sum_total_month7 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 7");
  $row_month7 = $sum_total_month7->fetch_array();
  $sum_total_month8 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 8");
  $row_month8 = $sum_total_month8->fetch_array();
  $sum_total_month9 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 9");
  $row_month9 = $sum_total_month9->fetch_array();
  $sum_total_month10 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 10");
  $row_month10 = $sum_total_month10->fetch_array();
  $sum_total_month11 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 11");
  $row_month11 = $sum_total_month11->fetch_array();
  $sum_total_month12 = $conn->query("SELECT sum(price_sum_vat) as sum_total FROM tb_sale WHERE by_month = 12");
  $row_month12 = $sum_total_month12->fetch_array();
}


array_push($array_price_month, $row_month1['sum_total']);
array_push($array_price_month, $row_month2['sum_total']);
array_push($array_price_month, $row_month3['sum_total']);
array_push($array_price_month, $row_month4['sum_total']);
array_push($array_price_month, $row_month5['sum_total']);
array_push($array_price_month, $row_month6['sum_total']);
array_push($array_price_month, $row_month7['sum_total']);
array_push($array_price_month, $row_month8['sum_total']);
array_push($array_price_month, $row_month9['sum_total']);
array_push($array_price_month, $row_month10['sum_total']);
array_push($array_price_month, $row_month11['sum_total']);
array_push($array_price_month, $row_month12['sum_total']);
?>
<script>
  $(function() {
    /* ChartJS
     * -------
     * Data and config for chartjs
     */
    'use strict';
    var data = {
      labels: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
      datasets: [{
        label: 'ขายใด้',
        data: [<?php echo implode(',', $array_price_month) ?>],
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
  $('#searchYear').change((e) => {
    window.location.href = "#/reportproductmonth.php?searchYear=" + e.target.value;
  })
</script>