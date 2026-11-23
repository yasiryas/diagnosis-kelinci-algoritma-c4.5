<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-label="Dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ready to Leave?</h5>
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

<!-- Tambah Gejala Modal -->

<div class="modal fade" id="gejalaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah gejala</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php if (session()->getFlashdata('erorr')) : ?>
                <div class="alert alert-danger" role="alert" id="alertGejala">
                    <?= session()->getFlashdata('erorr'); ?>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#gejalaModal').modal();
                    });
                </script>
            <?php endif; ?>
            <form action="/admin/tambahgejala" method="post" class="needs-validation" novalidate id="formGejala" data-ajax-form data-ajax-url="/admin/tambahgejala" data-ajax-method="post" data-ajax-action="create">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
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
        $(document).ready(function() {
            // get data
            $('.btn-tambah-gejala').on('click', function() {
                //set data
                document.getElementById("kategori").value = "";
                document.getElementById("gejala").value = "";
                //show modal
                $('#gejalaModal').modal('show');
                //unset session
                const x = document.getElementById("alertGejala");
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
                <h5 class="modal-title">Ubah gejala</h5>
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
            <form action="/admin/updategejala" method="post" class="needs-validation" novalidate name="formEditGejala" id="formEditGejala" data-ajax-form data-ajax-url="/admin/updategejala" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
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
                    <button type="submit" class="btn btn-primary" id="updateGejala">Save</button>
                </div>
            </form>
        </div>
    </div>

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
<div class="modal fade" id="deleteGejalaModal" tabindex="-1" aria-label="Dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleDeleteGejala">Hapus Gejala</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="bodyDeleteGejala">Dengan menghapus gejala, maka anda juga akan menghapus sample dengan gejala ini!</h6>
                <input type="checkbox" aria-label="Checkbox for following text input" id="setujuHapusGejala" onchange="document.getElementById('buttonDeleteGejala').disabled = !this.checked;"> Saya setuju!
            </div>

            <form action="/admin/deletegejala" method="post" data-ajax-form data-ajax-url="/admin/deletegejala" data-ajax-method="post" data-ajax-action="delete">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <input type="hidden" name="id_gejala" id="id_delete">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="buttonDeleteGejala" disabled>Delete</button>
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
            $('#bodyDeleteGejala').text("Dengan menghapus gejala ini, maka anda juga akan menghapus sample dengan gejala " + kategori + " " + gejala + "!");
            //show modal
            $('#deleteGejalaModal').modal('show');
        });
        // $('#setujuHapusGejala').on('click', function() {
        //     document.getElementById("buttonDeleteGejala").disabled = false;
        // });
    });

    // function enableButtonDeleteGejala() {
    //     document.getElementById("buttonDeleteGejala").disable = false;
    // }
</script>
<!-- End Delete Gejala -->

