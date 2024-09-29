<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-6 grL-o-3 component">
                <div class="title">
                    <h2 class="title__text">Đăng ký</h2>
                </div>

                <div class="component__content">
                    <form
                        id="form__auth"
                        class="form__auth"
                        action="login.php"
                        method="POST"
                    >
                        <div class="form__auth-group">
                            <div class="avatar__label">
                                <img
                                    src="<?= WEB_ROOT . '/uploads/no-image.png' ?>"
                                    alt="Ảnh đại diện"
                                    class="avatar"
                                    title="Avatar"
                                />
                                <input
                                    type="file"
                                    class="form__auth-input"
                                    id="avatar"
                                    name="avatar"
                                />
                            </div>
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="username"
                                class="form__auth-label"
                            >Tên đăng nhập</label
                            >
                            <input
                                type="text"
                                class="form__auth-input"
                                rules="required"
                                id="username"
                                name="username"
                                placeholder="Vd: Nguyen Van A"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="email"
                                class="form__auth-label"
                            >Email</label
                            >
                            <input
                                type="email"
                                class="form__auth-input"
                                rules="required|email"
                                id="email"
                                name="email"
                                placeholder="Vd: abc@gmail.com"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="address"
                                class="form__auth-label"
                            >Địa chỉ</label>
                            <input
                                type="text"
                                class="form__auth-input"
                                rules="required"
                                id="address"
                                name="address"
                                placeholder="Vd: 123 Đường ABC"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="phone"
                                class="form__auth-label"
                            >Phone</label
                            >
                            <input
                                type="number"
                                class="form__auth-input"
                                rules="required|phone"
                                id="phone"
                                name="phone"
                                placeholder="Vd: 0123456789"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="cccd"
                                class="form__auth-label"
                            >Số CCCD</label
                            >
                            <input
                                type="number"
                                class="form__auth-input"
                                rules="required|min:9"
                                id="cccd"
                                name="cccd"
                                placeholder="Vd: 217627049"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="password"
                                class="form__auth-label"
                            >Mật khẩu</label
                            >
                            <input
                                type="password"
                                class="form__auth-input"
                                rules="required|min:6"
                                id="password"
                                name="password"
                                placeholder="Nhập mật khẩu"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <label
                                for="password_confirmation"
                                class="form__auth-label"
                            >Nhập lại mật khẩu</label
                            >
                            <input
                                type="password"
                                class="form__auth-input"
                                rules="required|confirmation"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Nhập lại mật khẩu"
                            />
                            <span class="form__auth-message"></span>
                        </div>
                        <div class="form__auth-group">
                            <button type="submit" class="btn">
                                Đăng ký
                            </button>
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


<script src="<?= WEB_ROOT . '/public/plugins/validator.js' ?>"></script>
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
    let modalView = document.getElementById("modalLoading");

    new Validator("#form__auth").onSubmit = async (data) => {
        jQuery("#modalLoading").modal("show");

        const formData = new FormData();
        for (const key in data) {
            if (key === "avatar") {
                formData.append(key, data[key][0]);
                continue;
            }
            formData.append(key, data[key]);
        }

        const response = await fetch("<?= WEB_ROOT . '/dang-ky' ?>", {
            method: "POST",
            body: formData,
        });

        const { status, message } = await response.json();

        console.log(status, message);

        if (status === 'error') {
            modalView.querySelector('.modal-title').innerText = 'Error';
            modalView.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    ${message}
                </div>
            `;
        } else {
            modalView.querySelector('.modal-title').innerText = 'Success';
            modalView.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-success fs-2" role="alert">
                    ${message}
                </div>
            `;
        }
    };
</script>