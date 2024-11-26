<div class="content__header">
    <div class="card card--success text-center shadow-sm" style="width: 18rem">
        <div class="card-body">
            <h5 class="card-title">Tổng số độc giả</h5>
            <p class="card-text display-4 fw-bold text-success"><?= $total_reader ?></p>
            <i class="bi bi-person-fill text-success" style="font-size: 2rem;"></i>
        </div>
    </div>
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
                    <form class="form__filter" action="">
                        <div class="form__filter-group">
                            <label
                                for="reader_name"
                                class="form__filter-label">Tên độc giả</label>
                            <input
                                type="text"
                                name="reader_name"
                                id="reader_name"
                                class="form__filter-input" />
                        </div>
                        <div class="form__filter-group">
                            <button
                                type="submit"
                                class="btn">
                                Tra cứu
                            </button>
                        </div>
                        <div class="form__filter-group">
                            <a href="<?= WEB_ROOT . '/quan-tri/thong-ke-doc-gia' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table" id="reader-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên độc giả</th>
                                <th>Tổng số sách mượn</th>
                                <th>Tổng số sách đã trả</th>
                                <th>Tổng số tiền phạt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($readers)) {
                                echo '<tr><td colspan="6">Không có dữ liệu</td></tr>';
                            } else foreach ($readers as $key => $row): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $row['user_name'] ? $row['user_name'] : "Không có dữ liệu" ?></td>
                                    <td><?= $row['total_borrowed'] ? $row['total_borrowed'] : 0 ?></td>
                                    <td><?= $row['total_returned'] ? $row['total_returned'] : 0 ?></td>
                                    <td><?= $row['total_fine'] ? $row['total_fine'] : 0 ?></td>
                                    <td>
                                        <button
                                            class="btn btn--info js-view"
                                            data-key="<?= $row['id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#view-modal">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button
                                            class="btn btn--info js-export"
                                            data-key="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-file-export"></i>
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

        <div class="grCol grL-6">
            <div class="card card--warning">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Biểu đồ thống kê độc giả đang mượn sách
                    </h3>
                </div>
                <div class="card__body">
                    <canvas id="reader-chart"></canvas>
                </div>
            </div>
        </div>
        <div class="grCol grL-6">
            <div class="card card--warning">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Biểu đồ thống kê tiền phạt
                    </h3>
                </div>
                <div class="card__body">
                    <canvas id="fine-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VIEW STATISTIC -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="modalViewStatisticalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết sách mượn</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sách</th>
                            <th>Ngày mượn</th>
                            <th>Ngày trả</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                            <th>Tiền phạt</th>
                        </tr>
                    </thead>
                    <tbody id="borrowed-detail">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php 
    if (file_exists(_DIR_ROOT . '/public/assets/admin/js/pages/' . $script . '.js')) {
        echo '<script src="' . WEB_ROOT . '/public/assets/admin/js/pages/' . $script . '.js"></script>';
    } 
?>
