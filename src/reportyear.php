<?php include("../config/connect.php"); ?>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">รายงานขายประจำปี</h4>
            <canvas id="areaChart" style="height:250px"></canvas>
        </div>
    </div>
</div>
<?php
$array_report_year = array();
$array_check_year = array();
$all_sale_year = $conn->query("SELECT price_sum_vat,sale_id,by_year FROM tb_sale");
foreach ($all_sale_year as $row) {
    if (!in_array($row['by_year'], $array_check_year)) {
        array_push($array_check_year, $row['by_year']);
    }
    for ($i = 0; $i < count($array_check_year); $i++) {
        if ($row['by_year'] == $array_check_year[$i]) {
            $total_price_year = $conn->query("SELECT sum(price_sum_vat) as total_year FROM tb_sale WHERE by_year = '$array_check_year[$i]'");
            $row_total = $total_price_year->fetch_array();
            array_push($array_report_year, $row_total['total_year']);
        }
    }
}
?>
<script>
    $(function() {
        /* ChartJS
         * -------
         * Data and config for chartjs
         */
        'use strict';
        var areaData = {
            labels: [<?php echo implode(',', $array_check_year) ?>],
            datasets: [{
                label: 'รายใด้',
                data: [<?php echo implode(',', $array_report_year) ?>],
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
                fill: true, // 3: no fill
            }]
        };

        var areaOptions = {
            plugins: {
                filler: {
                    propagate: true
                }
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
            }
        }

        if ($("#areaChart").length) {
            var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
            var areaChart = new Chart(areaChartCanvas, {
                type: 'line',
                data: areaData,
                options: areaOptions
            });
        }
    });
</script>