<!-- Modal Tambah Penyakit  -->
<div class="modal fade" id="modalTambahPenyakit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penyakit</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php if (session()->getFlashdata('erorr-tambah-penyakit')) : ?>
                <div class="alert alert-danger" role="alert" id="alertPenyakit">
                    <?= session()->getFlashdata('erorr-tambah-penyakit'); ?>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#modalTambahPenyakit').modal();
                    });
                </script>
            <?php endif; ?>
            <form action="/admin/tambahpenyakit" method="post" class="needs-validation" novalidate id="formTambahPenyakit" data-ajax-form data-ajax-url="/admin/tambahpenyakit" data-ajax-method="post" data-ajax-action="create">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
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
                const x = document.getElementById("alertPenyakit");
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
            <form action="/admin/updatepenyakit" method="post" class="needs-validation" novalidate id="formUpdatePenyakit" data-ajax-form data-ajax-url="/admin/updatepenyakit" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
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
                    <button type="submit" class="btn btn-primary" id="updatePenyakit">Save</button>
                </div>
            </form>
        </div>
    </div>

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
<div class="modal fade" id="modalDeletePenyakit" tabindex="-1" aria-label="Dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleDeletePenyakit">Hapus Penyakit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="bodyDeletePenyakit">Dengan menghapus penyakit, maka anda juga akan menghapus sample dengan penyakit ini!</h6>
                <input type="checkbox" aria-label="Checkbox for following text input" id="setujuHapusPenyakit" onchange="document.getElementById('buttonDeletePenyakit').disabled = !this.checked;"> Saya setuju!
            </div>
            <form action="/admin/deletepenyakit" method="post" data-ajax-form data-ajax-url="/admin/deletepenyakit" data-ajax-method="post" data-ajax-action="delete">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <input type="hidden" name="id_penyakit" id="idDeletePenyakit">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="buttonDeletePenyakit" disabled>Delete</button>
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
            $('#bodyDeletePenyakit').text("Dengan menghapus penyakit ini, maka anda juga akan menghapus sample dengan penyakit " + penyakit + "!");
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
                <h5 class="modal-title">Tambah Sample</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php if (session()->getFlashdata('erorr-tambah-sample')) : ?>
                <div class="alert alert-danger" role="alert" id="alertSample">
                    <?= session()->getFlashdata('erorr-tambah-sample'); ?>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#modalTambahSample').modal();
                    });
                </script>
            <?php endif; ?>
            <form action="/admin/tambahsample" method="post" class="needs-validation" novalidate id="formTambahSample" data-ajax-form data-ajax-url="/admin/tambahsample" data-ajax-method="post" data-ajax-action="create">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <?php if ($title == "Data Sample") : ?>
                        <div class="form-group">
                            <label for="sampleGejala">Gejala</label>
                            <select class="custom-select" required id="sampleGejala" name="sampleGejala">
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
                    <button type="submit" class="btn btn-primary" id="inputSample">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // get data
            $('.btn-tambah-sample').on('click', function() {
                document.getElementById("sampleGejala").value = "";
                document.getElementById("samplePenyakit").value = "";
                //show modal
                $('#modalTambahSample').modal('show');
                //unset session
                const x = document.getElementById("alertSample");
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
                        $('#editSampleModal').modal('show');
                    });
                </script>
            <?php endif; ?>
            <form action="/admin/updatesample" method="post" class="needs-validation" novalidate id="formUpdateSample" data-ajax-form data-ajax-url="/admin/updatesample" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <input type="hidden" name="id_sample" id="id_sample">
                <div class="modal-body">
                    <?php if ($title == "Data Sample") : ?>
                        <div class="form-group sample-gejala">
                            <label for="editSampleGejala">Gejala</label>
                            <select class="custom-select" required id="editSampleGejala" name="sampleGejala">
                                <option value="">Klik untuk pilih gejala</option>
                                <?php foreach ($gejala as $g) : ?>
                                    <option value="<?= $g['id_gejala']; ?>"><?= $g['kategori']; ?> - <?= $g['gejala']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Gejala harus dipilih!</div>
                        </div>
                        <div class="form-group sample-penyakit">
                            <label for="editSamplePenyakit">Penyakit</label>
                            <select class="custom-select" required id="editSamplePenyakit" name="samplePenyakit">
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
                    <button type="submit" class="btn btn-primary" id="updateSample">Save</button>
                </div>
            </form>
        </div>
    </div>

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
<div class="modal fade" id="modalDeleteSample" tabindex="-1" aria-label="Dialog" aria-hidden="true">
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
            <form action="/admin/deletesample" method="post" data-ajax-form data-ajax-url="/admin/deletesample" data-ajax-method="post" data-ajax-action="delete">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <input type="hidden" id="idDeleteSample" name="id_sample">
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

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-label="Edit profil" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="/user/updateprofile" method="post" enctype="multipart/form-data" data-ajax-form data-ajax-url="/user/updateprofile" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="profileUsername">Username</label>
                        <input type="text" class="form-control" id="profileUsername" name="username" value="<?= esc(user()->username); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="profileFullname">Nama lengkap</label>
                        <input type="text" class="form-control" id="profileFullname" name="fullname" value="<?= esc(user()->fullname); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="profileImage">Foto profil</label>
                        <input type="file" class="form-control-file" id="profileImage" name="user_image" accept="image/png,image/jpeg,image/svg+xml">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-label="Ganti password" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="/user/updatepassword" method="post" data-ajax-form data-ajax-url="/user/updatepassword" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="passwordOld">Password lama</label>
                        <input type="password" class="form-control" id="passwordOld" name="oldpassword" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordNew">Password baru</label>
                        <input type="password" class="form-control" id="passwordNew" name="newpassword" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordRepeat">Ulangi password baru</label>
                        <input type="password" class="form-control" id="passwordRepeat" name="repeatnewpassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Diagnosis-->
<div class="modal fade" id="modalDiagnosis" tabindex="-1" aria-label="Dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleDiagnosis">Diagnosis Kelinci</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Gejala apa yang dialami kelinci anda?
            </div>
            <form action="/user/prosesdiagnosis" method="get">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // get data
        //enable jika menggunakan modal
        // $('.btn-diagnosis').on('click', function() {
        $('.btn-diagnosis-enable').on('click', function() {
            const id_sample = $(this).data('sample');
            const kategori = $(this).data('kategori');
            const gejala = $(this).data('gejala');
            const penyakit = $(this).data('penyakit');
            //set data
            //$('#idDeleteSample').val(id_sample);
            //$('#titleDeleteSample').text("Hapus Sample " + kategori + ' ' + gejala + " = " + penyakit);
            //show modal
            //enable jika menggunakan modal
            $('#modalDiagnosis').modal('show');
        });
    });
</script>

