<div class="content__header">
    <h1 class="content__header-title">Books manager</h1>
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
                                    src="<?= !isset($book['img']) ? WEB_ROOT . '/uploads/no-image.png' :
                                        WEB_ROOT . '/' . $book['img'] ?>"
                                    alt="Ảnh sách"
                                    class="img__book"
                                    title="Image book"
                                />
                                <input
                                    type="file"
                                    class="form__input"
                                    id="img_book"
                                    name="img"
                                />
                            </div>
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="code_isbn"
                                class="form__label"
                            >Mã ISBN</label
                            >
                            <input
                                value="<?= $book['isbn_code'] ?? '' ?>"
                                rules="required"
                                type="text"
                                name="code_isbn"
                                id="code_isbn"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="book_name"
                                class="form__label"
                            >Tên sách</label
                            >
                            <input
                                value="<?= $book['book_name'] ?? '' ?>"
                                rules="required"
                                type="text"
                                name="book_name"
                                id="book_name"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="book_title"
                                class="form__label"
                            >Title sách</label
                            >
                            <input
                                value="<?= $book['book_title'] ?? '' ?>"
                                rules="required"
                                type="text"
                                name="book_title"
                                id="book_title"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="book_description"
                                class="form__label"
                            >Mô tả sách</label
                            >
                            <textarea
                                name="book_description"
                                id="book_description"
                                class="form__input"
                            ><?= $book['book_description'] ?? '' ?></textarea>
                        </div>
                        <div class="form__group">
                            <label
                                for="location"
                                class="form__label"
                            >Location</label
                            >
                            <input
                                value="<?= $book['location'] ?? '' ?>"
                                rules="required"
                                type="text"
                                name="location"
                                id="location"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="author_name"
                                class="form__label"
                            >Tên tác giả</label
                            >
                            <select
                                name="author"
                                id="author"
                                class="form__input"
                            >
                                <?php foreach ($author as $item) : ?>
                                    <option
                                        value="<?= $item['id'] ?>"
                                        <?= isset($book['author_id']) && $book['author_id'] == $item['id'] ? 'selected' : '' ?>
                                    >
                                        <?= $item['author_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="category"
                                class="form__label"
                            >Thể loại</label
                            >
                            <select
                                name="category[]"
                                id="category"
                                class="form__input"
                                multiple
                            >
                                <?php foreach ($category as $item) : ?>
                                    <option
                                        value="<?= $item['id'] ?>"
                                        <?= isset($book['categories']) && in_array($item['id'], $book['categories']) ? 'selected' : '' ?>
                                    >
                                        <?= $item['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="publisher_name"
                                class="form__label"
                            >Nhà xuất bản</label
                            >
                            <select
                                name="publisher"
                                id="publisher"
                                class="form__input"
                            >
                                <?php foreach ($publisher as $item) : ?>
                                    <option
                                        value="<?= $item['id'] ?>"
                                        <?= isset($book['publisher_id']) && $book['publisher_id'] == $item['id'] ? 'selected' : '' ?>
                                    >
                                        <?= $item['publisher_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="year_published"
                                class="form__label"
                            >Năm xuất bản</label
                            >
                            <input
                                value="<?= $book['year_published'] ?? '' ?>"
                                rules="required"
                                type="number"
                                name="year_published"
                                id="year_published"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="quantity"
                                class="form__label"
                            >Số lượng</label
                            >
                            <input
                                value="<?= $book['quantity'] ?? '' ?>"
                                rules="required"
                                type="number"
                                name="quantity"
                                id="quantity"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <label
                                for="price"
                                class="form__label"
                            >Giá</label
                            >
                            <input
                                value="<?= $book['price'] ?? '' ?>"
                                rules="required"
                                type="number"
                                name="price"
                                id="price"
                                class="form__input"
                            />
                            <span
                                class="form__message"
                            ></span>
                        </div>
                        <div class="form__group">
                            <button
                                type="submit"
                                class="btn"
                            >
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

<!-- Thêm Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="<?= WEB_ROOT . '/public/assets/admin/js/validator.js' ?>"></script>

<script>
    const toastElement = document.getElementById('myToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });

    new Validator("#form").onSubmit = async (data) => {
        const formData = new FormData();

        for (const key in data) {
            if (key === "img") {
                formData.append(key, data[key][0]);
                continue;
            }
            formData.append(key, data[key]);
        }

        let selectValues = jQuery('#category').val();

        formData.delete("category[]");

        selectValues.forEach((item) => {
            formData.append("category[]", item);
        });

        const req = await fetch("<?= WEB_ROOT . $action ?>", {
            method: "POST",
            body: formData,
        });

        const { status, message } = await req.json();

        if (status === "success") {
            toastElement.querySelector('.toast-body').textContent = message;
            toastElement.querySelector('.toast-header').classList.add('bg-success', 'text-white');
            toast.show();
        } else {
            toastElement.querySelector('.toast-body').textContent = message;
            toastElement.querySelector('.toast-header').classList.add('bg-danger', 'text-white');
            toast.show();
        }
    };
</script>
<script>
    let bookImg = $(".img__book");
    let inputImg = $("#img_book");

    inputImg.onchange = () => {
        const [file] = inputImg.files;
        if (file) {
            bookImg.src = URL.createObjectURL(file);
        }
    };
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