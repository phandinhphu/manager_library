const PATH = "http://localhost/manager_library/admin/category/";

$(document).ready(() => {

    // Hàm kiểm tra dữ liệu nhập vào
    function validateData(data) {
        if (data.name.trim() === '') {
            alert("Vui lòng nhập tên thể loại!");
            return false;
        }
        return true;
    }
    
    //Thêm thể loại
    $('#js-add-save').click(() => {
        const data = {
            name: $("#category-name").val(),
        };
        if (!validateData(data)) return;
        
        $.ajax({
            url: PATH + "insert",
            type: "POST",
            data: data,
            success: (response) => {
                if (response.success === 1) {
                    alert(response.message);
                    $('#add-modal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    //Sửa thể loại
    $('#category-table').on('click', '.js-edit', function() {
        let categoryId = $(this).data('key');
        console.log(categoryId);

        $.ajax({
            url: PATH + "get",
            type: "POST",
            data: { id: categoryId },
            success: (response) => {
                $("#js-edit-save").val(categoryId);
                $('#edit-category-name').val(response.name);
                $("#edit-modal").modal("show");
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Lưu thay đổi thể loại
    $('#js-edit-save').click(() => {
        const data = {
            id: $("#js-edit-save").val(),
            name: $("#edit-category-name").val(),
        };
        if (!validateData(data)) return;

        $.ajax({
            url: PATH + "update",
            type: "POST",
            data: data,
            success: (response) => {
                if (response.success === 1) {
                    alert(response.message);
                    $('#edit-modal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Xóa thể loại
    $('#category-table').on('click', '.js-delete', function() {
        let categoryId = $(this).data('key');
        
        if(confirm("Bạn có chắc chắn muốn xóa thể loại này không?")) {
            $.ajax({
                url: PATH + "delete",
                type: "POST",
                data: { id: categoryId },
                success: (response) => {
                    if (response.success === 1) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    });
})