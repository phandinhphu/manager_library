<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-6 grL-o-3 component">
                <div class="title">
                    <h2 class="title__text">Quên mật khẩu</h2>
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
                        <div class="form__auth-group">
                            <button type="submit" class="btn" style="margin-bottom: 1.2rem;">Gửi</button>
                            <a href="<?= WEB_ROOT . '/dang-nhap' ?>" class="btn">Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLoading" aria-hidden="true" aria-labelledby="modalLoadingLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-2" id="modalDetailLabel2">Loading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Spinner ẩn đi lúc đầu -->
                <div id="spinner" class="spinner-border text-primary" role="status" style="display: inline-block;">
                    <span class="visually-hidden">Đang gửi...</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#modalViewStatistical" data-bs-toggle="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= WEB_ROOT . '/public/assets/client/js/validator.js' ?>"></script>
<script>
    let modalView = document.getElementById("modalLoading");

    new Validator('#form__auth').onSubmit = async (data) => {
        jQuery("#modalLoading").modal("show");

        const formData = new FormData();
        for (const key in data) {
            formData.append(key, data[key]);
        }

        const req = await fetch('<?= WEB_ROOT . '/quen-mat-khau' ?>', {
            method: 'POST',
            body: formData
        });

        const { status, message } = await req.json();

        if (status === 'success') {
            modalView.querySelector('.modal-title').innerText = 'Success';
            modalView.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-success fs-2" role="alert">
                    ${message}
                </div>
            `;
        } else {
            modalView.querySelector('.modal-title').innerText = 'Error';
            modalView.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-danger fs-2" role="alert">
                    ${message}
                </div>
            `;
        }
    };
</script>