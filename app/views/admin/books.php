<div class="content__header">
    <h1 class="content__header-title">Books manager</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        List books
                    </h3>

                    <div class="card__header-action">
                        <a
                            class="btn"
                            href="<?= WEB_ROOT . '/quan-tri/books/them-sach' ?>"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Add books
                        </a>
                    </div>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/books/search' ?>" method="get">
                        <div class="form__filter-group">
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
                        <div class="form__filter-group">
                            <label
                                for="author_name"
                                class="form__filter-label"
                            >Tên tác giả</label
                            >
                            <select
                                name="author"
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
                        <div class="form__filter-group">
                            <label
                                for="category"
                                class="form__filter-label"
                                style="width: 90px"
                            >Thể loại</label
                            >
                            <select
                                name="category[]"
                                id="category"
                                class="form__filter-input"
                                multiple="multiple"
                            >
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id'] ?>">
                                        <?= $category['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form__filter-group">
                            <label
                                for="publisher_name"
                                class="form__filter-label"
                            >Nhà xuất bản</label
                            >
                            <select
                                name="publisher"
                                id="publisher"
                                class="form__filter-input"
                            >
                                <option value="">Chọn nhà xuất bản</option>
                                <?php foreach ($publishers as $publisher) : ?>
                                    <option value="<?= $publisher['id'] ?>">
                                        <?= $publisher['publisher_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form__filter-group">
                            <label
                                for="code_isbn"
                                class="form__filter-label"
                            >Mã ISBN</label
                            >
                            <input
                                type="text"
                                name="code_isbn"
                                id="code_isbn"
                                class="form__filter-input"
                            />
                        </div>
                        <div class="form__filter-group">
                            <label
                                for="year_published"
                                class="form__filter-label"
                            >Năm xuất bản</label
                            >
                            <input
                                type="number"
                                name="year_published"
                                id="year_published"
                                class="form__filter-input"
                            />
                        </div>
                        <div class="form__filter-group">
                            <button
                                type="submit"
                                class="btn"
                            >
                                Tra cứu
                            </button>
                        </div>
                        <div class="form__filter-group">
                            <a href="<?= WEB_ROOT . '/quan-tri/books' ?>" class="btn">Hủy</a>
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
                                    title="Name book"
                                >
                                    Name book
                                </span>
                            </th>
                            <th>
                                <span
                                    class="d-block text-truncate"
                                    data-bs-toggle="tooltip"
                                    title="Author"
                                >
                                    Author
                                </span>
                            </th>
                            <th>
                                <span
                                    class="d-block text-truncate"
                                    data-bs-toggle="tooltip"
                                    title="Categories"
                                >
                                    Thể loại
                                </span>
                            </th>
                            <th>
                                <span
                                    class="d-block text-truncate"
                                    data-bs-toggle="tooltip"
                                    title="Publisher"
                                >
                                    Publisher
                                </span>
                            </th>
                            <th>
                                <span
                                    class="d-block text-truncate"
                                    data-bs-toggle="tooltip"
                                    title="Quantity"
                                >
                                    Quantity
                                </span>
                            </th>
                            <th>
                                <span
                                    class="d-block text-truncate"
                                    data-bs-toggle="tooltip"
                                    title="Action"
                                >
                                    Action
                                </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($books as $key => $book) : ?>
                            <tr>
                                <td>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="<?= $key + 1 ?>"
                                    >
                                        <?= $key + 1 ?>
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="<?= $book['book_name'] ?>"
                                    >
                                        <?= $book['book_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="<?= $book['author_name'] ?>"
                                    >
                                        <?= $book['author_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="<?= $book['categories'] ?>"
                                    >
                                        <?= $book['categories'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="<?= $book['publisher_name'] ?>"
                                    >
                                        <?= $book['publisher_name'] ?>
                                    </span>
                                </td>
                                <td><?= $book['quantity'] ?></td>
                                <td>
                                    <a
                                        href="<?= WEB_ROOT . '/quan-tri/books/sua-sach/' . $book['id'] ?>"
                                        class="btn btn--primary"
                                    >
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <button
                                        class="btn btn--danger btn-delete-book"
                                        id="<?= $book['id'] ?>"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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

<!-- Thêm Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Xử lý xóa sách
    document.addEventListener('DOMContentLoaded', function () {
        var btnDeleteBook = document.querySelectorAll('.btn-delete-book');
        btnDeleteBook.forEach(function (btn) {
            btn.addEventListener('click', async function () {
                if (confirm('Bạn có chắc chắn muốn xóa sách này không?')) {
                    const res = await fetch('<?= WEB_ROOT . '/quan-tri/books/delete-book/' ?>' + this.getAttribute('id'));
                    const { status, message } = await res.json();

                    if (status === 'success') {
                        alert(message);
                        window.location.reload();
                    } else {
                        alert(message);
                    }
                }
            });
        });
    });
</script>
<script>
    function matchCategory(params, data) {
        // Nếu không có giá trị trả về hoặc không có giá trị tìm kiếm thì trả về null
        if (jQuery.trim(params.term) === "") {
            return data;
        }

        // Nếu tìm thấy giá trị trả về
        if (
            data.text.toLowerCase().indexOf(params.term.toLowerCase()) >
            -1
        ) {
            var modifiedData = jQuery.extend({}, data, true);
            modifiedData.text += " (tìm thấy)";
            return modifiedData;
        }

        // Nếu không tìm thấy giá trị trả về
        return null;
    }

    jQuery(document).ready(function () {
        jQuery("#category").select2({
            placeholder: "Chọn thể loại",
            allowClear: true,
            matcher: matchCategory,
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });

    jQuery(document).ready(function () {
        jQuery("#author").select2({
            placeholder: "Chọn tác giả",
            allowClear: true,
            matcher: matchCategory,
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });

    jQuery(document).ready(function () {
        jQuery("#publisher").select2({
            placeholder: "Chọn nhà xuất bản",
            allowClear: true,
            matcher: matchCategory,
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });
</script>
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (
        tooltipTriggerEl
    ) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>