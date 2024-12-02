<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-12 grM-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Chi tiết sách</h2>
                </div>
                <div class="component__content">
                    <div class="row">
                        <div class="grCol grL-4 grM-12 grC-12">
                            <div class="component__content-img">
                                <img src="<?= WEB_ROOT . '/' . $book['img'] ?>" alt="Ảnh đại diện" />
                            </div>
                        </div>
                        <div class="grCol grL-8 grM-12 grC-12">
                            <div class="component__content-info">
                                <h3 class="component__content-info-title">Tên sách</h3>
                                <p class="component__content-info-text"><?= $book['book_name'] ?></p>
                                <h3 class="component__content-info-title">Tác giả</h3>
                                <p class="component__content-info-text"><?= $book['author_name'] ?></p>
                                <h3 class="component__content-info-title">Nhà xuất bản</h3>
                                <p class="component__content-info-text"><?= $book['publisher_name'] ?></p>
                                <h3 class="component__content-info-title">Thể loại</h3>
                                <p class="component__content-info-text"><?= $book['categories'] ?></p>
                                <h3 class="component__content-info-title">Năm xuất bản</h3>
                                <p class="component__content-info-text"><?= $book['year_published'] ?></p>
                                <h3 class="component__content-info-title">Số lượng</h3>
                                <p class="component__content-info-text"><?= $book['quantity'] ?></p>
                                <h3 class="component__content-info-title">Mô tả</h3>
                                <p class="component__content-info-text">
                                    <?= $book['book_description'] ?>
                                </p>
                                <div class="action">
                                    <div class="action__item">
                                        <button class="btn__action" id="btn-like">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </button>
                                        <span class="action__count likes"><?= $amount_likes ?></span>
                                    </div>
                                    <div class="action__item">
                                        <button class="btn__action" id="btn-dislike">
                                            <i class="fa-solid fa-thumbs-down"></i>
                                        </button>
                                        <span class="action__count dislikes"><?= $amount_dislikes ?></span>
                                    </div>
                                    <div class="action__item">
                                        <button
                                            id="btn-add-wishlist"
                                            class="btn__action"
                                        >Add wishlist</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grCol grL-12 grM-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Comments</h2>
                </div>

                <div class="component__content">
                    <div class="comment__form">
                        <div class="comment__avatar">
                            <img
                                class="comment__user-img"
                                src="<?= WEB_ROOT . '/uploads/no-image.png' ?>"
                                alt="Ảnh đại diện"
                            />
                        </div>
                        <textarea
                            class="comment__input"
                            placeholder="Your Comment"
                            required
                        ></textarea>
                        <div class="comment__submit"><button id="sendComment" type="button" class="btn">Submit</button></div>
                    </div>

                    <ul class="comment__list">
                        <?php foreach ($comments as $comment) : ?>
                        <li class="comment__item">
                            <div class="comment__user">
                                <img
                                    class="comment__user-img"
                                    src="<?= WEB_ROOT . '/' . $comment['avatar'] ?>"
                                    alt="Ảnh đại diện"
                                />
                                <div class="comment__user-info">
                                    <h5 class="comment__user-name">
                                        <?= isset($_SESSION['user']) && ($comment['user_name'] === $_SESSION['user']['name'])
                                        ? 'Bạn' : $comment['user_name'] ?>
                                    </h5>
                                    <p class="comment__user-date"><?= $comment['created_at'] ?></p>
                                </div>
                            </div>
                            <p class="comment__content"><?= $comment['content'] ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="grCol grL-12 grM-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Cùng thể loại</h2>
                </div>
                <div class="component__content">
                    <div class="list__items">
                        <div class="row">
                            <?php foreach ($same_category_books as $same_category_book) : ?>
                            <a href="<?= WEB_ROOT . '/sach/chi-tiet/' . $same_category_book['id'] ?>" class="grCol grL-2-4 grM-4 grC-6">
                                <div class="list__item">
                                    <img class="book__img" src="<?= WEB_ROOT . '/' . $same_category_book['img'] ?>" alt="Ảnh sách" />
                                    <div class="book__title">
                                        <h3 class="book__title-text"><?= $same_category_book['book_name'] ?></h3>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grCol grL-12 grM-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Đồng tác giả</h2>
                </div>
                <div class="component__content">
                    <div class="list__items">
                        <div class="row">
                            <?php foreach ($same_author_books as $same_author_book) : ?>
                                <a href="<?= WEB_ROOT . '/sach/chi-tiet/' . $same_author_book['id'] ?>" class="grCol grL-2-4 grM-4 grC-6">
                                    <div class="list__item">
                                        <img class="book__img" src="<?= WEB_ROOT . '/' . $same_author_book['img'] ?>" alt="Ảnh sách" />
                                        <div class="book__title">
                                            <h3 class="book__title-text"><?= $same_author_book['book_name'] ?></h3>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
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

