<?php
include('../config/connect.php');
$m = date('m');
$d = date('d');
$date = date('d M Y');
$qty = 0;
$product_name = '';
$array_sale_day = array();
$array_sale_product_day = array();
$array_sale_product_qty_day = array();
$array_sale_product_price_day = array();
$array_sale_sale_id = array();
$array_product_id = array();

$query = $conn->query("SELECT * FROM tb_sale");
foreach ($query as $row) {
    $day = explode('-', $row['by_date']);
    if ($day[0] == $d) {
        if (!in_array($row['sale_id'], $array_sale_sale_id)) {
            array_push($array_sale_sale_id, $row['sale_id']);
        }
        array_push($array_sale_day, $row);
        $product = $conn->query("SELECT * FROM tb_order WHERE sale_id = '$row[sale_id]'");
        if ($product->num_rows >= 1) {

            foreach ($product as $data) {



                $sum = $conn->query("SELECT sum(product_total_price) as total FROM tb_order WHERE product_id = $data[product_id] AND sale_id = '$row[sale_id]'");
                $rows = $sum->fetch_array();
                array_push($array_sale_product_price_day, $rows['total']);

                $product_list = $conn->query("SELECT * FROM tb_product WHERE product_id = $data[product_id]");
                $row_product_list = $product_list->fetch_array();

                $product_name = '"' . $row_product_list['product_name'] . '"';
                if (!in_array($product_name, $array_sale_product_day)) {
                    array_push($array_sale_product_day, $product_name);
                }
            }
        }
    }
}


for ($i = 0; $i < count($array_sale_sale_id); $i++) {
    $qty_product = $conn->query("SELECT product_qty,product_id FROM tb_order WHERE sale_id = $array_sale_sale_id[$i]");
    foreach ($qty_product as $rows) {
        if (!in_array($rows['product_id'], $array_product_id)) {
            array_push($array_product_id, $rows['product_id']);
        }
    }
}
$count_sale_day = count($array_sale_day);

$all_product = $conn->query("SELECT product_id FROM tb_product");
$count_all_product = $all_product->num_rows;

$count_all_price_month = 0;
$price_all_month = 0;
$price_all_total = 0;
$query = $conn->query("SELECT * FROM tb_sale");
foreach ($query as $row) {


    $price_all_total += $row['price_sum_vat'];
    $month = explode('-', $row['by_date']);
    if ($month[1] == $m) {

        $count_all_price_month += 1;
        $price_all_month += $row['price_sum_vat'];
    }
}


