<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-12 component">
                <div class="title">
                    <h2 class="title__text">Lịch sử trả sách</h2>
                </div>
                <div class="component__content">
                    <div class="component">
                        <form class="form__filter" action="<?= WEB_ROOT . '/lich-su/search' ?>">
                            <div class="form__group">
                                <label for="book_name" class="form__filter-label">Tên sách</label>
                                <input type="text" name="book_name" id="book_name" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="author" class="form__filter-label" style="width: 120px;">Tên tác giả</label>
                                <select
                                    name="author"
                                    id="author"
                                    class="form__filter-input">
                                    <option value="">Chọn tác giả</option>
                                    <?php foreach ($authors as $author) : ?>
                                        <option value="<?= $author['id'] ?>">
                                            <?= $author['author_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form__group">
                                <label for="code_isbn" class="form__filter-label">Mã ISBN</label>
                                <input type="text" name="code_isbn" id="code_isbn" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="sort" class="form__filter-label">Sắp xếp</label>
                                <select name="sort" id="sort" class="form__filter-input">
                                    <option value="">Chọn cách sắp xếp</option>
                                    <option value="book_name_asc">Tên sách(A->Z)</option>
                                    <option value="book_name_desc">Tên sách(Z->A)</option>
                                    <option value="borrow_date_desc">Ngày mượn(giảm dần)</option>
                                    <option value="borrow_date_asc">Ngày mượn(tăng dần)</option>
                                    <option value="return_date_asc">Ngày trả(tăng dần)</option>
                                    <option value="fine_amount_desc">Tiền phạt(giảm dần)</option>
                                    <option value="fine_amount_asc">Tiền phạt(tăng dần)</option>
                                </select>
                            </div>
                            <div class="form__group">
                                <button type="submit" class="btn">Lọc</button>
                            </div>
                            <div class="form__group">
                                <a href="<?= WEB_ROOT . '/lich-su' ?>" class="btn">Hủy</a>
                            </div>
                        </form>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Mã ISBN">Mã ISBN</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên sách">Tên sách</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tác giả">Tác giả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày mượn">Ngày mượn</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày trả">Ngày trả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Trạng thái (trả sách)">Trạng thái trả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Phí phạt">Phí phạt</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($returnBooks)) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                                </tr>
                                <?php else :
                                foreach ($returnBooks as $key => $book) :
                                ?>
                                    <tr>
                                        <td data-label="STT"><?= $key + 1 ?></td>
                                        <td data-label="Mã sách">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['isbn_code'] ?>"><?= $book['isbn_code'] ?></span>
                                        </td>
                                        <td data-label="Tên sách">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['book_name'] ?>"><?= $book['book_name'] ?></span>
                                        </td>
                                        <td data-label="Tên tác giả">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['author_name'] ?>"><?= $book['author_name'] ?></span>
                                        </td>
                                        <td data-label="Ngày mượn">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['borrow_date'] ?>"><?= $book['borrow_date'] ?></span>
                                        </td>
                                        <td data-label="Ngày trả">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['return_date'] ?>"><?= $book['return_date'] ?></span>
                                        </td>
                                        <td data-label="Trạng thái trả">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['book_status'] ?>"><?= $book['book_status'] ?></span>
                                        </td>
                                        <td data-label="Tiền phạt">
                                            <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['fine_amount'] . 'VND' ?>"><?= $book['fine_amount'] . 'VND' ?></span>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
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

            <div class="grCol grL-12 component">
                <div class="title">
                    <h2 class="title__text">Thống kê</h2>
                </div>

                <div class="component__content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng số sách">Tổng số sách</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng số sách đã trả">Tổng số sách đã trả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng số sách đã trả (quá hạn)">Tổng số sách đã trả (quá hạn)</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng số sách chưa trả">Tổng số sách chưa trả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng số sách chưa trả (quá hạn)">Tổng số sách chưa trả (quá hạn)</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tổng phí phạt">Tổng phí phạt</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="width-bf-50" data-label="Tổng số sách">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total'] ?>"><?= $statistic['total'] ?></span>
                                </td>
                                <td class="width-bf-50" data-label="Tổng số sách đã trả">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total_return'] ?>"><?= $statistic['total_return'] ?></span>
                                </td>
                                <td class="width-bf-50" data-label="Tổng số sách đã trả (quá hạn)">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total_return_overdue'] ?>"><?= $statistic['total_return_overdue'] ?></span>
                                </td>
                                <td class="width-bf-50" data-label="Tổng số sách chưa trả">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total_borrow'] ?>"><?= $statistic['total_borrow'] ?></span>
                                </td>
                                <td class="width-bf-50" data-label="Tổng số sách chưa trả (quá hạn)">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total_borrow_overdue'] ?>"><?= $statistic['total_borrow_overdue'] ?></span>
                                </td>
                                <td class="width-bf-35" data-label="Tổng phí phạt">
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $statistic['total_fine'] ?>"><?= $statistic['total_fine'] ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
        jQuery("#author").select2({
            placeholder: "Chọn tác giả",
            allowClear: true,
            matcher: matchCategory,
            width: "100%",
            minimumResultsForSearch: -1,
        });
    });
</script>