<script type="module">
    import { format } from '<?= WEB_ROOT . '/node_modules/date-fns/index.js' ?>';

    const toastElement = document.getElementById('myToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });

    var conn = new WebSocket('ws://<?= $_ENV['WEBSOCKET_SERVER'] ?>:<?= $_ENV['WEBSOCKET_PORT'] ?>');
    var bookId = <?= $book['id'] ?>;

    conn.onopen = (e) => {
        console.log("Connection established!");
        conn.send(JSON.stringify({
            action: 'subscribe',
            book_id: bookId
        }))
    }

    conn.onmessage = (e) => {
        // Hiển thị comment nhận được
        var newComment = JSON.parse(e.data);
        var currUser = "<?= $_SESSION['user']['name'] ?? '' ?>";
        console.log(newComment);

        if (newComment.action === 'newcomment' && newComment.book_id === bookId)
        document.querySelector('.comment__list').innerHTML = `
            <li class="comment__item">
                <div class="comment__user">
                    <img
                        class="comment__user-img"
                        src="${newComment.avatar}"
                        alt="Ảnh đại diện"
                    />
                    <div class="comment__user-info">
                        <h5 class="comment__user-name">${currUser != '' && currUser === newComment.username ? 'Bạn' : newComment.username}</h5>
                        <p class="comment__user-date">${newComment.created_at}</p>
                    </div>
                </div>
                <p class="comment__content">${newComment.comment}</p>
            </li>
        ` + document.querySelector('.comment__list').innerHTML;
    }

    document.getElementById('sendComment').onclick = () => {
        let userId = <?= $_SESSION['user']['user_id'] ?? 'undefined' ?>;

        if (userId !== undefined) {
            let comment = document.querySelector('.comment__input').value;
            let avatar = "<?= WEB_ROOT . '/' . ($_SESSION['user']['avatar'] ?? '') ?>";
            let username = "<?= $_SESSION['user']['name'] ?? '' ?>";
            let created_at = format(new Date(), 'yyyy-MM-dd HH:mm:ss');

            conn.send(JSON.stringify({
                action: 'newcomment',
                book_id: bookId,
                username: username,
                user_id: userId,
                avatar: avatar,
                comment: comment,
                created_at: created_at
            }))

            document.querySelector('.comment__input').value = "";
        } else {
            toastContainer.classList.remove('top-100');
            toastContainer.classList.add('top-0');
            toastElement.querySelector('.toast-body').textContent = 'Bạn cần đăng nhập để thực hiện chức năng này';

            toastElement.querySelector('.toast-header').classList.remove('bg-success');
            toastElement.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
            toast.show();
        }
    }
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

        // remove class by type
        if (type === 'danger') {
            toastElement.querySelector('.toast-header').classList.remove('bg-success');
        } else {
            toastElement.querySelector('.toast-header').classList.remove('bg-danger');
        }

        toastElement.querySelector('.toast-header').classList.add(`bg-${type}`, 'text-white');
        toast.show();
    }

    document.getElementById('btn-like').onclick = async () => {
        const req = await fetch('<?= WEB_ROOT . '/sach/yeu-thich/' . $book['id'] ?>');

        const res = await req.json();

        if (res.status === 'success') {
            document.querySelector('.likes').innerHTML = res.amount_likes;
            document.querySelector('.dislikes').innerHTML = res.amount_dislikes;
        } else {
            customAlert(res.message);
        }
    }

    document.getElementById('btn-dislike').onclick = async () => {
        const req = await fetch('<?= WEB_ROOT . '/sach/khong-thich/' . $book['id'] ?>');

        const res = await req.json();

        if (res.status === 'success') {
            document.querySelector('.likes').innerHTML = res.amount_likes;
            document.querySelector('.dislikes').innerHTML = res.amount_dislikes;
        } else {
            customAlert(res.message);
        }
    }

    document.getElementById('btn-add-wishlist').onclick = async () => {
        const req = await fetch('<?= WEB_ROOT . '/danh-sach-mong-muon?book_id=' . $book['id'] . '&action="add"' ?>');
        const res = await req.json();

        if (res.status === 'success') {
            customAlert(res.message, 'success');
        } else {
            customAlert(res.message);
        }
    }
</script>
