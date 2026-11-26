<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">User List</h1>
            <form action="" method="GET" data-live-search data-page-param="page_users">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Cari pengguna" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div data-admin-list>
                <?= $this->include('admin/fragments/userlist'); ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>