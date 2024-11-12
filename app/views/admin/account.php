<div class="content__header">
    <h1 class="content__header-title">Accounts manager</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        List accounts
                    </h3>

                    <div class="card__header-action">
                        <a
                            class="btn"
                            href="<?= WEB_ROOT . '/quan-tri/quan-ly-tai-khoan/them-tai-khoan'?>"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Add accounts
                        </a>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/quan-ly-tai-khoan' ?>" method="get">
                        <div class="form__filter-group">
                            <label
                                for="user_name"
                                class="form__filter-label">Tên độc giả</label>
                            <input
                                type="text"
                                name="user_name"
                                id="user_name"
                                class="form__filter-input" />
                        </div>
                        <div class="form__filter-group">
                            <button
                                type="submit"
                                class="btn">
                                Tra cứu
                            </button>
                        </div>
                        <div class="form__filter-group">
                            <a href="<?= WEB_ROOT . '/quan-tri/quan-ly-tai-khoan' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên độc giả">Tên độc giả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Email">Email</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số điện thoại">Số điện thoại</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số CCCD">Số CCCD</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Hành động">Thao tác</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($accounts) && $accounts != []) :
                                foreach ($accounts as $key => $account) :
                            ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $account['user_name'] ?>">
                                                <?= $account['user_name'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $account['email'] ?>">
                                                <?= $account['email'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $account['phone_number'] ?>">
                                                <?= $account['phone_number'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $account['cccd'] ?>">
                                                <?= $account['cccd'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button
                                                class="btn__action btn-view-account"
                                                type="button"
                                                data-id="<?= $account['id'] ?>"
                                            >
                                                <i
                                                    class="fa-solid fa-eye"></i>
                                            </button>
                                            <a
                                                href="<?= WEB_ROOT . '/quan-tri/quan-ly-tai-khoan/sua-tai-khoan/' . $account['id'] ?>"
                                                class="btn__action"
                                                type="button"

                                            >
                                                <i
                                                    class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button
                                                class="btn__action btn-delete-account"
                                                type="button"
                                                data-id="<?= $account['id'] ?>"
                                            >
                                                <i
                                                    class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="6">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <?php if ($page > 1) : ?>
                            <a
                                href="<?= $url_page . ($page - 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__prev">
                                <i
                                    class="fa fa-arrow-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <a href="<?= $url_page . $i . '.html?' . ($param_string ?? '') ?>" class="pagination__number <?= $i == $page ? 'pagination__number--active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages) : ?>
                            <a
                                href="<?= $url_page . ($page + 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__next">
                                <i
                                    class="fa fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modalViewAccount"
    tabindex="-1"
    aria-labelledby="modalViewAccountLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account info</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form__group">
                    <img
                        src="./assets/imgs/no-image.png"
                        alt="avatar"
                        class="avatar" />
                </div>
                <div class="form__group" style="display: block;">
                    <label for="name" class="form__label">Tên độc giả: </label>
                    <span class="fw-bold text-primary user-name">Nguyễn Văn A</span>
                </div>
                <div class="form__group" style="display: block;">
                    <label for="email" class="form__label">Email: </label>
                    <span class="fw-bold text-primary email">a@gmail.com</span>
                </div>
                <div class="form__group" style="display: block;">
                    <label for="phone" class="form__label">Phone: </label>
                    <span class="fw-bold text-primary phone">0298573135</span>
                </div>
                <div class="form__group" style="display: block;">
                    <label for="cccd" class="form__label">Số CCCD: </label>
                    <span class="fw-bold text-primary cccd">215628498</span>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<script>
    document.querySelectorAll('.btn-view-account').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            var modal = new bootstrap.Modal(document.getElementById('modalViewAccount'), {
                keyboard: false
            });

            let id = this.getAttribute('data-id');

            // Call API to get account info
            const req = await fetch(`<?= WEB_ROOT ?>/quan-tri/quan-ly-tai-khoan/get-account/${id}`);

            const account = await req.json();

            if (account) {
                console.log(account.email);
                document.querySelector('#modalViewAccount .avatar').src = `<?= WEB_ROOT . '/' ?>${account.avatar}`;
                document.querySelector('#modalViewAccount .user-name').textContent = account.user_name;
                document.querySelector('#modalViewAccount .email').textContent = account.email;
                document.querySelector('#modalViewAccount .phone').textContent = account.phone_number;
                document.querySelector('#modalViewAccount .cccd').textContent = account.cccd;
            }
            
            modal.show();
        });
    });
</script>

<script>
    document.querySelectorAll('.btn-delete-account').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            let id = this.getAttribute('data-id');

            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')) {
                const req = await fetch(`<?= WEB_ROOT ?>/quan-tri/quan-ly-tai-khoan/xoa-tai-khoan/${id}`);

                const { status, message } = await req.json();

                if (status === 'success') {
                    alert(message);
                    location.reload();
                } else {
                    alert(message);
                }
            }
        });
    });
</script>