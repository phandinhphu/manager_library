<style>
    .text-truncate {
        max-width: 68px; /* Adjust the width as needed */
    }
</style>

<div class="content__header">
    <h1 class="content__header-title"><?= $title ?></h1>
</div>

<div class="content__body">
    <div class="row">
        <div class="grCol grL-6 grM-12 grC-12">
            <div class="card card--info">
                <div class="card__header">
                    <h3 class="card__header-title">
                        New imported books
                    </h3>
                </div>
                <div class="card__body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        Name book
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Author">
                                        Author
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Publisher">
                                        Publisher
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Category">
                                        Category
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Quantity">
                                        Quantity
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($new_imported_books) && $new_imported_books != []) :
                                foreach ($new_imported_books as $key => $book) :
                            ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['book_name'] ?>">
                                        <?= $book['book_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['author_name'] ?>">
                                        <?= $book['author_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['publisher_name'] ?>">
                                        <?= $book['publisher_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['categories'] ?>">
                                        <?= $book['categories'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $book['quantity'] ?>">
                                        <?= $book['quantity'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach;
                                else : 
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="grCol grL-6 grM-12 grC-12">
            <div class="card card--danger">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Readers violated
                    </h3>
                </div>
                <div class="card__body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name reader">
                                        Name reader
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        Name book
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Return date">
                                        Return date
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Status">
                                        Status
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Fine">
                                        Fine
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($readers_violated) && $readers_violated != []) :
                                foreach ($readers_violated as $key => $reader) :
                            ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader['user_name'] ?>">
                                        <?= $reader['user_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader['book_name'] ?>">
                                        <?= $reader['book_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader['return_date'] ?>">
                                        <?= $reader['return_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader['book_status'] ?>">
                                        <?= $reader['book_status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader['fine_amount'] ?>">
                                        <?= $reader['fine_amount'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach;
                                else : 
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="grCol grL-6 grM-12 grC-12">
            <div class="card card--success">
                <div class="card__header">
                    <h3 class="card__header-title">
                        Readers borrowing
                    </h3>
                </div>
                <div class="card__body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name reader">
                                        Name reader
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Name book">
                                        Name book
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Borrow date">
                                        Borrow date
                                    </span>
                                </th>
                                <th>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="Expiry date">
                                        Expiry date
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($readers_borrowing) && $readers_borrowing != []) :
                                foreach ($readers_borrowing as $key => $reader_borrowing) :
                            ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader_borrowing['user_name'] ?>">
                                        <?= $reader_borrowing['user_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader_borrowing['book_name'] ?>">
                                        <?= $reader_borrowing['book_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader_borrowing['borrow_date'] ?>">
                                        <?= $reader_borrowing['borrow_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" data-bs-toggle="tooltip" title="<?= $reader_borrowing['due_date'] ?>">
                                        <?= $reader_borrowing['due_date'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach;
                                else : 
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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