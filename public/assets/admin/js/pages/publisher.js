const WEB_ROOT = window.location.origin + '/manager_library';

$(document).ready(() => {

    // Hàm kiểm tra dữ liệu nhập vào
    function validateData(data) {
        if (data.name.trim() === '') {
            alert("Vui lòng nhập tên nhà xuất bản!");
            return false;
        }
        return true;
    }
    
    //Thêm nhà xuất bản
    $('#js-add-save').click(() => {
        const data = {
            name: $("#publisher-name").val(),
        };
        console.log(getWebRoot());
        console.log(WEB_ROOT);
        
        console.log(data['name']);
        if (!validateData(data)) return;
        
        $.ajax({
            url: WEB_ROOT + "/quan-tri/quan-ly-nha-xuat-ban/" + "addPublisher",
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

    //Sửa nhà xuất bản
    $('#publisher-table').on('click', '.js-edit', function() {
        let publisherId = $(this).data('key');
        console.log(publisherId);

        $.ajax({
            url: WEB_ROOT + "/quan-tri/quan-ly-nha-xuat-ban/" + "get",
            type: "POST",
            data: { id: publisherId },
            success: (response) => {
                $("#js-edit-save").val(publisherId);
                $('#edit-publisher-name').val(response.name);
                $("#edit-modal").modal("show");
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Lưu thay đổi nhà xuất bản
    $('#js-edit-save').click(() => {
        const data = {
            id: $("#js-edit-save").val(),
            name: $("#edit-publisher-name").val(),
        };
        if (!validateData(data)) return;

        $.ajax({
            url: WEB_ROOT + "/quan-tri/quan-ly-nha-xuat-ban/" + "updatePublisher",
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

    // Xóa nhà xuất bản
    $('#publisher-table').on('click', '.js-delete', function() {
        let publisherId = $(this).data('key');
        
        if(confirm("Bạn có chắc chắn muốn xóa nhà xuất bản này không?")) {
            $.ajax({
                url: WEB_ROOT + "/quan-tri/quan-ly-nha-xuat-ban/" + "deletePublisher",
                type: "POST",
                data: { id: publisherId },
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