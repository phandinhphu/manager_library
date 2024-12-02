<header class="header">
    <div class="header__first">
        <a href="<?= WEB_ROOT . '/trang-chu' ?>">
            <h1>Library</h1>
        </a>
        <div class="header__menu">
            <ul class="first__list">
                <li class="first__items <?= isset($tab) && $tab == 'trang-chu' ? 'active' : '' ?>">
                    <a class="first__items-link" href="<?= WEB_ROOT . '/trang-chu' ?>">Trang chủ</a>
                </li>
                <li class="first__items <?= isset($tab) && $tab === 'tra-cuu' ? 'active' : '' ?>">
                    <a class="first__items-link" href="<?= WEB_ROOT . '/tra-cuu' ?>">Tra cứu</a>
                </li>
                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="first__items <?= isset($tab) && $tab == 'wishlist' ? 'active' : '' ?>">
                        <a
                            class="first__items-link"
                            href="<?= WEB_ROOT . '/danh-sach-mong-muon' ?>">Wish list</a>
                    </li>
                    <li class="first__items <?= isset($tab) && $tab == 'book-borrow' ? 'active' : '' ?>">
                        <a
                            class="first__items-link"
                            href="<?= WEB_ROOT . '/muon-sach' ?>">Sách mượn</a>
                    </li>
                    <li class="first__items <?= isset($tab) && $tab == 'history' ? 'active' : '' ?>">
                        <a class="first__items-link" href="<?= WEB_ROOT . '/lich-su' ?>">Lịch sử</a>
                    </li>
                <?php endif; ?>
                <li class="line"></li>
            </ul>
        </div>
    </div>

    <div class="header__second">
        <div class="header__second-user">
            <div class="header__menu">
                <?php
                if (!isset($_SESSION['user'])) :
                ?>
                    <ul class="first__list">
                        <li class="first__items">
                            <a
                                class="first__items-link"
                                href="<?= WEB_ROOT . '/dang-nhap' ?>">Đăng nhập</a>
                        </li>
                        <li class="first__items">
                            <a
                                class="first__items-link"
                                href="<?= WEB_ROOT . '/dang-ky' ?>">Đăng ký</a>
                        </li>
                    </ul>
                <?php else : ?>
                    <ul class="first__list">
                        <li class="first__items">
                            <a
                                class="first__items-link"
                                href="<?= WEB_ROOT . '/thong-tin-ca-nhan' ?>">Xin chào, <?= $_SESSION['user']['name'] ?></a>
                        </li>
                        <li class="first__items">
                            <a
                                class="first__items-link"
                                href="<?= WEB_ROOT . '/dang-xuat' ?>">Đăng xuất</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mobile-menu-btn js-menu-btn">
        <i class="mobile-menu-icon fa-solid fa-bars"></i>
    </div>
</header>
<script src="<?= WEB_ROOT . '/public/assets/client/js/line.js' ?>"></script>

<script>
    var header = document.querySelector('.header')
    var headerHeight = header.clientHeight
    const mobileMenu = document.querySelector('.js-menu-btn')

    // Đóng mở mobile menu
    mobileMenu.addEventListener('click', function() {
        var isClose = header.clientHeight === headerHeight
        if (isClose) {
            header.style.height = 'auto'
        } else {
            header.style.height = 'var(--height-header)'
        }
    })

    // Tự động đóng khi chọn menu
    var menuItems = document.querySelectorAll('#nav li a[href*="#"]')
    for (const menuItem of menuItems) {


        menuItem.addEventListener('click', function(event) {
            if (menuItem.nextElementSibling && menuItem.nextElementSibling.classList.contains('subnav')) {
                event.preventDefault()
            } else {
                header.style.height = '46px'
            }
        })
    }
</script>