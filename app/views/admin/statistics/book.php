<div class="content__header">
    <div class="card card--success text-center shadow-sm" style="width: 18rem">
        <div class="card-body">
            <h5 class="card-title">Tổng số sách</h5>
            <p class="card-text display-4 fw-bold text-success"><?= $total_books ?></p>
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

                    <button
                        class="btn btn--info">
                        <i class="fa-solid fa-file-export"></i>
                    </button>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/thong-ke-sach' ?>">
                        <div class="form__filter-group">
                            <label
                                for="book_name"
                                class="form__filter-label">Tên sách</label>
                            <input
                                type="text"
                                name="book_name"
                                id="book_name"
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
                            <a href="<?= WEB_ROOT . '/quan-tri/thong-ke-sach' ?>" class="btn">Hủy</a>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tên sách">Tên sách</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Tác giả">Tác giả</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Thể loại">Thể loại</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số lượng mượn">Số lượng mượn</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Số lượng còn">Số lượng còn</span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Xuất file">Xuất file</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $key => $book) : ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['book_name'] ?>"><?= $book['book_name'] ?></span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['author_name'] ?>"><?= $book['author_name'] ?></span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['categories'] ?>"><?= $book['categories'] ?></span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['quantity_borrow'] ?>"><?= $book['quantity_borrow'] ?></span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['quantity'] ?>"><?= $book['quantity'] ?></span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn--info">
                                        <i class="fa-solid fa-file-export"></i>
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

        <div class="grCol grL-12">
            <div class="card card--warning">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Biểu đố tổng số lượng mượn sách
                    </h3>
                </div>
                <div class="card__body">
                    <form class="form__filter" action="">
                        <div class="form__filter-group">
                            <label
                                for="year"
                                class="form__filter-label">Năm</label>
                            <input
                                type="number"
                                name="year"
                                id="year"
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
                            <a href="#" class="btn">Hủy</a>
                        </div>
                    </form>
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Tháng 1',
                'Tháng 2',
                'Tháng 3',
                'Tháng 4',
                'Tháng 5',
                'Tháng 6',
                'Tháng 7',
                'Tháng 8',
                'Tháng 9',
                'Tháng 10',
                'Tháng 11',
                'Tháng 12',
            ],
            datasets: [{
                label: 'Số lượng mượn sách',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Năm 2020',
                        color: '#000',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
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