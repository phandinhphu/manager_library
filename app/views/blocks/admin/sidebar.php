<div class="sidebar">
    <div class="sidebar__content">
        <ul class="sidebar__items">
            <li class="sidebar__item <?= $tab === 'dashboard' ? 'selected' : '' ?>">
                <a
                    class="sidebar__item-link"
                    href="<?= WEB_ROOT . '/quan-tri/dashboard' ?>">Dashboard</a>
            </li>
            <li class="sidebar__item" <?= $tab === 'request' ? 'selected' : '' ?>>
                <a
                    class="sidebar__item-link"
                    href="<?= WEB_ROOT . '/quan-tri/request' ?>">Request</a>
            </li>
            <li class="sidebar__item <?= $tab === 'books' ? 'selected' : '' ?>">
                <div class="sidebar__item-link">
                    Books
                    <i
                        class="fa-solid fa-caret-right icon no-rotate"></i>
                </div>
                <ul class="sidebar__sub-items">
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/quan-ly-the-loai' ?>">Manager category</a>
                    </li>
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/quan-ly-tac-gia' ?>">Manager author</a>
                    </li>
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/quan-ly-nha-xuat-ban' ?>">Manager publisher</a>
                    </li>
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/books' ?>">Manager books</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar__item  <?= $tab === 'reader' ? 'selected' : '' ?>">
                <div class="sidebar__item-link">
                    Reader
                    <i
                        class="fa-solid fa-caret-right icon no-rotate"></i>
                </div>
                <ul class="sidebar__sub-items">
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/quan-ly-tai-khoan' ?>">Manager account</a>
                    </li>
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/list-borrow-books' ?>">List borrow books</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar__item  <?= $tab === 'statistics' ? 'selected' : '' ?>">
                <div
                    class="sidebar__item-link"
                >
                    Thống kê
                    <i
                        class="fa-solid fa-caret-right icon no-rotate"
                    ></i>
                </div>
                <ul class="sidebar__sub-items">
                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/reader-statistics' ?>"
                        >Reader</a
                        >
                    </li>

                    <li class="sidebar__sub-item">
                        <a
                            class="sidebar__sub-item-link"
                            href="<?= WEB_ROOT . '/quan-tri/books-statistics' ?>"
                        >Books</a
                        >
                    </li>
                </ul>
            </li>
            <li class="sidebar__item" <?= $tab === 'logout' ? 'selected' : '' ?>>
                <a
                        class="sidebar__item-link"
                        href="<?= WEB_ROOT . '/quan-tri/dang-xuat' ?>">Logout</a>
            </li>
        </ul>
    </div>
</div>