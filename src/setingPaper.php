	<?php
    include("../config/connect.php");


    $size_page = $conn->query("SELECT * FROM tb_size_page");
    $row_size_page = $size_page->fetch_array();
    $width = $row_size_page['width'];
    $height = $row_size_page['height'];
    ?>
	<style>
	    .box {
	        background-color: white;
	        width: 80mm;
	        height: 80mm;
	        color: white;
	        padding: 2px;
	        position: relative;
	    }

	    .box-mini {
	        top: 0;
	        width: auto;
	        position: absolute;
	    }
	</style>
	<div id="demopaper">
	    <div class="row">
	        <div class="col-3">
	            <h3 class="text-center text-black">กำหนดขนาด</h3>
	            <p class="text-danger">* ขนาดแนะนำ A7 (74mm * 105mm) </p>
	            <div class="mb-2">
	                <label for="">ความกว้าง : </label>
	                <div class="d-flex justify-content-between">
	                    <input type="text" name="" id="" class="form-control" v-model="boxStyle.width">
	                </div>
	            </div>
	            <div class="mb-2">
	                <label for="">ความสูง : </label>
	                <div class="d-flex justify-content-between">
	                    <input type="text" name="" id="" class="form-control" v-model="boxStyle.height">
	                </div>
	            </div>
	            <button class="btn btn-primary" @click="savesize">บันทึก</button>
	        </div>
	        <div class="col-9 d-flex justify-content-center">
	            <img src="src/test11.png" alt="" class="img-fluid" v-bind:style="boxStyle">
	        </div>
	    </div>
	</div>
	<script>
	    var width = <?= $width ?> + 'mm';
	    var height = <?= $height ?> + 'mm';
	    new Vue({
	        el: '#demopaper',
	        data: {
	            boxStyle: {
	                'background-color': 'white',
	                'width': width,
	                'height': height,
	            }
	        },
	        methods: {
	            savesize() {
	                axios.post('controller/salepicController.php', {
	                    action: "savesize",
	                    width: this.boxStyle.width,
	                    height: this.boxStyle.height
	                }).then((res) => {
                        this.swalAlert("success","อัพเดตขนาดกระดาษสำเร็จ")
                    })
	            },
	            swalAlert(type, title) {
	                Swal.fire({
	                    position: "center",
	                    icon: type,
	                    text: title,
	                    showConfirmButton: false,
	                    timer: 900,
	                });
	            },
	        },
	        computed: {
	            newBoxStyle: function() {
	                return {
	                    'background-color': this.backgroundColor,
	                    'width': this.width + 'mm',
	                    'height': this.height + 'mm',
	                }
	            }
	        },
	        watch: {
	            backgroundColor: function() {
	                this.boxStyle['background-color'] = this.backgroundColor
	            },
	            width: function() {
	                this.boxStyle['width'] = this.width + 'mm'
	            },
	            height: function() {
	                this.boxStyle['height'] = this.height + 'mm'
	            }
	        }
	    })
	</script>
	<script>
	    // function test_print(){
	    //     const pdfUrl = "controller/testprint.php?id=1";
	    //     const pdfWindow = window.open(pdfUrl, "_blank");
	    //     pdfWindow.print().focus();
	    // }
	</script>