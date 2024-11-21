<div class="content__header">
    <h1 class="content__header-title"><?= $title ?></h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        List borrow books
                    </h3>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/danh-sach-sach-muon' ?>">
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
                            <a href="<?= WEB_ROOT . '/quan-tri/danh-sach-sach-muon' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tên độc giả">
                                        Tên độc giả
                                    </span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tên sách">
                                        Tên sách
                                    </span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Ngày mượn">
                                        Ngày mượn
                                    </span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Ngày hẹn trả">
                                        Ngày trả
                                    </span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tiền phạt">
                                        Tiền phạt (VNĐ)
                                    </span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Thao tác">
                                        Xác nhận
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($borrowBooks)) : ?>
                                <tr>
                                    <td colspan="7">Không có dữ liệu</td>
                                </tr>
                                <?php else:
                                foreach ($borrowBooks as $key => $borrowBook) :
                                ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $borrowBook['user_name'] ?>">
                                                <?= $borrowBook['user_name'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $borrowBook['book_name'] ?>">
                                                <?= $borrowBook['book_name'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $borrowBook['borrow_date'] ?>">
                                                <?= $borrowBook['borrow_date'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $borrowBook['due_date'] ?>">
                                                <?= $borrowBook['due_date'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                class="form__filter-input"
                                                value="0"
                                                name="staff_fine"
                                                min="0" />
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-view"
                                                data-id="<?= $borrowBook['id'] ?>"
                                            >
                                                <i
                                                    class="fa-solid fa-eye"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-send-mail"
                                                data-user="<?= $borrowBook['user_id'] ?>"
                                            >
                                                <i
                                                    class="fa-solid fa-paper-plane"></i>
                                            </button>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif;
                            ?>
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
    id="modalSendMail"
    tabindex="-1"
    aria-labelledby="modalSendMailLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Mail</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="form__group">
                        <label for="title" class="form__label">Title</label>
                        <textarea
                            name="title"
                            id="title"
                            class="form__input"></textarea>
                    </div>
                    <div class="form__group">
                        <label for="content" class="form__label">Content</label>
                        <textarea
                            name="content"
                            id="content"
                            class="form__input"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary btn-send">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modalView"
    tabindex="-1"
    aria-labelledby="modalViewLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="book_name" class="form__label">Tên sách</label>
                            <p
                                id="book_name"
                                class="form__input">
                                Abc xyz
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="user_name" class="form__label">Tên độc giả</label>
                            <p
                                id="user_name"
                                class="form__input">
                                Abc xyz
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="borrow_date" class="form__label">Ngày mượn</label>
                            <p
                                id="borrow_date"
                                class="form__input">
                                2021-12-12
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="due_date" class="form__label">Ngày hẹn trả</label>
                            <p
                                id="due_date"
                                class="form__input">
                                2021-12-12
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="return_date" class="form__label">Ngày trả thực tế</label>
                            <p
                                id="return_date"
                                class="form__input"
                            >
                                2021-12-12
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="overdue_days" class="form__label">Số ngày quá hạn</label>
                            <p
                                id="overdue_days"
                                class="form__input"
                            >
                                0
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="system_fine" class="form__label">Tiền phạt hệ thống</label>
                            <p
                                id="system_fine"
                                class="form__input"
                            >
                                0
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="staff_fine" class="form__label">Tiền phạt nhân viên tính</label>
                            <p
                                id="staff_fine"
                                class="form__input"
                            >
                                0
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="quantity" class="form__label">Số lượng mượn</label>
                            <p
                                id="quantity"
                                class="form__input"
                            >
                                1
                            </p>
                        </div>
                        <div class="grCol grL-6 grM-6 grC-12 form__group">
                            <label for="note" class="form__label">Ghi chú</label>
                            <textarea
                                name="note"
                                id="note"
                                class="form__input"
                                autofocus
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary btn-confirm">
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLoading" aria-hidden="true" aria-labelledby="modalLoadingLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
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

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function(
        tooltipTriggerEl
    ) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<script>

    const handleConfirm = async (data, modalView) => {
        let book_status = modalView.querySelector('#note').value;
        data = { ...data, book_status };

        const req = await fetch('<?= WEB_ROOT . '/admin/borrowbook/confirmReturnBook' ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const { status, message } = await req.json();

        if (status === 'success') {
            alert(message);
            location.reload();
        } else {
            alert(message);
        }
    }

    document.querySelectorAll('.btn-view').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            var modal = new bootstrap.Modal(
                document.getElementById('modalView'),
                {
                    keyboard: false
                }
            );

            let id = this.getAttribute('data-id');
            let staff_fine = this.parentElement.parentElement.querySelector('input[name="staff_fine"]').value;

            const req = await fetch('<?= WEB_ROOT . '/admin/borrowbook/getById/' ?>' + id);

            const res = await req.json();
            const modalView = document.getElementById('modalView');

            modalView.querySelector('#book_name').textContent = res.book_name;
            modalView.querySelector('#user_name').textContent = res.user_name;
            modalView.querySelector('#borrow_date').textContent = res.borrow_date;
            modalView.querySelector('#due_date').textContent = res.due_date;
            modalView.querySelector('#return_date').textContent = res.return_date;
            modalView.querySelector('#overdue_days').textContent = res.over_dues;
            modalView.querySelector('#system_fine').textContent = res.system_fine;
            modalView.querySelector('#staff_fine').textContent = staff_fine;
            modalView.querySelector('#quantity').textContent = res.quantity;

            modal.show();

            let data = {
                id: id,
                fine_amount: Number(staff_fine) + Number(res.system_fine),
                days_overdue: res.over_dues,
                return_date: res.return_date,
            };

            document.querySelector('.btn-confirm').addEventListener('click', () => {
                handleConfirm(data, modalView);
            });
        });
    });

    document.querySelectorAll('.btn-send-mail').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modal = new bootstrap.Modal(
                document.getElementById('modalSendMail'),
                {
                    keyboard: false
                }
            );

            var modalLoading = new bootstrap.Modal(
                document.getElementById('modalLoading'),
                {
                    keyboard: false
                }
            );

            modal.show();
            
            let user = this.getAttribute('data-user');

            
            document.querySelector('.btn-send').addEventListener('click', async function() {
                const modalSendMail = document.getElementById('modalSendMail');
                let title = modalSendMail.querySelector('#title');
                let content = modalSendMail.querySelector('#content');

                modalLoading.show();

                const req = await fetch(`<?= WEB_ROOT ?>/admin/borrowbook/sendWarningEmail/${user}/${title.value}/${content.value}`);
                const { status, message } = await req.json();

                let modalView = document.getElementById("modalLoading");

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

                title.value = '';
                content.value = '';

                modalView.addEventListener('hidden.bs.modal', function() {
                    this.querySelector('.modal-title').innerText = 'Loading';
                    this.querySelector('.modal-body').innerHTML = `
                        <div id="spinner" class="spinner-border text-primary" role="status" style="display: inline-block;">
                            <span class="visually-hidden">Đang gửi...</span>
                        </div>
                    `;
                });
            });
        });
    });
</script>