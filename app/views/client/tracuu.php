<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-12 component">
                <div class="title">
                    <h2 class="title__text">Tra cứu</h2>
                </div>
                <div class="component__content">
                    <div class="component">
                        <form class="form__filter" action="<?= WEB_ROOT . '/tra-cuu' ?>">
                            <div class="form__group">
                                <label for="book_name" class="form__filter-label">Tên sách</label>
                                <input type="text" name="book_name" id="book_name" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="author_name" class="form__filter-label" style="width: 120px;">Tên tác giả</label>
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
                            <div class="form__group">
                                <label for="publisher_name" class="form__filter-label" style="width: 150px;">Nhà xuất bản</label>
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
                            <div class="form__group">
                                <label for="code_isbn" class="form__filter-label">Mã ISBN</label>
                                <input type="text" name="code_isbn" id="code_isbn" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="year_published" class="form__filter-label">Năm xuất bản</label>
                                <input type="number" name="year_published" id="year_published" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="category" class="form__filter-label" style="width: 90px;">Thể loại</label>
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
                            <div class="form__group">
                                <button type="submit" class="btn">Tra cứu</button>
                            </div>
                            <div class="form__group">
                                <a href="<?= WEB_ROOT . '/tra-cuu' ?>" class="btn">Hủy</a>
                            </div>
                        </form>
                    </div>

                    <div class="list__items">
                        <div class="row">
                            <?php foreach ($books as $book) : ?>
                            <a href="<?= WEB_ROOT . '/sach/chi-tiet/' . $book['id'] ?>" class="grCol grL-2-4 grM-4 grC-6">
                                <div class="list__item">
                                    <img class="book__img" src="<?= WEB_ROOT . '/' . $book['img'] ?>" alt="Ảnh sách" />
                                    <div class="book__title">
                                        <h3 class="book__title-text"><?= $book['book_name'] ?></h3>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

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

    jQuery(document).ready(function() {
        jQuery('#category').select2({
            placeholder: "Chọn thể loại",
            allowClear: true,
            matcher: matchCategory,
            minimumResultsForSearch: -1,
            width: "100%"
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