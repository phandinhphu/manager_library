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
                                        data-user="<?= $request['user_id'] ?>"
                                    >
                                        <i class="fa-regular fa-circle-check btn-accepted"></i>
                                    </button>
                                    <button
                                        class="btn btn__action--delete btn-denied"
                                        data-id="<?= $request['id'] ?>"
                                        data-user="<?= $request['user_id'] ?>"
                                    >
                                        <i class="fa-solid fa-ban btn-denied"></i>
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

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
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

        if (type === 'success') {
            toastElement.querySelector('.toast-header').classList.remove('bg-danger', 'text-white');
        } else {
            toastElement.querySelector('.toast-header').classList.remove('bg-success', 'text-white');
        }

        toastElement.querySelector('.toast-header').classList.add(`bg-${type}`, 'text-white');
        toast.show();
    }

    document.querySelector('tbody').addEventListener('click', async function(event) {
        if (event.target.classList.contains('btn-accepted')) {
            var con = new WebSocket('ws://<?= $_ENV['WEBSOCKET_SERVER'] ?>:<?= $_ENV['WEBSOCKET_PORT'] ?>');
            con.onopen = function() {
                console.log('Kết nối thành công', user);
                con.send(JSON.stringify({
                    action: 'join',
                    user_id: user
                }));
            };

            // Lấy ID hoặc thông tin cần thiết từ nút xóa
            const id = event.target.closest('button').dataset.id;
            const user = event.target.closest('button').dataset.user;
            // Thực hiện thao tác xóa với fetch
            const page = <?= $page ?>;
            
            const req = await fetch(`<?= WEB_ROOT . '/request/accepted/' ?>${id}/${page}`);
            const res = await req.json();


            if (res.status === "success") {
                customAlert(res.message, 'success');

                const row = event.target.closest('tr');
                const bookName = row.querySelector('td:nth-child(3)').textContent;
                row.remove();

                setTimeout(() => {
                    con.send(JSON.stringify({
                        action: 'notify',
                        type: 'accepted',
                        message: `Yêu cầu mượn sách ${bookName} cuả bạn đã được chấp nhận.`,
                        user_id: user
                    }));
                }, 1000);
            } else {
                customAlert(res.message);
            }

            con.onclose = function() {
                console.log('Connection closed');
                clearTimeout();
            };
        }
    });

    document.querySelector('tbody').addEventListener('click', async function(event) {
        if (event.target.classList.contains('btn-denied')) {
            var con = new WebSocket('ws://<?= $_ENV['WEBSOCKET_SERVER'] ?>:<?= $_ENV['WEBSOCKET_PORT'] ?>');
            if (!confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?')) {
                return;
            }

            con.onopen = function() {
                console.log('Kết nối thành công', user);
                con.send(JSON.stringify({
                    action: 'join',
                    user_id: user
                }));
            };

            // Lấy ID hoặc thông tin cần thiết từ nút xóa
            const id = event.target.closest('button').dataset.id;
            const user = event.target.closest('button').dataset.user;
            const page = <?= $page ?>;

            // Thực hiện thao tác xóa với fetch
            const req = await fetch(`<?= WEB_ROOT . '/request/denied/' ?>${id}/${page}`);
            const res = await req.json();

    
            if (res.status === "success") {
                customAlert(res.message, 'success');
    
                const row = event.target.closest('tr');
                const bookName = row.querySelector('td:nth-child(3)').textContent;
                row.remove();

                con.send(JSON.stringify({
                    action: 'notify',
                    type: 'denied',
                    message: `Yêu cầu mượn sách ${bookName} cuả bạn đã bị từ chối.`,
                    user_id: user
                }));
            } else {
                customAlert(res.message);
            }

            con.onclose = function() {
                console.log('Connection closed');
            };
        }
    });
</script>