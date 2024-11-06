<div class="content__header">
    <h1 class="content__header-title">Yêu cầu mượn sách</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        List request borrow book
                    </h3>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/yeu-cau-muon-sach' ?>" method="get">
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
                            <a href="<?= WEB_ROOT . '/quan-tri/yeu-cau-muon-sach' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên độc giả">
                                        Tên độc giả
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên sách">
                                        Tên sách
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số lượng">
                                        Số lượng
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày mượn">
                                        Ngày mượn
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày trả">
                                        Ngày trả
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Action">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests && count($requests) > 0) : 
                                foreach ($requests as $key => $request) : 
                            ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $request['user_name'] ?>">
                                        <?= $request['user_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $request['book_name'] ?>">
                                        <?= $request['book_name'] ?>
                                    </span>
                                </td>
                                <td><?= $request['quantity'] ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $request['create_date'] ?>">
                                        <?= $request['create_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $request['expire_date'] ?>">
                                        <?= $request['expire_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-accepted"
                                        data-id="<?= $request['id'] ?>"
                                    >
                                        <i class="fa-regular fa-circle-check"></i>
                                    </button>
                                    <button
                                        class="btn btn__action--delete btn-denied"
                                        data-id="<?= $request['id'] ?>"
                                    >
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; 
                                else :
                            ?>
                            <tr>
                                <td colspan="7">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <?php if ($page > 1) : ?>
                            <a
                                href="<?= $url_page . ($page - 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__prev"
                            >
                                <i
                                    class="fa fa-arrow-left"
                                ></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <a href="<?= $url_page . $i . '.html?' . ($param_string ?? '') ?>" class="pagination__number <?= $i == $page ? 'pagination__number--active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages) : ?>
                            <a
                                href="<?= $url_page . ($page + 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__next"
                            >
                                <i
                                    class="fa fa-arrow-right"
                                ></i>
                            </a>
                        <?php endif; ?>
                    </div>
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

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<script>
    const toastElement = document.getElementById('myToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });

    document.querySelector('tbody').addEventListener('click', async function(e) {
        if (event.target.classList.contains('btn-accepted')) {
            // Lấy ID hoặc thông tin cần thiết từ nút xóa
            const id = event.target.dataset.id;
            // Thực hiện thao tác xóa với fetch
            const page = <?= $page ?>;
            
            const req = await fetch(`<?= WEB_ROOT . '/request/accepted/' ?>${id}/${page}`);
            const res = await req.json();

            if (res.status === "success") {
                toastElement.querySelector('.toast-body').textContent = res.message;
                toastElement.querySelector('.toast-header').classList.add('bg-success', 'text-white');
                toast.show();

                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';

                if (res.data.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="7">Không có dữ liệu</td>
                    `;
                    tbody.appendChild(tr);
                } else {
                    let html = '';
                    res.data.forEach(function (item, index) {
                        html += `
                            <td>${index + 1}</td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name user">
                                    ${item.user_name}
                                </span>
                            </td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.book_name}
                                </span>
                            </td>
                            <td>${item.quantity}</td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.create_date}
                                </span>
                            </td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.expire_date}
                                </span>
                            </td>
                            <td>
                                <button
                                    class="btn btn-accepted"
                                    data-id="${item.id}"
                                >
                                    <i class="fa-regular fa-circle-check"></i>
                                </button>
                                <button
                                    class="btn btn__action--delete btn-denied"
                                    data-id="${item.id}"
                                >
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </td>
                        `;
                    });
                    tbody.innerHTML = html;
                }
            } else {
                toastElement.querySelector('.toast-body').textContent = res.message;
                toastElement.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
                toast.show();
            }
        }
    });

    document.querySelector('tbody').addEventListener('click', async function(e) {
        if (event.target.classList.contains('btn-denied')) {
            if (!confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?')) {
                return;
            }

            // Lấy ID hoặc thông tin cần thiết từ nút xóa
            const id = event.target.dataset.id;
            const page = <?= $page ?>;

            // Thực hiện thao tác xóa với fetch
            const req = await fetch(`<?= WEB_ROOT . '/request/denied/' ?>${id}/${page}`);
            const res = await req.json();
    
            if (res.status === "success") {
                toastElement.querySelector('.toast-body').textContent = res.message;
                toastElement.querySelector('.toast-header').classList.add('bg-success', 'text-white');
                toast.show();
    
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';
    
                if (res.data.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="7">Không có dữ liệu</td>
                    `;
                    tbody.appendChild(tr);
                } else {
                    res.data.forEach(function (item, index) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${index + 1}</td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name user">
                                    ${item.user_name}
                                </span>
                            </td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.book_name}
                                </span>
                            </td>
                            <td>${item.quantity}</td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.create_date}
                                </span>
                            </td>
                            <td>
                                <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                    ${item.expire_date}
                                </span>
                            </td>
                            <td>
                                <button
                                    class="btn btn-accepted"
                                    data-id="${item.id}"
                                >
                                    <i class="fa-regular fa-circle-check"></i>
                                </button>
                                <button
                                    class="btn btn__action--delete btn-denied"
                                    data-id="${item.id}"
                                >
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            } else {
                toastElement.querySelector('.toast-body').textContent = res.message;
                toastElement.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
                toast.show();
            }
        }
    });
</script>