<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-6 grL-o-3 component">
                <div class="title">
                    <h2 class="title__text">Đăng nhập</h2>
                </div>

                <div class="component__content">
                    <form id="form__auth" class="form__auth" action="<?= WEB_ROOT . '/dang-nhap' ?>" method="post">
                        <div class="form__auth-group <?= isset($errors['email_err']) ? 'invalid' : '' ?>">
                            <label for="email" class="form__auth-label">Email</label>
                            <input type="email" class="form__auth-input" rules="required|email" id="email" name="email" placeholder="Vd: abc@gmail.com">
                            <span class="form__auth-message">
                                <?php if (isset($errors['email_err'])) : ?>
                                    <?= $errors['email_err'] ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="form__auth-group <?= isset($errors['password_err']) ? 'invalid' : '' ?>">
                            <label for="password" class="form__auth-label">Mật khẩu</label>
                            <input type="password" class="form__auth-input" rules="required|min:6" id="password" name="password" placeholder="Nhập mật khẩu">
                            <span class="form__auth-message">
                                <?php if (isset($errors['password_err'])) : ?>
                                    <?= $errors['password_err'] ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="form__auth-group">
                            <button type="submit" class="btn" style="margin-bottom: 1.2rem;">Đăng nhập</button>
                            <a href="<?= WEB_ROOT . '/quen-mat-khau' ?>" class="btn">Quên mật khẩu</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= WEB_ROOT . '/public/plugins/validator.js' ?>"></script>
<script>
    new Validator('#form__auth');
</script>