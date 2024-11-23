<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
        integrity="sha512-NmLkDIU1C/C88wi324HBc+S2kLhi08PN5GDeUVVVC/BVt/9Izdsc9SVeVfA1UZbY3sHUlDSyRXhCzHfr6hmPPw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous" />
    <!-- Thêm Select2 CSS -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="<?= WEB_ROOT . '/public/assets/client/css/grid.css' ?>" />
    <link rel="stylesheet" href="<?= WEB_ROOT . '/public/assets/client/css/style.css' ?>" />

    <style>
        .text-truncate {
            max-width: 150px;
            /* Adjust the width as needed */
        }

        .tooltip-inner {
            font-size: 1.6rem;
            /* Tăng kích thước font chữ */
            padding: 0.8rem;
            /* Điều chỉnh khoảng cách */
        }

        @media (max-width: 63.9375em) {
            .text-truncate {
                max-width: 78px;
                /* Adjust the width as needed */
            }
        }
    </style>

    <style>
        .select2-container .select2-selection--single {
            font-size: 1.6rem;
            /* Kích thước font của hộp lựa chọn */
        }

        .select2-dropdown .select2-results__option {
            font-size: 1.6rem;
            /* Kích thước font của các mục trong dropdown */
        }
    </style>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</head>

<body>
    <div class="app">
        <?php
        $this->view('blocks/client/header', $headercontent ?? []);
        $this->view($content, $subcontent ?? []);
        $this->view('blocks/client/footer');
        ?>
    </div>
</body>

</html>

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
    var user = <?= $_SESSION['user']['user_id'] ?? 'undefined' ?>;
    if (user !== undefined) {
        console.log('Đã đăng nhập');
        var con = new WebSocket('ws://localhost:8081');

        const toastContainerNotify = document.querySelector('.toast-container');
        const toastElementNotify = document.querySelector('.toast');
        const toastNotify = new bootstrap.Toast(toastElementNotify, {
            autohide: true,
            delay: 3000
        });

        con.onopen = function(e) {
            console.log('Kết nối thành công');
            con.send(JSON.stringify({
                action: 'join',
                user_id: <?= $_SESSION['user']['user_id'] ?? 'undefined' ?>
            }));
        };

        con.onmessage = function(e) {
            const data = JSON.parse(e.data);
            if (data.action === 'notify') {
                if (data.type === 'accepted') {
                    toastContainerNotify.classList.remove('top-100');
                    toastContainerNotify.classList.add('top-0');
                    toastElementNotify.querySelector('.toast-body').textContent = data.message;
                    toastElementNotify.querySelector('.toast-header').classList.add('bg-success', 'text-white');
                    toastNotify.show();
                } else {
                    toastContainerNotify.classList.remove('top-100');
                    toastContainerNotify.classList.add('top-0');
                    toastElementNotify.querySelector('.toast-body').textContent = data.message;
                    toastElementNotify.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
                    toastNotify.show();
                }
            }
        };

        const delay = ms => new Promise(res => setTimeout(res, ms));

        const sendNotify = book => {
            toastContainerNotify.classList.remove('top-100');
            toastContainerNotify.classList.add('top-0');
            toastElementNotify.querySelector('.toast-body').textContent = `📢 Thông báo: Sách "${book.book_name}" đã quá hạn!`;
            toastElementNotify.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
            toastNotify.show();
        }

        const notifyOverdueBooks = async () => {
            const req = await fetch(`<?= WEB_ROOT ?>/client/borrowbook/getOverdueBooks/${user}`);
            const res = await req.json();

            if (res && res.length > 0) {
                for (const book of res) {
                    sendNotify(book);
                    await delay(10 * 60 * 1000);
                }
            }
        }

        notifyOverdueBooks();
    }
</script>