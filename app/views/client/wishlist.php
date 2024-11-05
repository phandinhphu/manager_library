<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="col l-12 component">
                <div class="title">
                    <h2 class="title__text">Wish List</h2>
                </div>
                <div class="component__content">
                    <div class="component">
                        <form class="form__filter" action="<?= WEB_ROOT . '/danh-sach-mong-muon' ?>">
                            <div class="form__group">
                                <label
                                    for="book_name"
                                    class="form__filter-label"
                                >Tên sách</label
                                >
                                <input
                                    type="text"
                                    name="book_name"
                                    id="book_name"
                                    class="form__filter-input"
                                />
                            </div>
                            <div class="form__group">
                                <label
                                    for="author_name"
                                    class="form__filter-label"
                                    style="width: 120px;"
                                >Tên tác giả</label
                                >
                                <select
                                    name="author_id"
                                    id="author"
                                    class="form__filter-input"
                                >
                                    <option value="">Chọn tác giả</option>
                                    <?php foreach ($authors as $author) : ?>
                                        <option value="<?= $author['id'] ?>">
                                            <?= $author['author_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form__group">
                                <label
                                    for="isbn_code"
                                    class="form__filter-label"
                                >Mã ISBN</label
                                >
                                <input
                                    type="text"
                                    name="isbn_code"
                                    id="isbn_code"
                                    class="form__filter-input"
                                />
                            </div>
                            <div class="form__group">
                                <button type="submit" class="btn">
                                    Lọc
                                </button>
                            </div>
                            <div class="form__group">
                                <a href="<?= WEB_ROOT . '/danh-sach-mong-muon' ?>" class="btn">Hủy</a>
                            </div>
                        </form>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Mã ISBN"
                                    >Mã ISBN</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tên sách"
                                    >Tên sách</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tác giả"
                                    >Tác giả</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Ngày trả"
                                    >Ngày trả</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Số lượng"
                                    >Số lượng</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Thao tác"
                                    >Thao tác</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books_wishlist as $key => $book) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <span
                                            class="d-block text-truncate"
                                            data-bs-toggle="tooltip"
                                            title="<?= $book['isbn_code'] ?>"
                                        ><?= $book['isbn_code'] ?></span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block text-truncate"
                                            data-bs-toggle="tooltip"
                                            title="<?= $book['book_name'] ?>"
                                        ><?= $book['book_name'] ?></span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block text-truncate"
                                            data-bs-toggle="tooltip"
                                            title="<?= $book['author_name'] ?>"
                                        ><?= $book['author_name'] ?></span>
                                    </td>
                                    <td>
                                        <input
                                            type="date"
                                            name="return_date"
                                            class="form__filter-input return_date"
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="quantity"
                                            class="form__filter-input quantity"
                                        />
                                    </td>
                                    <td>
                                        <button
                                            data-book-id="<?= $book['id'] ?>"
                                            type="button"
                                            class="btn btn--danger btn-remove"
                                        >Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="6"></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn"
                                        id="btn-borrow"
                                    >
                                        Mượn
                                    </button>
                                </td>
                            </tr>
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

<script type="module">
    import { isBefore, isEqual } from '<?= WEB_ROOT . '/node_modules/date-fns/index.js' ?>';

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

    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.onclick = async () => {
            let bookId = btn.getAttribute('data-book-id');
            const req = await fetch(`<?= WEB_ROOT . '/danh-sach-mong-muon?book_id=' ?> ${bookId}&action=remove`);
            const res = await req.json();

            if (res.status === 'success') {
                customAlert(res.message, 'success');

                window.location.reload();
            } else {
                customAlert(res.message);
            }
        }
    });

    document.getElementById('btn-borrow').onclick = async () => {
        const books = document.querySelectorAll('.table tbody tr');
        let data = [];

        books.forEach(book => {
            if (book.querySelector('.btn-remove') !== null) {
                const bookId = book.querySelector('.btn-remove').getAttribute('data-book-id');
                const returnDate = book.querySelector('.return_date').value;
                const quantity = book.querySelector('.quantity').value;

                const checkReturnDate = new Date(returnDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (isBefore(checkReturnDate, today) || isEqual(checkReturnDate, today)) {
                    customAlert('Ngày trả không hợp lệ. Ngày trả phải lớn hơn ngày hiện tại');
                    data = [];
                    return;
                }

                if (returnDate === '' || quantity === '') {
                    customAlert('Vui lòng nhập đầy đủ thông tin');
                    data = [];
                    return;
                }

                data.push({
                    book_id: bookId,
                    return_date: returnDate,
                    quantity: quantity
                });
            }
        });

        if (data.length !== 0) {
            const req = await fetch(`<?= WEB_ROOT . '/add-request' ?>`, {
                method: 'POST',
                body: JSON.stringify(data),
            });

            const { status, message } = await req.json();

            if (status === 'success') {
                customAlert(message, 'success');

                window.location.reload();
            } else {
                customAlert(message);
            }
        }
    }
</script>


<!-- Thêm Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    function matchCategory(params, data) {
        // Nếu không có giá trị trả về hoặc không có giá trị tìm kiếm thì trả về null
        if (jQuery.trim(params.term) === "") {
            return data;
        }

        // Nếu tìm thấy giá trị trả về
        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            var modifiedData = jQuery.extend({}, data, true);
            modifiedData.text += " (tìm thấy)";
            return modifiedData;
        }

        // Nếu không tìm thấy giá trị trả về
        return null;
    }

    jQuery(document).ready(function () {
        jQuery("#author").select2({
            placeholder: "Chọn tác giả",
            allowClear: true,
            matcher: matchCategory,
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });
</script>