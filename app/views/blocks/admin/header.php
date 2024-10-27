<header class="header">
    <div class="header__content">
        <div class="header__logo">
            <a href="./index.html">Logo</a>
        </div>
        <div class="header__menu">
            <ul class="menu__items">
                <?php if (isset($_SESSION['admin'])) : ?>
                    <li class="menu__item">
                        <img
                            class="menu__item-avatar"
                            src="<?= WEB_ROOT . '/' . $_SESSION['admin']['avatar'] ?>"
                            alt="avatar" />
                    </li>

                    <li class="menu__item">
                        <h5 class="menu__item-name"><?= $_SESSION['admin']['name'] ?></h5>
                    </li>
                <?php else : ?>
                    <li class="menu__item">
                        <a class="menu__item-link" href="./index.html">Đăng nhập</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>