    <div id="element">
        <?php
        include("function/head.php");
        include("function/navbar.php");
        include("function/sidebar.php");
        ?>
        <div id="AppContainer"></div>
    </div>
    </div>
    <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script>
        var HashChange = function() {
            var hash = window.location.hash;
            if (hash.startsWith("#/") && hash.length > 2) {
                hash = hash.replace("#/", "");
                var file = "src/" + hash + ".php";
                $("#AppContainer").load(file);
            } else {
                window.location = "#/dashboard";
            }
            window.onhashchange = HashChange;
        }
        HashChange();


        /* Get into full screen */
        function GoInFullscreen(element) {
            if (element.requestFullscreen)
                element.requestFullscreen();
            else if (element.mozRequestFullScreen)
                element.mozRequestFullScreen();
            else if (element.webkitRequestFullscreen)
                element.webkitRequestFullscreen();
            else if (element.msRequestFullscreen)
                element.msRequestFullscreen();
        }

        /* Get out of full screen */
        function GoOutFullscreen() {
            if (document.exitFullscreen)
                document.exitFullscreen();
            else if (document.mozCancelFullScreen)
                document.mozCancelFullScreen();
            else if (document.webkitExitFullscreen)
                document.webkitExitFullscreen();
            else if (document.msExitFullscreen)
                document.msExitFullscreen();
        }

        /* Is currently in full screen or not */
        function IsFullScreenCurrently() {
            var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

            // If no element is in full-screen
            if (full_screen_element === null)
                return false;
            else
                return true;
        }

        $("#go-button").on('click', function() {
            if (IsFullScreenCurrently())
                GoOutFullscreen();
            else
                GoInFullscreen($("#element").get(0));
        });

        function logout() {
            let option = {
                url: 'controller/action.php',
                type: 'post',
                data: {
                    logout: 1
                },
                success: (res) => {
                    Swal.fire(
                        'ออกจากระบบสำเร็จ!',
                        '',
                        'success'
                    )
                    setTimeout(() => {
                        window.location = "src/login.php";
                    }, 800)
                }
            }
            Swal.fire({
                title: 'ต้องการออกจากระบบ?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax(option)
                }
            })
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- <script src="assets/vendors/js/vendor.bundle.base.js"></script> -->
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/jquery-circle-progress/js/circle-progress.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- <script src="assets/js/dashboard.js"></script> -->
    <!-- <script src="assets/js/chart.js"></script> -->
    <!-- End custom js for this page -->
    </body>

    </html>