<h2>auth Register</h2>
<?= form_open('register') ?>

<label for="">email</label>
<input type="email" name="email" value="<?= set_value('email') ?>"> <br>
<label for="">password</label>
<input type="password" name="password" value=""><br>
<label for="">ulangi password</label>
<input type="password" name="password2" value=""><br>

<input type="submit" name="submit" value="register">

<?= form_error('email') ?>
<?= form_error('password') ?>
<?= form_error('password2') ?>

<?= form_close() ?>
