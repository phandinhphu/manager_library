<div class="container__login">
    <form id="form-login" class="grCol grL-6 grL-o-3 grM-6 grM-o-3 grC-12" action="<?= WEB_ROOT . '/quan-tri/dang-nhap' ?>" method="post">
        <h3 class="heading">Đăng nhập</h3>

        <div class="spacer"></div>

        <div class="form__group">
            <label for="email" class="form__label">Email</label>
            <input type="email" class="form__input" rules="required|email" id="email" name="email" placeholder="Vd: abc@gmail.com">
            <span class="form__message">
                <?php if(isset($errors['email_err'])) : ?>
                    <?= $errors['email_err'] ?>
                <?php endif; ?>
            </span>
        </div>

        <div class="form__group">
            <label for="password" class="form__label">Mật khẩu</label>
            <input type="password" class="form__input" rules="required|min:6" id="password" name="password" placeholder="Nhập mật khẩu">
            <span class="form__message">
                <?php if(isset($errors['password_err'])) : ?>
                    <?= $errors['password_err'] ?>
                <?php endif; ?>
            </span>
        </div>

        <div class="form__group">
            <button type="submit" class="form__submit">Đăng nhập</button>
        </div>
    </form>
</div>

<script src="<?= WEB_ROOT . '/public/assets/admin/js/validator.js' ?>"></script>
<script>
</script>