<div class="modal fade" id="artikelModal" tabindex="-1" role="dialog" aria-labelledby="artikelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="artikelModalLabel">Tambah artikel</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/admin/tambahartikel" method="post" class="needs-validation" novalidate id="formArtikel" data-ajax-form data-ajax-url="/admin/tambahartikel" data-ajax-method="post" data-ajax-action="create">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="artikelJudul">Judul</label>
                        <input class="form-control" type="text" id="artikelJudul" name="judul" required>
                        <div class="invalid-feedback">Judul harus diisi!</div>
                    </div>
                    <div class="form-group">
                        <label for="artikelSlug">Slug <small class="text-muted">(kosongkan untuk otomatis)</small></label>
                        <input class="form-control" type="text" id="artikelSlug" name="slug">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="artikelKategori">Kategori</label>
                            <select class="form-control" id="artikelKategori" name="kategori_id" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategori ?? [] as $id => $nama) : ?>
                                    <option value="<?= (int) $id; ?>"><?= esc($nama); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Kategori harus dipilih!</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="artikelStatus">Status</label>
                            <select class="form-control" id="artikelStatus" name="status" required>
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="artikelPublishedAt">Diterbitkan pada</label>
                        <input class="form-control" type="datetime-local" id="artikelPublishedAt" name="published_at">
                    </div>
                    <div class="form-group">
                        <label for="artikelRingkasan">Ringkasan</label>
                        <textarea class="form-control" id="artikelRingkasan" name="ringkasan" rows="2" required></textarea>
                        <div class="invalid-feedback">Ringkasan harus diisi!</div>
                    </div>
                    <div class="form-group">
                        <label for="artikelKonten">Konten</label>
                        <textarea class="form-control" id="artikelKonten" name="konten" rows="5" required></textarea>
                        <div class="invalid-feedback">Konten harus diisi!</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editArtikelModal" tabindex="-1" role="dialog" aria-labelledby="editArtikelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editArtikelModalLabel">Ubah artikel</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/admin/updateartikel" method="post" class="needs-validation" novalidate id="formEditArtikel" data-ajax-form data-ajax-url="/admin/updateartikel" data-ajax-method="post" data-ajax-action="update">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <input type="hidden" id="editIdArtikel" name="id">
                    <div class="form-group">
                        <label for="editArtikelJudul">Judul</label>
                        <input class="form-control" type="text" id="editArtikelJudul" name="judul" required>
                        <div class="invalid-feedback">Judul harus diisi!</div>
                    </div>
                    <div class="form-group">
                        <label for="editArtikelSlug">Slug</label>
                        <input class="form-control" type="text" id="editArtikelSlug" name="slug">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editArtikelKategori">Kategori</label>
                            <select class="form-control" id="editArtikelKategori" name="kategori_id" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategori ?? [] as $id => $nama) : ?>
                                    <option value="<?= (int) $id; ?>"><?= esc($nama); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Kategori harus dipilih!</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editArtikelStatus">Status</label>
                            <select class="form-control" id="editArtikelStatus" name="status" required>
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editArtikelPublishedAt">Diterbitkan pada</label>
                        <input class="form-control" type="datetime-local" id="editArtikelPublishedAt" name="published_at">
                    </div>
                    <div class="form-group">
                        <label for="editArtikelRingkasan">Ringkasan</label>
                        <textarea class="form-control" id="editArtikelRingkasan" name="ringkasan" rows="2" required></textarea>
                        <div class="invalid-feedback">Ringkasan harus diisi!</div>
                    </div>
                    <div class="form-group">
                        <label for="editArtikelKonten">Konten</label>
                        <textarea class="form-control" id="editArtikelKonten" name="konten" rows="5" required></textarea>
                        <div class="invalid-feedback">Konten harus diisi!</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteArtikelModal" tabindex="-1" role="dialog" aria-labelledby="deleteArtikelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArtikelModalLabel">Hapus artikel</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/admin/deleteartikel" method="post" id="formDeleteArtikel" data-ajax-form data-ajax-url="/admin/deleteartikel" data-ajax-method="post" data-ajax-action="delete">
                <?= csrf_field(); ?>
                <div class="alert alert-danger d-none" data-error-summary role="alert" tabindex="-1"></div>
                <div class="modal-body">
                    <input type="hidden" id="deleteIdArtikel" name="id">
                    <p id="deleteArtikelPesan">Yakin ingin menghapus artikel ini?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.btn-tambah-artikel').on('click', function() {
            document.getElementById('formArtikel').reset();
            $('#artikelModal').modal('show');
        });

        $('.btn-edit-artikel').on('click', function() {
            const button = $(this);
            $('#editIdArtikel').val(button.data('id'));
            $('#editArtikelJudul').val(button.data('judul'));
            $('#editArtikelSlug').val(button.data('slug'));
            $('#editArtikelKategori').val(button.data('kategori-id'));
            $('#editArtikelStatus').val(button.data('status'));
            $('#editArtikelPublishedAt').val(String(button.data('published-at') || '').replace(' ', 'T'));
            $('#editArtikelRingkasan').val(button.data('ringkasan'));
            $('#editArtikelKonten').val(button.data('konten'));
            document.getElementById('formEditArtikel').dataset.rowId = String(button.data('row-id'));
            $('#editArtikelModal').modal('show');
        });

        $('.btn-delete-artikel').on('click', function() {
            const button = $(this);
            $('#deleteIdArtikel').val(button.data('id'));
            $('#deleteArtikelPesan').text('Yakin ingin menghapus artikel ' + button.data('judul') + '?');
            document.getElementById('formDeleteArtikel').dataset.rowId = String(button.data('id'));
            $('#deleteArtikelModal').modal('show');
        });
    });
</script>