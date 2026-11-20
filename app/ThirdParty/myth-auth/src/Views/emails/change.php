<p>Someone requested a password change at this email address for <?= site_url() ?>.</p>

<p>To change the password use this code or URL and follow the instructions.</p>

<p>Your Code: <?= $hash ?></p>

<p>Visit the <a href="<?= site_url('change-password') . '?token=' . $hash ?>">Change Form</a>.</p>

<br>

<p>If you did not request a password change, you can safely ignore this email.</p>