<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">User List</h1>
            <form action="" method="POST">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search user" name="keyword">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">

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
                                <button type="text" class="btn btn-<?= ($user['name'] == 'admin') ? 'success' : 'warning'; ?>">
                                    <?= $user['name']; ?>
                                </button>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/detail/' . $user['userid']); ?>" class="btn btn-info">detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pager->links('users', 'user_pagination') ?>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>