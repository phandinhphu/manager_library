<div class="container">
    <div class="grid wide">
        <div class="row">
            <div class="grCol grL-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Sách mới nhất</h2>
                </div>
                <div class="component__content">
                    <div class="list__items">
                        <div class="row">
                            <?php 
                                if (isset($newbooks)) {
                                    foreach($newbooks as $book) {
                            ?>
                            <a
                                href="<?= WEB_ROOT . '/sach/chi-tiet/' . $book['id'] ?>"
                                class="grCol grL-2-4 grM-4 grC-6">
                                <div class="list__item">
                                    <img
                                        class="book__img"
                                        src="<?php echo $book['img']; ?>"
                                        alt="" />
                                    <div class="book__title">
                                        <h3
                                            class="book__title-text">
                                            <?php echo $book['book_name']; ?>
                                        </h3>
                                    </div>
                                </div>
                            </a>
                            <?php 
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grCol grL-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Most liked</h2>
                </div>
                <div class="component__content">
                    <div class="list__items">
                        <div class="row">
                            <?php 
                                if (isset($mostlikedbooks)) {
                                    foreach($mostlikedbooks as $book) {
                            ?>
                            <a
                                href="<?= WEB_ROOT . '/sach/chi-tiet/' . $book['id'] ?>"
                                class="grCol grL-2-4 grM-4 grC-6">
                                <div class="list__item">
                                    <img
                                        class="book__img"
                                        src="<?php echo $book['img']; ?>"
                                        alt="" />
                                    <div class="book__title">
                                        <h3
                                            class="book__title-text">
                                            <?php echo $book['book_name']; ?>
                                        </h3>
                                    </div>
                                </div>
                            </a>
                            <?php 
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grCol grL-12 grC-12 component">
                <div class="title">
                    <h2 class="title__text">Most disliked</h2>
                </div>
                <div class="component__content">
                    <div class="list__items">
                        <div class="row">
                            <?php 
                                if (isset($mostdislikedbooks)) {
                                    foreach($mostdislikedbooks as $book) {
                            ?>
                            <a
                                href="<?= WEB_ROOT . '/sach/chi-tiet/' . $book['id'] ?>"
                                class="grCol grL-2-4 grM-4 grC-6">
                                <div class="list__item">
                                    <img
                                        class="book__img"
                                        src="<?php echo $book['img']; ?>"
                                        alt="" />
                                    <div class="book__title">
                                        <h3
                                            class="book__title-text">
                                            <?php echo $book['book_name']; ?>
                                        </h3>
                                    </div>
                                </div>
                            </a>
                            <?php 
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
