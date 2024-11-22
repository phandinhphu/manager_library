<div class="content__header">
    <h1 class="content__header-title">Quản Lý Nhà Xuất Bản</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Danh sách nhà xuất bản
                    </h3>

                    <div class="card__header-action">
                        <button
                            class="btn"
                            id="js-add"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#add-modal">
                            <i class="fa-solid fa-plus"></i>
                            Thêm nhà xuất bản
                        </button>
                    </div>
                </div>
                <div class="card__body">
                    <!-- Bảng dữ liệu -->
                    <table class="table" id="publisher-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nhà xuất bản</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($publisher)) {
                                echo '<tr><td colspan="3">Không có dữ liệu</td></tr>';
                            } else foreach ($publisher as $key => $row): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $row['publisher_name'] ? $row['publisher_name'] : "Không có dữ liệu" ?></td>
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
                <h5 class="modal-title">Thêm nhà xuất bản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="add-form">
                    <!-- Publisher name -->
                    <div class="mb-3">
                        <label for="publisher-name" class="col-form-label">Tên nhà xuất bản:</label>
                        <textarea class="form-control form-control-lg" id="publisher-name" name="publisher-name" row="2"></textarea>
                        <div class="invalid-feedback">
                            Vui lòng nhập tên nhà xuất bản.
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
                <h5 class="modal-title">Sửa nhà xuất bản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="edit-form">
                    <!-- publisher name -->
                    <div class="mb-3">
                        <label for="edit-publisher-name" class="col-form-label">Tên nhà xuất bản:</label>
                        <textarea class="form-control form-control-lg" id="edit-publisher-name" name="edit-publisher-name" row="2"></textarea>
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

<?php 
    if (file_exists(_DIR_ROOT . '/public/assets/admin/js/pages/' . $script . '.js')) {
        echo '<script src="' . WEB_ROOT . '/public/assets/admin/js/pages/' . $script . '.js"></script>';
    } 
?>