?>
<div class="d-xl-flex justify-content-between align-items-start">
    <h2 class="text-dark font-weight-bold mb-2"> แดชบอร์ดภาพรวม </h2>
    <div class="d-sm-flex justify-content-xl-between align-items-center mb-2">
        <div class="dropdown ml-0 ml-md-4 mt-2 mt-lg-0">
            <button class="btn bg-white p-3 d-flex align-items-center" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-calendar mr-1"></i><?= $date; ?> </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tab-content tab-transparent-content">
            <div class="tab-pane fade show active" id="business-1" role="tabpanel" aria-labelledby="business-tab">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="mb-2 text-dark font-weight-normal">สินค้าในระบบ</h5>
                                <h2 class="mb-4 text-dark font-weight-bold"><?= number_format($count_all_product) ?> </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="mb-2 text-dark font-weight-normal">รายการขายเดือนนี้</h5>
                                <h2 class="mb-4 text-dark font-weight-bold"><?= number_format($count_all_price_month); ?></h2>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3  col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="mb-2 text-dark font-weight-normal">รายใด้เดือนนี้</h5>
                                <h2 class="mb-4 text-dark font-weight-bold"><?= number_format($price_all_month, 2); ?></h2>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="mb-2 text-dark font-weight-normal">รายใด้ทั้งหมด</h5>
                                <h2 class="mb-4 text-dark font-weight-bold"><?= number_format($price_all_total, 2) ?></h2>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-12 col-md-12">

                                        <h3 class="mb-0 text-dark font-weight-bold"><i class="mdi mdi-content-paste text-info"></i> รายการขายวันนี้ ( <span class="text-success"><?= $count_sale_day ?></span> )</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-hover table-bordered" id="myTable">
                                                <thead class="bg-info text-white">
                                                    <tr>
                                                        <th class="text-center">ลำดับ</th>
                                                        <th class="text-center">รหัสรายการ</th>
                                                        <th class="text-center">ชื่อลูกค้า</th>
                                                        <th class="text-center">พนักงานขาย</th>
                                                        <th class="text-center">วันที่ซื้อ</th>
                                                        <th width="15%" class="text-center">รายละเอียด</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    foreach ($array_sale_day as $row) :
                                                        $customer = $conn->query("SELECT * FROM tb_customer WHERE customer_id = '$row[customer_id]'");
                                                        if ($customer->num_rows >= 1) {
                                                            $row_customer = $customer->fetch_array();
                                                            $name = $row_customer['customer_fname'] . ' ' . $row_customer['customer_lname'];
                                                        } else {
                                                            $name = 'ไม่ใช่สมาชิก';
                                                        }

                                                        $employee = $conn->query("SELECT * FROM tb_employee WHERE employee_id = '$row[employee_id]'");
                                                        if ($employee->num_rows >= 1) {
                                                            $row_employee = $employee->fetch_array();
                                                            $emp_name = $row_employee['fname'] . ' ' . $row_employee['lname'];
                                                        } else {
                                                            $emp_name = '';
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?= $i++; ?></td>
                                                            <td><?= $row['sale_code']; ?></td>
                                                            <td><?= $name; ?></td>
                                                            <td><?= $emp_name ?></td>
                                                            <td><?= $row['by_date']; ?></td>
                                                            <td>
                                                                <div class="d-flex justify-content-center">
                                                                    <button class="btn btn-primary btn-xs" onclick="showDetail(<?= $row['sale_id'] ?>)"><i class="mdi mdi-eye"></i></button>
                                                                    <button class="btn btn-danger" onclick="DeleteStory(<?= $row['sale_id'] ?>)"><i class="mdi mdi-delete-forever"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-12 col-md-12 mt-3">
                                        <div class="d-xl-flex justify-content-between align-items-center mb-2">
                                            <div class="d-lg-flex align-items-center mb-lg-2 mb-xl-0">
                                                <h3 class="text-dark font-weight-bold mr-2 mb-0 "><i class="mdi mdi-shopping text-primary"></i> สินค้าที่ขายวันนี้</h3>
                                            </div>
                                        </div>
                                        <div class="graph-custom-legend clearfix" id="device-sales-legend"></div>
                                        <canvas id="device-sales"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="textcategory">รายละเอียดการขาย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="resultDetail"></div>
            </div>
        </div>
    </div>
</div>

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

    function showDetail(id) {
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                id: id,
                showDetail: 1
            },
            success: (res) => {
                $('#resultDetail').html(res)
                $('#ModalDetail').modal('show')
            }
        }
        $.ajax(option)
    }

    function DeleteStory(id) {
        let option = {
            url: 'controller/action.php',
            type: 'post',
            data: {
                id: id,
                deleteStory: 1
            },
            success: function(res) {
                table.row('#row-' + id).remove().draw();
                swalAlert('ลบข้อมูลสำเร็จ', 'success');
            }
        }
        Swal.fire({
            title: 'ต้องการลบประวัติ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax(option)
            }
        })
    }

    (function($) {
        'use strict';
        $(function() {

            if ($(".dashboard-progress-4").length) {
                $('.dashboard-progress-4').circleProgress({
                    value: 1.45,
                    size: 125,
                    thickness: 7,
                    startAngle: 10,
                    fill: {
                        gradient: ["#9f041b", "#f5515f"]
                    }
                });
            }


            if ($("#device-sales").length) {
                var deviceSalesData = {
                    labels: [<?= implode(',', $array_sale_product_day) ?>],
                    datasets: [{
                        label: 'ขายใด้',
                        data: [<?= implode(',', $array_sale_product_price_day) ?>],
                        backgroundColor: [
                            '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a',
                        ],
                        borderColor: [
                            '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a', '#fc5a5a',
                        ],
                        borderWidth: 1,
                        fill: false
                    }]
                };
                var deviceSalesOptions = {
                    scales: {
                        xAxes: [{
                            stacked: false,
                            barPercentage: .5,
                            categoryPercentage: 0.4,
                            position: 'bottom',
                            display: true,
                            gridLines: {
                                display: false,
                                drawBorder: false,
                                drawTicks: false
                            },
                            ticks: {
                                display: true, //this will remove only the label
                                stepSize: 500,
                                fontColor: "#a7afb7",
                                fontSize: 14,
                                padding: 10,
                            }
                        }],
                        yAxes: [{
                            stacked: false,
                            display: true,
                            gridLines: {
                                drawBorder: false,
                                display: true,
                                color: "#eef0fa",
                                drawTicks: false,
                                zeroLineColor: 'rgba(90, 113, 208, 0)',
                            },
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
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend">');
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            text.push('<li><span class="legend-box" style="background:' + chart.data.datasets[i].backgroundColor[i] + ';"></span><span class="legend-label text-dark">');
                            if (chart.data.datasets[i].label) {
                                text.push(chart.data.datasets[i].label);
                            }
                            text.push('</span></li>');
                        }
                        text.push('</ul>');
                        return text.join("");
                    },
                    tooltips: {
                        backgroundColor: 'rgba(0, 0, 0, 1)',
                    },
                    plugins: {
                        datalabels: {
                            display: false,
                            align: 'center',
                            anchor: 'center'
                        }
                    }
                };
                var barChartCanvas = $("#device-sales").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                var barChart = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: deviceSalesData,
                    options: deviceSalesOptions
                });
                document.getElementById('device-sales-legend').innerHTML = barChart.generateLegend();
            }


            // var doughnutPieOptions = {
            //     responsive: true,
            //     animation: {
            //         animateScale: true,
            //         animateRotate: true
            //     }
            // };
            // var doughnutPieData = {
            //     datasets: [{
            //         data: [<?= implode(',', $array_sale_product_qty_day) ?>],
            //         backgroundColor: [
            //             'rgba(255, 99, 132, 0.5)',
            //             'rgba(54, 162, 235, 0.5)',
            //             'rgba(255, 206, 86, 0.5)',
            //             'rgba(75, 192, 192, 0.5)',
            //             'rgba(153, 102, 255, 0.5)',
            //             'rgba(255, 159, 64, 0.5)'
            //         ],
            //         borderColor: [
            //             'rgba(255,99,132,1)',
            //             'rgba(54, 162, 235, 1)',
            //             'rgba(255, 206, 86, 1)',
            //             'rgba(75, 192, 192, 1)',
            //             'rgba(153, 102, 255, 1)',
            //             'rgba(255, 159, 64, 1)'
            //         ],
            //     }],

            //     // These labels appear in the legend and in the tooltips when hovering different arcs
            //     labels: [
            //         <?= implode(',', $array_sale_product_day) ?>
            //     ]
            // };

            // if ($("#doughnutChart").length) {
            //     var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
            //     var doughnutChart = new Chart(doughnutChartCanvas, {
            //         type: 'doughnut',
            //         data: doughnutPieData,
            //         options: doughnutPieOptions
            //     });
            // }


        });
    })(jQuery);
</script>