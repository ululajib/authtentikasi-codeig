<h2>auth login</h2>

<?= form_open('login') ?>

<label for="">email</label>
<input type="email" name="email" value="<?= set_value('email') ?>"> <br>
<label for="">password</label>
<input type="password" name="password" value=""><br>

<input type="submit" name="submit" value="login">

<?= form_error('email') ?>
<?= form_error('password') ?>


<?= form_close() ?>
