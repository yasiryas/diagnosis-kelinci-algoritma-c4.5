<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">User List</h1>
            <form action="" method="GET">
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

            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 + ($page * ($currentPage - 1));
                    foreach ($users as $user) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $user['username']; ?></td>
                            <td><?= $user['email'] ?></td>
                            <td>
                                <span class="badge badge-<?= ($user['name'] == 'admin') ? 'success' : 'warning'; ?> px-3 py-2">
                                    <?= esc($user['name']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/detail/' . $user['userid']); ?>" class="btn btn-info">detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data pengguna.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <?= $pager->links('users', 'user_pagination') ?>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>