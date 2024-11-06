<div class="content__header">
    <h1 class="content__header-title">Yêu cầu mượn sách</h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        List request borrow book
                    </h3>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/yeu-cau-muon-sach' ?>" method="get">
                        <div class="form__filter-group">
                            <label
                                for="user_name"
                                class="form__filter-label">Tên độc giả</label>
                            <input
                                type="text"
                                name="user_name"
                                id="user_name"
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
                            <a href="<?= WEB_ROOT . '/quan-tri/yeu-cau-muon-sach' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên độc giả">
                                        Tên độc giả
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên sách">
                                        Tên sách
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số lượng">
                                        Số lượng
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày mượn">
                                        Ngày mượn
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Ngày trả">
                                        Ngày trả
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Action">
                                        Action
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests && count($requests) > 0) : 
                                foreach ($requests as $request) : 
                            ?>
                            <tr>
                                <td>1</td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name user">
                                        <?= $request['user_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        <?= $request['book_name'] ?>
                                    </span>
                                </td>
                                <td><?= $request['quantity'] ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        <?= $request['create_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        <?= $request['expire_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn">
                                        <i class="fa-regular fa-circle-check"></i>
                                    </a>
                                    <a href="#" class="btn btn__action--delete">
                                        <i class="fa-solid fa-ban"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; 
                                else :
                            ?>
                            <tr>
                                <td colspan="7">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
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

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>