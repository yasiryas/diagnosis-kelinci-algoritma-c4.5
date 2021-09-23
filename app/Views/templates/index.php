<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title; ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url(); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>/https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->include('templates/sidebar'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->include('templates/topbar'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?= $this->renderSection('page-content'); ?>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Diagnosict Rabbit <?= date("Y"); ?></span>
                    </div>
                </div>
            </footer> -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal-->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Change Password?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Yes" below if you are ready to exit and get a confirmation email to change the password!</div>
                <div class="modal-footer">
                    <form action="<?= base_url('change'); ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" value="<?= user()->email; ?>" name="email">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Gejala Modal -->

    <div class="modal fade" id="gejalaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah gejala</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('erorr')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert" id="alert">
                        <?= session()->getFlashdata('erorr'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#gejalaModal').modal();
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/tambahGejala" method="post" class="needs-validation" novalidate id="formGejala">
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="text" name="kategori" placeholder="Kategori gejala" required value="<?= old('kategori'); ?>" id="kategori">
                            <div class="invalid-feedback">
                                Kategori harus diisi!
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="gejala" placeholder="gejala" required value="<?= old('gejala'); ?>" id="gejala">
                            <div class="invalid-feedback">
                                Gejala harus diisi!
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputGejala">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            // Javascript modal gejala
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                // get data
                $('.btn-tambah-gejala').on('click', function() {
                    //set data
                    document.getElementById("kategori").value = "";
                    document.getElementById("gejala").value = "";
                    //show modal
                    $('#gejalaModal').modal('show');
                    //unset session
                    const x = document.getElementById("alert");
                    x.style.display = "none";
                });
            });
        </script>
    </div>
    <!-- end tambah modal gejala -->

    <!-- Edit Gejala Modal -->
    <div class="modal fade" id="editGejalaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah gejala</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('update erorr')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert">
                        <?= session()->getFlashdata('update erorr'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#editGejalaModal').modal('show');
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/updateGejala/" method="post" class="needs-validation" novalidate name="formEditGejala">
                    <div class="modal-body">
                        <input type="hidden" name="id_gejala" id="id_gejala">
                        <div class="form-group">
                            <label for="editKategori">Kategori</label>
                            <input class="form-control" type="text" name="kategori" required value="<?= old('kategori'); ?>" id="editKategori">
                            <div class="invalid-feedback">
                                Kategori harus diisi!
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editGejala">Gejala</label>
                            <input class="form-control" type="text" name="gejala" required value="<?= old('gejala'); ?>" id="editGejala">
                            <div class="invalid-feedback">
                                Gejala harus diisi!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputGejala">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Javascript modal edit gejala
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByName('formEditGejala');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                // get data
                $('.btn-edit-gejala').on('click', function() {
                    const id_gejala = $(this).data('id');
                    const kategori = $(this).data('kategori');
                    const gejala = $(this).data('gejala');
                    //set data
                    $('#id_gejala').val(id_gejala);
                    $('#editKategori').val(kategori);
                    $('#editGejala').val(gejala);
                    //show modal
                    $('#editGejalaModal').modal('show');
                });
            });
        </script>
    </div>
    <!-- End Edit Gejala -->

    <!-- Delete Gejala Modal -->
    <div class="modal fade" id="deleteGejalaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleDeleteGejala">Hapus Gejala</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus gejala ini?
                </div>
                <form action="/admin/deleteGejala/" method="post">
                    <input type="hidden" name="id_gejala" id="id_delete">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // get data
            $('.btn-delete').on('click', function() {
                const id_gejala = $(this).data('id');
                const kategori = $(this).data('kategori');
                const gejala = $(this).data('gejala');
                //set data
                $('#id_delete').val(id_gejala);
                $('#titleDeleteGejala').text("Hapus Gejala " + kategori + " " + gejala);
                //show modal
                $('#deleteGejalaModal').modal('show');
            });
        });
    </script>
    <!-- End Delete Gejala -->

    <!-- Modal Tambah Penyakit  -->
    <div class="modal fade" id="modalTambahPenyakit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Penyakit</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('erorr-tambah-penyakit')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert" id="alert">
                        <?= session()->getFlashdata('erorr-tambah-penyakit'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#modalTambahPenyakit').modal();
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/tambahPenyakit/" method="post" class="needs-validation" novalidate id="formTambahPenyakit">
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="text" name="penyakit" placeholder="Penyakit" required value="<?= old('penyakit'); ?>" id="penyakit">
                            <div class="invalid-feedback">
                                Penyakit harus diisi!
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="obat" placeholder="Obat" required value="<?= old('obat'); ?>" id="obat">
                            <div class="invalid-feedback">
                                Obat harus diisi!
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" required type="text" name="solusi" placeholder="Solusi" id="solusi"><?= old('solusi'); ?></textarea>
                            <div class="invalid-feedback">
                                Solusi harus diisi!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputPenyakit">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            // Javascript modal penyakit
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                // get data
                $('.btn-tambah-penyakit').on('click', function() {
                    //set data
                    document.getElementById("penyakit").value = "";
                    document.getElementById("obat").value = "";
                    document.getElementById("solusi").value = "";
                    //show modal
                    $('#modalTambahPenyakit').modal('show');
                    //unset session
                    const x = document.getElementById("alert");
                    x.style.display = "none";
                });
            });
        </script>
    </div>
    <!-- end modal tambah penyakit -->

    <!-- Edit Penyakit Modal -->
    <div class="modal fade" id="editPenyakitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleEditPenyakit">Ubah Penyakit</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('update penyakit erorr')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert">
                        <?= session()->getFlashdata('update penyakit erorr'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#editPenyakitModal').modal('show');
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/updatePenyakit/" method="post" class="needs-validation" novalidate id="formUpdatePenyakit">
                    <div class="modal-body">
                        <input type="hidden" name="id_penyakit" id="id_penyakit">
                        <div class="form-group">
                            <label for="editObat">Obat</label>
                            <input class="form-control" type="text" name="obat" placeholder="Obat" required value="<?= old('obat'); ?>" id="editObat">
                            <div class="invalid-feedback">
                                Obat harus diisi!
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editSolusi">Solusi</label>
                            <textarea class="form-control" required type="text" name="solusi" placeholder="Solusi" id="editSolusi"><?= old('solusi'); ?></textarea>
                            <div class="invalid-feedback">
                                Solusi harus diisi!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputPenyakit">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Javascript modal edit penyakit
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByName('formEditGejala');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                $('.btn-edit-penyakit').on('click', function() {
                    // get data
                    const id = $(this).data('id');
                    const penyakit = $(this).data('penyakit');
                    const obat = $(this).data('obat');
                    const solusi = $(this).data('solusi');

                    //set data
                    $('#id_penyakit').val(id);
                    $('#titleEditPenyakit').text("Ubah Penyakit " + penyakit);
                    $('#editObat').val(obat);
                    $('#editSolusi').val(solusi);

                    //show modal
                    $('#editPenyakitModal').modal('show');
                });
            });
        </script>
    </div>
    <!-- End Edit penyakit -->

    <!-- Modal Delete Penyakit -->
    <div class="modal fade" id="modalDeletePenyakit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleDeletePenyakit">Hapus Penyakit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus penyakit ini?
                </div>
                <form action="/admin/deletePenyakit/" method="post">
                    <input type="hidden" name="id_penyakit" id="idDeletePenyakit">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.btn-delete-penyakit').on('click', function() {
                // get data
                const id_penyakit = $(this).data('id');
                const penyakit = $(this).data('penyakit');

                //set data
                $('#idDeletePenyakit').val(id_penyakit);
                $('#titleDeletePenyakit').text("Hapus Penyakit " + penyakit);

                //show modal
                $('#modalDeletePenyakit').modal('show');
            });
        });
    </script>
    <!-- End Modal Delete Penyakit -->

    <!-- Modal Tambah Sample  -->
    <div class="modal fade" id="modalTambahSample" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Sample</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('erorr-tambah-sample')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert" id="alert">
                        <?= session()->getFlashdata('erorr-tambah-sample'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#modalTambahSample').modal();
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/tambahSample/" method="post" class="needs-validation" novalidate id="formTambahSample">
                    <div class="modal-body">
                        <?php if ($title == "Data Sample") : ?>
                            <div class="form-group">
                                <label for="sampleGejala">Gejala</label>
                                <select class="custom-select" required id='sampleGejala' name="sampleGejala">
                                    <option value="">Klik untuk pilih gejala</option>
                                    <?php foreach ($gejala as $g) : ?>
                                        <option value="<?= $g['id_gejala']; ?>"><?= $g['kategori']; ?> - <?= $g['gejala']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Gejala harus dipilih!</div>
                            </div>
                            <div class="form-group">
                                <label for="samplePenyakit">Penyakit</label>
                                <select class="custom-select" required id="samplePenyakit" name="samplePenyakit">
                                    <option value="">Klik untuk pilih penyakit</option>
                                    <?php foreach ($penyakit as $p) : ?>
                                        <option value="<?= $p['id_penyakit']; ?>"><?= $p['penyakit']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Penyakit harus dipilih!</div>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputPenyakit">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            // Javascript modal tambah sample
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                // get data
                $('.btn-tambah-sample').on('click', function() {
                    //set data
                    document.getElementById("penyakit").value = "";
                    document.getElementById("obat").value = "";
                    document.getElementById("solusi").value = "";
                    //show modal
                    $('#modalTambahSample').modal('show');
                    //unset session
                    const x = document.getElementById("alert");
                    x.style.display = "none";
                });
            });
        </script>
    </div>
    <!-- end modal tambah sample -->

    <!-- Modal Edit Sample -->
    <div class="modal fade" id="editSampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleEditSample">Ubah Sample</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php if (session()->getFlashdata('update sample erorr')) : ?>
                    <div class="alert alert-danger" role="alert" name="alert">
                        <?= session()->getFlashdata('update sample erorr'); ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#modalEditSample').modal('show');
                        });
                    </script>
                <?php endif; ?>
                <form action="/admin/updateSample/" method="post" class="needs-validation" novalidate id="formUpdateSample">
                    <input type="hidden" name="id_sample" id="id_sample">
                    <div class="modal-body">
                        <?php if ($title == "Data Sample") : ?>
                            <div class="form-group sample-gejala">
                                <label for="sampleGejala">Gejala</label>
                                <select class="custom-select" required id='sampleGejala' name="sampleGejala">
                                    <option value="">Klik untuk pilih gejala</option>
                                    <?php foreach ($gejala as $g) : ?>
                                        <option value="<?= $g['id_gejala']; ?>"><?= $g['kategori']; ?> - <?= $g['gejala']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Gejala harus dipilih!</div>
                            </div>
                            <div class="form-group sample-penyakit">
                                <label for="samplePenyakit">Penyakit</label>
                                <select class="custom-select" required id="samplePenyakit" name="samplePenyakit">
                                    <option value="">Klik untuk pilih penyakit</option>
                                    <?php foreach ($penyakit as $p) : ?>
                                        <option value="<?= $p['id_penyakit']; ?>"><?= $p['penyakit']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Penyakit harus dipilih!</div>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="inputSample">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Javascript modal edit sample
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByName('formEditSample');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <script>
            $(document).ready(function() {
                $('.btn-edit-sample').on('click', function() {
                    // get data
                    const id_sample = $(this).data('id_sample');
                    const id_gejala = $(this).data('id_gejala');
                    const id_penyakit = $(this).data('id_penyakit');


                    //set data
                    $('#id_sample').val(id_sample);
                    $('div.sample-gejala select').val(id_gejala);
                    $('div.sample-penyakit select').val(id_penyakit);


                    //show modal
                    $('#editSampleModal').modal('show');
                });
            });
        </script>
    </div>
    <!-- End Modal Edit Sample -->

    <!-- Modal Delete Sample-->
    <div class="modal fade" id="modalDeleteSample" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleDeleteSample">Hapus Sample</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus sample ini?
                </div>
                <form action="/admin/deleteSample/" method="post">
                    <input type="hidden" id="idDeleteSample" name=" id_sample">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // get data
            $('.btn-delete-sample').on('click', function() {
                const id_sample = $(this).data('sample');
                const kategori = $(this).data('kategori');
                const gejala = $(this).data('gejala');
                const penyakit = $(this).data('penyakit');
                //set data
                $('#idDeleteSample').val(id_sample);
                $('#titleDeleteSample').text("Hapus Sample " + kategori + ' ' + gejala + " = " + penyakit);
                //show modal
                $('#modalDeleteSample').modal('show');
            });
        });
    </script>
    <!-- End Modal Delete Sample -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url(); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url(); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url(); ?>/js/sb-admin-2.min.js"></script>

    <script>
        function previewImage() {
            const image = document.querySelector('#user_image');
            const imageLabel = document.querySelector('.custom-file-label');
            const imgPreview = document.querySelector('.img-preview');

            imageLabel.textContent = image.files[0].name;

            const fileImage = new FileReader();
            fileImage.readAsDataURL(image.files[0]);

            fileImage.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
    </script>

</body>

</html>