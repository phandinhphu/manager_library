const getWebRoot = () => {
    const pathname = window.location.pathname; // Lấy đường dẫn hiện tại của URL
    const projectName = 'manager_library'; // Tên dự án của bạn
    const projectIndex = pathname.indexOf(projectName) + projectName.length; // Tìm vị trí của tên dự án trong đường dẫn
    const projectPath = pathname.substring(0, projectIndex); // Cắt chuỗi để lấy đường dẫn đến tên dự án
    return `${window.location.origin}${projectPath}`; // Trả về đường dẫn gốc của dự án
};
const PATH = getWebRoot() + '/admin/category/'; // Đường dẫn API

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
            url: getWebRoot() + "/quan-tri/quan-ly-the-loai/" + "insert",
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
            url: getWebRoot() + "/quan-tri/quan-ly-the-loai/" + "get",
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
            url: getWebRoot() + "/quan-tri/quan-ly-the-loai/" + "update",
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
                url: getWebRoot() + "/quan-tri/quan-ly-the-loai/" + "delete",
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