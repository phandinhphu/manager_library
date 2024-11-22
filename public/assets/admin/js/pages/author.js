const getWebRoot = () => {
  const pathname = window.location.pathname; // Lấy đường dẫn hiện tại của URL
  const projectName = 'manager_library'; // Tên dự án của bạn
  const projectIndex = pathname.indexOf(projectName) + projectName.length; // Tìm vị trí của tên dự án trong đường dẫn
  const projectPath = pathname.substring(0, projectIndex); // Cắt chuỗi để lấy đường dẫn đến tên dự án
  return `${window.location.origin}${projectPath}`; // Trả về đường dẫn gốc của dự án
};

$(document).ready(() => {

  // Hàm kiểm tra dữ liệu nhập vào
  function validateData(data) {
    if (data.name.trim() === '') {
        alert("Vui lòng nhập tên tác giả!");
        return false;
    }
    return true;
  }

  

  //Thêm tác giả
  $('#js-add-save').click(() => {
    const data = {
        name: $("#author-name").val(),
    };
    if (!validateData(data)) return;
    
    $.ajax({
        url: getWebRoot() + "/quan-tri/quan-ly-tac-gia/" + "insert",
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

  // cập nhật tác giả

  $('#author-table').on('click', '.js-edit', function() {
    let authorId = $(this).data('key');
    console.log(authorId);

    $.ajax({
        url: getWebRoot() + "/quan-tri/quan-ly-tac-gia/" + "get",
        type: "POST",
        data: { id: authorId },
        success: (response) => {
            $("#js-edit-save").val(authorId);
            $('#edit-author-name').val(response.name);
            $("#edit-modal").modal("show");
        },
        error: function (error) {
            console.error(error);
        }
    });
  });

  // Lưu thay đổi tác giả
  $('#js-edit-save').click(() => {
    const data = {
        id: $("#js-edit-save").val(),
        name: $("#edit-author-name").val(),
    };
    if (!validateData(data)) return;

    $.ajax({
        url: getWebRoot() + "/quan-tri/quan-ly-tac-gia/" + "update",
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

  // xóa tác giả

  $('#author-table').on('click', '.js-delete', function() {
    let authorId = $(this).data('key');
    
    if(confirm("Bạn có chắc chắn muốn xóa tác giả này không?")) {
        $.ajax({
            url: getWebRoot() + "/quan-tri/quan-ly-tac-gia/" + "delete",
            type: "POST",
            data: { id: authorId },
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
});