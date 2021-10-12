 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('index.php'); ?>">
         <div class="sidebar-brand-icon rotate-n-15">
             <i class="fas fa-stethoscope"></i>
         </div>
         <div class="sidebar-brand-text mx-3">Diagnosic Rabbit</div>
     </a>


     <!-- Divider -->
     <hr class="sidebar-divider">


     <?php if (in_groups('admin')) : ?>

         <!-- Menu Admin -->

         <!-- Nav Item - Dashboard Admin-->
         <li class="nav-item <?= ($title == 'Dashboard') ? 'active' : ''; ?>">
             <a class="nav-link" href="<?= base_url('admin/'); ?>">
                 <i class="fas fa-tachometer-alt"></i>
                 <span>Dashboard</span></a>
         </li>

         <!-- Nav Item - User List -->
         <li class="nav-item <?= ($title == 'User List') ? 'active' : ''; ?>">
             <a class="nav-link" href="<?= base_url('admin/userlist'); ?>">
                 <i class="fas fa-users"></i>
                 <span>User List</span></a>
         </li>

         <!-- Nav Item - Management data -->
         <li class="nav-item <?= ($title == 'Management Data' || $title == 'Gejala' || $title == 'Penyakit' || $title == 'Data Diagnosis') ? 'active' : ''; ?>">
             <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                 <i class="fas fa-tasks"></i>
                 <span>Management Data</span>
             </a>
             <div id="collapseThree" class="collapse <?= ($title == 'Management Data' || $title == 'Data Gejala' || $title == 'Data Penyakit' || $title == 'Data Sample') ? 'show' : ''; ?>" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                 <div class="bg-white py-2 collapse-inner rounded">
                     <a class="collapse-item <?= ($title == 'Data Gejala') ? 'active' : ''; ?>" href="<?= base_url('admin/gejala'); ?>">Data Gejala</a>
                     <a class="collapse-item <?= ($title == 'Data Penyakit') ? 'active' : ''; ?>" href="<?= base_url('admin/penyakit'); ?>">Data Penyakit</a>
                     <a class="collapse-item <?= ($title == 'Data Sample') ? 'active' : ''; ?>" href="<?= base_url('admin/datasample'); ?>">Data Sample</a>
                 </div>
             </div>
         </li>

         <!-- Nav Item - Decission Tree -->
         <li class="nav-item <?= ($title == 'Decision Tree') ? 'active' : ''; ?>">
             <a class="nav-link" href="<?= base_url('admin/decisiontree'); ?>">
                 <i class="fas fa-code-branch"></i>
                 <span>Decision Tree</span></a>
         </li>


     <?php elseif (in_groups('user')) : ?>

         <!-- Menu User -->

         <!-- Nav Item - Dashboard User-->
         <li class="nav-item <?= ($title == 'Dashboard') ? 'active' : ''; ?>">
             <a class="nav-link" href="<?= base_url('user/index'); ?>">
                 <i class="fas fa-tachometer-alt"></i>
                 <span>Dashboard</span></a>
         </li>


         <!-- Nav Item - Diagnosis -->
         <li class="nav-item <?= ($title == 'Diagnosis') ? 'active' : ''; ?>">
             <a class="nav-link" href="<?= base_url('user/diagnosis'); ?>">
                 <i class="fas fa-stethoscope"></i>
                 <span>Diagnosis</span></a>
         </li>



     <?php endif; ?>


     <!-- Nav Item - My Profile -->
     <li class="nav-item <?= ($title == 'My Profile') ? 'active' : ''; ?>">
         <a class="nav-link" href="<?= base_url('user/profile'); ?>">
             <i class="fas fa-user"></i>
             <span>My Profile</span></a>
     </li>

     <!-- Nav Item - Logout -->
     <li class="nav-item">
         <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
             <i class="fas fa-sign-out-alt"></i>
             <span>Logout</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>

 </ul>