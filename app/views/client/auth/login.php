<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-6 grL-o-3 component">
                <div class="title">
                    <h2 class="title__text">Đăng nhập</h2>
                </div>

                <div class="component__content">
                    <form id="form__auth" class="form__auth" action="login.php" method="POST">
                        <div class="form__auth-group">
                            <label for="email" class="form__auth-label">Email</label>
                            <input type="email" class="form__auth-input" rules="required|email" id="email" name="email" placeholder="Vd: abc@gmail.com">
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label for="password" class="form__auth-label">Mật khẩu</label>
                            <input type="password" class="form__auth-input" rules="required|min:6" id="password" name="password" placeholder="Nhập mật khẩu">
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <button type="submit" class="btn" style="margin-bottom: 1.2rem;">Đăng nhập</button>
                            <a href="forgotpassword.html" class="btn">Quên mật khẩu</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= WEB_ROOT . '/public/plugins/validator.js' ?>"></script>
<script>
    new Validator('#form__auth').onSubmit = (data) => {
        console.log(data);
    }
</script>