<div class="content__header">
	<h1 class="content__header-title">Author manager</h1>
</div>

<div class="content__body">
	<div class="row">
		<div class="grCol grL-12">
			<div class="card card--info">
				<div class="card__header">
					<h3 class="card__header-title">
						Danh sách tác giả
					</h3>

					<div class="card__header-action">
						<button
							class="btn"
							id="js-add"
							type="button"
							data-bs-toggle="modal"
							data-bs-target="#add-modal">
							<i class="fa-solid fa-plus"></i>
							Thêm tác giả
						</button>
					</div>
				</div>
				<div class="card__body">

					<!-- Thanh tìm kiếm -->
					<form class="form__filter" action="<?= WEB_ROOT . '/quan-tri/quan-ly-tac-gia/tim-kiem' ?>" method="get">
						<div class="form__filter-group">
							<label
								for="search-keyword"
								class="form__filter-label">Tìm kiếm</label>
							<input
								type="text"
								name="search-keyword"
								id="search-keyword"
								class="form__filter-input"
								placeholder="Nhập từ khóa"
								required
							/>
						</div>
                        
						<div class="form__filter-group">
							<button
								type="submit"
								class="btn">
								Tra cứu
							</button>
						</div>
					<div class="form__filter-group">
						<a href="<?= WEB_ROOT . '/quan-tri/quan-ly-tac-gia' ?>" class="btn">Hủy</a>
					</div>
				</form>

				<!-- Bảng dữ liệu -->
					<table class="table" id="author-table">
						<thead>
							<tr>
								<th>STT</th>
								<th>Name Author</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($author as $key => $row) : ?>

								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $row['author_name'] ?></td>
									<td>
										<button
											class="js-edit btn__action"
											type="button"
											data-key="<?= $row['id'] ?>"
											data-bs-toggle="modal"
											data-bs-target="#modalAuthor">
											<i
												class="fa-solid fa-pen-to-square"></i>
										</button>
										<button
										  data-key="<?= $row['id'] ?>"
											class="js-delete btn__action"
											type="button">
											<i
												class="fa-solid fa-trash-can"></i>
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
                <h5 class="modal-title">Thêm tác giả</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="add-form">
                    <!-- author name -->
                    <div class="mb-3">
                        <label for="author-name" class="col-form-label">Tên tác giả:</label>
                        <textarea class="form-control form-control-lg" id="author-name" name="author-name" row="2"></textarea>
                        <div class="invalid-feedback">
                            Vui lòng nhập tên tác giả.
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
                <h5 class="modal-title">Sửa tác giả</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- End Header modal -->

            <!-- Modal body -->
            <div class="modal-body">
                <form id="edit-form">
                    <!-- author name -->
                    <div class="mb-3">
                        <label for="edit-author-name" class="col-form-label">Tên tác giả:</label>
                        <textarea class="form-control form-control-lg" id="edit-author-name" name="edit-author-name" row="2"></textarea>
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