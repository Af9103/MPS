<a href="index.php" class="brand-link">
    <img src="../../asset/img/kayaba-logo.png" alt="Kayaba Logo"
        style="opacity: .8; height: 40px; width: 40px; margin-left: 10px">

    <span class="brand-text font-weight-light">Real Time Man Power</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center" style="margin-left: 10px;">
        <div class="image">
            <i class="nav-icon fas fa-user" style="color: white;"></i>
        </div>
        <div class="info">
            <a href="#" class="d-block"><?php echo $_SESSION['name'] ?></h6></a>
        </div>
    </div>


    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 'HRD IR')): ?>
                <li class="nav-item">
                    <a href="../MPS/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Dashboard Man Power
                        </p>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="../FormMutasi/dashboard.php" class="nav-link">
                    <i class="nav-icon fas fa-random"></i>
                    <p>
                        Dashboard Mutasi
                    </p>
                </a>
            </li>


            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Mutasi
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 'HRD IR')): ?>
                        <li class="nav-item">
                            <a href="../Mutasi/mutasi.php" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Daftar Mutasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../Mutasi/mutasidept.php" class="nav-link">
                                <i class="far fa-chart-bar nav-icon"></i>
                                <p>Mutasi berdasarkan Departemen</p>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Kepala Divisi' && $_SESSION['role'] !== 'Direktur Plant' && $_SESSION['role'] !== 'Direktur Non Plant'): ?>
                        <li class="nav-item">
                            <a href="../FormMutasi/buatMutasiBaru.php" class="nav-link">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Pengajuan Mutasi</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="../FormMutasi/approvalMutasi.php" class="nav-link">
                            <i class="far fa-check-circle nav-icon"></i>
                            <p>Persetujuan Mutasi</p>
                        </a>
                    </li>
                </ul>
            </li>

            <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 'HRD IR')): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Perekrutan / Pemberhentian Karyawan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../INOUT/in.php" class="nav-link">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Perekrutan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../INOUT/out.php" class="nav-link">
                                <i class="fas fa-user-times nav-icon"></i>
                                <p>Pemberhentian</p>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="../logout.php" class="nav-link" id="logoutButton">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <p>Keluar</p>
                </a>
            </li>

            <script src="../../asset/sweetalert2/sweetalert2.all.min.js"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Ambil elemen tombol logout
                    var logoutButton = document.getElementById('logoutButton');

                    // Pengecekan apakah elemen logout ditemukan
                    if (logoutButton) {
                        // Tambahkan event listener untuk saat tombol logout diklik
                        logoutButton.addEventListener("click", function (event) {
                            // Menghentikan perilaku default dari link
                            event.preventDefault();

                            // Tampilkan Sweet Alert
                            Swal.fire({
                                title: 'Apakah Anda yakin untuk logout?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Logout!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                // Jika tombol 'Ya, Logout!' diklik
                                if (result.isConfirmed) {
                                    // Redirect ke halaman logout
                                    window.location.href = logoutButton.getAttribute('href');
                                }
                            });
                        });
                    } else {
                        console.error("Tombol logout tidak ditemukan!");
                    }
                });
            </script>