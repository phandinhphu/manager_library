<div class="content__header">
    <h1 class="content__header-title">Account manager</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        <?= $title ?>
                    </h3>
                </div>
                <div class="card__body">
                    <form id="form" class="form" action="">
                        <div class="form__group">
                            <div class="img__label">
                                <img
                                    src="<?= !isset($account['avatar']) ? WEB_ROOT . '/uploads/no-image.png' :
                                        WEB_ROOT . '/' . $account['avatar'] ?>"
                                    alt=""
                                    class="avatar"
                                    title="Avatar" />
                                <input
                                    type="file"
                                    class="form__input"
                                    id="avatar"
                                    name="img" />
                            </div>
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="user_name"
                                class="form__label">Tên độc giả</label>
                            <input
                                rules="required"
                                type="text"
                                name="user_name"
                                id="user_name"
                                class="form__input"
                                value="<?= isset($account['user_name']) ? $account['user_name'] : '' ?>"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="address"
                                class="form__label">Địa chỉ</label>
                            <input
                                rules="required"
                                type="text"
                                name="address"
                                id="address"
                                class="form__input"
                                value="<?= isset($account['address']) ? $account['address'] : '' ?>"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="email"
                                class="form__label">Email</label>
                            <input
                                rules="required|email"
                                type="email"
                                name="email"
                                id="email"
                                class="form__input"
                                value="<?= isset($account['email']) ? $account['email'] : '' ?>"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="cccd"
                                class="form__label">Số CCCD</label>
                            <input
                                rules="required"
                                type="text"
                                name="cccd"
                                id="cccd"
                                class="form__input"
                                value="<?= isset($account['cccd']) ? $account['cccd'] : '' ?>"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="phone"
                                class="form__label">Phone</label>
                            <input
                                rules="required|phone"
                                type="text"
                                name="phone"
                                id="phone"
                                class="form__input"
                                value="<?= isset($account['phone_number']) ? $account['phone_number'] : '' ?>"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="password"
                                class="form__label">Mật khẩu</label>
                            <input
                                rules="min:6"
                                type="password"
                                name="password"
                                id="password"
                                class="form__input"
                            />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="rpassword"
                                class="form__label">Nhập lại mật khẩu</label>
                            <input
                                rules="confirmation"
                                type="password"
                                name="rpassword"
                                id="rpassword"
                                class="form__input" />
                            <span
                                class="form__message"></span>
                        </div>
                        <div class="form__group">
                            <button
                                type="submit"
                                class="btn">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
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

<script src="<?= WEB_ROOT . '/public/assets/admin/js/validator.js' ?>"></script>

<script>
    let avatar = $(".avatar");
    let inputImg = $("#avatar");

    inputImg.onchange = () => {
        const [file] = inputImg.files;
        if (file) {
            avatar.src = URL.createObjectURL(file);
        }
    };
</script>

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
    }

    new Validator("#form").onSubmit = async (data) => {
        const formData = new FormData();

        for (const key in data) {
            if (key === "img") {
                formData.append(key, data[key][0]);
                continue;
            }
            formData.append(key, data[key]);
        }

        const req = await fetch("<?= WEB_ROOT . $action ?>", {
            method: "POST",
            body: formData,
        });

        const { status, message } = await req.json();

        if (status === "success") {
            customAlert(message, 'success');
        } else {
            customAlert(message);
        }
    }
</script>