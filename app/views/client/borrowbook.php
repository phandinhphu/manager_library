<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="col l-12 component">
                <div class="title">
                    <h2 class="title__text">Sách mượn</h2>
                </div>
                <div class="component__content">
                    <!-- Thanh tìm kiếm -->
                    <div class="component">
                        <form class="form__filter" action="<?= WEB_ROOT . '/muon-sach/tim-kiem' ?>" method="get">
                            <div class="form__group">
                                <label for="isbn_code" class="form__filter-label">Mã ISBN</label>
                                <input type="text" name="isbn_code" id="isbn_code" class="form__filter-input">
                            </div>    
                            <div class="form__group">
                                <label for="book_name" class="form__filter-label">Tên sách</label>
                                <input type="text" name="book_name" id="book_name" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="author_name" class="form__filter-label">Tên tác giả</label>
                                <input type="text" name="author_name" id="author_name" class="form__filter-input">
                            </div>
                            <div class="form__group">
                                <label for="status" class="form__filter-label">Trạng thái</label>
                                <select name="status" id="status" class="form__filter-input">
                                    <option value="0">--Chọn--</option>    
                                    <option value="1">Đang xử lý</option>    
                                    <option value="2">Đã mượn</option>
                                </select>
                            </div>
                            <div class="form__group">
                                <label for="sort" class="form__filter-label">Sắp xếp</label>
                                <select name="sort" id="sort" class="form__filter-input">
                                    <option value="1">Tên sách(A->Z)</option>
                                    <option value="2">Tên tác giả(A->Z)</option>
                                    <option value="2">Ngày mượn(giảm dần)</option>
                                    <option value="3">Ngày trả(giảm dần)</option>
                                </select>
                            </div>
                            <div class="form__group">
                                <button type="submit" class="btn">Lọc</button>
                            </div>
                            <div class="form__group">
                                <a href="<?= WEB_ROOT . '/muon-sach' ?>" class="btn">Hủy</a>
                            </div>
                        </form>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã ISBN</th>
                                <th>Tên sách</th>
                                <th>Tên tác giả</th>
                                <th>Ngày mượn</th>
                                <th>Ngày trả</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(empty($borrowed_books)) {
                                echo '<tr><td colspan="7">Không có dữ liệu</td></tr>';
                            } else foreach ($borrowed_books as $key => $row): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $row['isbn_code'] ? $row['isbn_code'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['book_name'] ? $row['book_name'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['author_name'] ? $row['author_name'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['borrow_date'] ? $row['borrow_date'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['due_date'] ? $row['due_date'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['status'] ? $row['status'] : "Không có dữ liệu" ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Phân trang -->
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a
                                href="<?= $url_page . ($page - 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__prev">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a
                                href="<?= $url_page . $i . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__number <?= $i == $page ? 'pagination__number--active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a
                                href="<?= $url_page . ($page + 1) . '.html?' . ($param_string ?? '') ?>"
                                class="pagination__next">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>