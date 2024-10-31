<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="col l-12 component">
                <div class="title">
                    <h2 class="title__text">Wish List</h2>
                </div>
                <div class="component__content">
                    <div class="component">
                        <form class="form__filter" action="">
                            <div class="form__group">
                                <label
                                    for="book_name"
                                    class="form__filter-label"
                                >Tên sách</label
                                >
                                <input
                                    type="text"
                                    name="book_name"
                                    id="book_name"
                                    class="form__filter-input"
                                />
                            </div>
                            <div class="form__group">
                                <label
                                    for="author_name"
                                    class="form__filter-label"
                                >Tên tác giả</label
                                >
                                <input
                                    type="text"
                                    name="author_name"
                                    id="author_name"
                                    class="form__filter-input"
                                />
                            </div>
                            <div class="form__group">
                                <label
                                    for="code_isbn"
                                    class="form__filter-label"
                                >Mã ISBN</label
                                >
                                <input
                                    type="text"
                                    name="code_isbn"
                                    id="code_isbn"
                                    class="form__filter-input"
                                />
                            </div>
                            <div class="form__group">
                                <button type="submit" class="btn">
                                    Lọc
                                </button>
                            </div>
                            <div class="form__group">
                                <a href="#" class="btn">Hủy</a>
                            </div>
                        </form>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Mã ISBN"
                                    >Mã ISBN</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tên sách"
                                    >Tên sách</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Tác giả"
                                    >Tác giả</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Ngày trả"
                                    >Ngày trả</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Số lượng"
                                    >Số lượng</span>
                                </th>
                                <th>
                                    <span
                                        class="d-block text-truncate"
                                        data-bs-toggle="tooltip"
                                        title="Thao tác"
                                    >Thao tác</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($_SESSION['books']['wishlist'])) :
                                foreach ($_SESSION['books']['wishlist'] as $key => $book) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $book['isbn_code'] ?>"
                                            ><?= $book['isbn_code'] ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $book['book_name'] ?>"
                                            ><?= $book['book_name'] ?></span>
                                        </td>
                                        <td>
                                            <span
                                                class="d-block text-truncate"
                                                data-bs-toggle="tooltip"
                                                title="<?= $book['author_name'] ?>"
                                            ><?= $book['author_name'] ?></span>
                                        </td>
                                        <td>
                                            <input
                                                type="date"
                                                name="return_date"
                                                id="return_date"
                                                class="form__filter-input"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="quantity"
                                                id="quantity"
                                                class="form__filter-input"
                                            />
                                        </td>
                                        <td>
                                            <a
                                                href="#"
                                                class="btn btn--danger"
                                            >Xóa</a>
                                        </td>
                                    </tr>
                            <?php endforeach;
                                endif;
                            ?>
                            <tr>
                                <td colspan="6"></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn"
                                    >
                                        Mượn
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <a href="#" class="pagination__prev">
                            <i class="fa fa-arrow-left"></i>
                        </a>

                        <a
                            href="#"
                            class="pagination__number pagination__number--active"
                        >1</a
                        >
                        <a href="#" class="pagination__number">2</a>
                        <a href="#" class="pagination__number">3</a>
                        <a href="#" class="pagination__number">4</a>

                        <a href="#" class="pagination__next">
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
