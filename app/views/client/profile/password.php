<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-12 component">
                <div class="title">
                    <h2 class="title__text">Thông tin cá nhân</h2>
                </div>

                <div class="component__content">
                    <div class="row">
                        <div class="grCol grL-4">
                            <div class="component">
                                <div class="title">
                                    <h3 class="title__text">Tài khoản của tôi</h3>
                                </div>
                                <div class="component__content">
                                    <ul class="list__groups">
                                        <li class="list__group-item">
                                            <a 
                                                class="list__group-item-link <?= $tab == 'info' ? 'selected' : '' ?>" 
                                                href="<?= WEB_ROOT . '/thong-tin-ca-nhan' ?>"
                                            >Hồ sơ</a>
                                        </li>
                                        <li class="list__group-item">
                                            <a 
                                                class="list__group-item-link <?= $tab == 'password' ? 'selected' : '' ?>" 
                                                href="<?= WEB_ROOT . '/doi-mat-khau' ?>"
                                            >Đổi mật khẩu</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="grCol grL-8">
                            <div class="component">
                                <div class="title">
                                    <h3 class="title__text">Hồ sơ của tôi</h3>
                                    <div class="alert" role="alert">
                                        <strong></strong>
                                    </div>
                                </div>
                                <div class="component__content">
                                    <form id="form__auth">
                                        <div class="form__auth-group">
                                            <label for="password" class="form__auth-label">Mật khẩu hiện tại</label>
                                            <input type="password" name="password" rules="min:6" id="password" class="form__auth-input" value="">
                                            <span class="form__auth-message"></span>
                                        </div>
                                        <div class="form__auth-group">
                                            <label for="new_password" class="form__auth-label">Mật khẩu mới</label>
                                            <input type="password" name="new_password" rules="min:6" id="new_password" class="form__auth-input" value="">
                                            <span class="form__auth-message"></span>
                                        </div>
                                        <div class="form__auth-group">
                                            <label for="rpassword" class="form__auth-label">Nhập lại mật khẩu mới</label>
                                            <input type="password" name="rpassword" rules="confirmation" id="rpassword" class="form__auth-input" value="">
                                            <span class="form__auth-message"></span>
                                        </div>
                                        <button type="submit" class="btn js-save-info">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="toast-container position-fixed top-100 end-0 p-3">
    <div id="myToast" class="toast fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" style="font-size: 1.6rem">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" style="font-size: 1.5rem">
            Đây là nội dung thông báo.
        </div>
    </div>
</div>

<script src="<?= WEB_ROOT . '/public/assets/client/js/validator.js' ?>"></script>

<script>
    const toastContainer = document.querySelector('.toast-container');
    const toastElement = document.getElementById('myToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });

    const customAlert = (message, type = 'danger') => {
        toastContainer.classList.remove('top-100');
        toastContainer.classList.add('top-0');
        toastElement.querySelector('.toast-body').textContent = message;
        toastElement.querySelector('.toast-header').classList.add(`bg-${type}`, 'text-white');
        toast.show();
    };

    new Validator('#form__auth').onSubmit = async (data) => {
        const formData = new FormData();
        for (const key in data) {
            formData.append(key, data[key]);
        }

        const response = await fetch("<?= WEB_ROOT . '/doi-mat-khau' ?>", {
            method: "POST",
            body: formData,
        });

        const { status, message } = await response.json();

        if (status === 'success') {
            customAlert(message, 'success');
        } else {
            customAlert(message);
        }
    };
</script>