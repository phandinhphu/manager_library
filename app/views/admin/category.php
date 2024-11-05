<div class="content__header">
    <h1 class="content__header-title">Quản Lý Thể Loại</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Danh sách thể loại
                    </h3>

                    <div class="card__header-action">
                        <button
                            class="btn"
                            id="js-add"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#add-modal">
                            <i class="fa-solid fa-plus"></i>
                            Thêm thể loại
                        </button>
                    </div>
                </div>
                <div class="card__body">

                    <!-- Thanh tìm kiếm -->
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/quan-ly-the-loai/tim-kiem' ?>" method="get">
                        <div class="form__filter-group">
                            <label
                                for="search-keyword"
                                class="form__filter-label">Tìm kiếm</label>
                            <input
                                type="text"
                                name="search-keyword"
                                id="search-keyword"
                                class="form__filter-input"
                                placeholder="Nhập từ khóa"
                                required
                            />
                        </div>
                        
                        <div class="form__filter-group">
                            <button
                                type="submit"
                                class="btn">
                                Tra cứu
                            </button>
                        </div>
                        <div class="form__filter-group">
                            <a href="<?= WEB_ROOT . '/quan-tri/quan-ly-the-loai' ?>" class="btn">Hủy</a>
                        </div>
                    </form>

                    <!-- Bảng dữ liệu -->
                    <table class="table" id="category-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên thể loại</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($categories)) {
                                echo '<tr><td colspan="3">Không có dữ liệu</td></tr>';
                            } else foreach ($categories as $key => $row): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $row['category_name'] ? $row['category_name'] : "Không có dữ liệu" ?></td>
                                    <td>
                                        <button
                                            class="js-edit btn__action"
                                            data-key="<?= $row['id'] ?>"
                                            type="button">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn__action js-delete"
                                            data-key="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
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

<!-- MODAL ADD -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Thêm thể loại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="add-form">
                    <!-- Category name -->
                    <div class="mb-3">
                        <label for="category-name" class="col-form-label">Tên thể loại:</label>
                        <textarea class="form-control form-control-lg" id="category-name" name="category-name" row="2"></textarea>
                        <div class="invalid-feedback">
                            Vui lòng nhập tên thể loại.
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Modal body -->

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn" id="js-add-save">Xác nhận</button>
            </div>
            <!-- End Model footer -->
        </div>
    </div>
</div>
<!-- END MODAL ADD -->

<!-- MODAL EDIT -->
<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Sửa thể loại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="edit-form">
                    <!-- Category name -->
                    <div class="mb-3">
                        <label for="edit-category-name" class="col-form-label">Tên thể loại:</label>
                        <textarea class="form-control form-control-lg" id="edit-category-name" name="edit-category-name" row="2"></textarea>
                    </div>
                </form>
            </div>
            <!-- End Modal body -->

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn" id="js-edit-save">Xác nhận</button>
            </div>
            <!-- End Model footer -->
        </div>
    </div>
</div>