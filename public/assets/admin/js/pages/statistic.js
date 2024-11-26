const getWebRoot = () => {
    const pathname = window.location.pathname; // Lấy đường dẫn hiện tại của URL
    const projectName = 'manager_library'; // Tên dự án của bạn
    const projectIndex = pathname.indexOf(projectName) + projectName.length; // Tìm vị trí của tên dự án trong đường dẫn
    const projectPath = pathname.substring(0, projectIndex); // Cắt chuỗi để lấy đường dẫn đến tên dự án
    return `${window.location.origin}${projectPath}`; // Trả về đường dẫn gốc của dự án
};

$(document).ready(() => {
    $('#reader-table').on('click', '.js-view', function() {
        let readerId = $(this).data('key');
        console.log(readerId);

        $.ajax({
            url: getWebRoot() + '/admin/statistic/' + "getDetailReader",
            type: "POST",
            data: { id: readerId },
            success: (response) => {
                if (Array.isArray(response)) {
                    updateTable(response);
                } else {
                    updateTable([]);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#reader-table').on('click', '.js-export', function() {
        let readerId = $(this).data('key');
        console.log(readerId);
        window.location.href = getWebRoot() + '/admin/statistic/' + "exportFileExcelReader/" + readerId;
    });

});

document.addEventListener('DOMContentLoaded', function() {
    const readerChart = document.getElementById('reader-chart').getContext('2d');
    const fineChart = document.getElementById('fine-chart').getContext('2d');
    $.ajax({
        url: getWebRoot() + '/admin/statistic/' + "getReaderStats",
        type: "POST",
        success: (response) => {
            console.log(response);
            if(response) {
                new Chart(readerChart, {
                    type: 'bar',
                    data: {
                        labels: ['Đang mượn', 'Đã trả', 'Quá hạn'],
                        datasets: [{
                            data: [
                                response.reader.borrowing || 0,
                                response.reader.returned  || 0,
                                response.reader.overdue   || 0
                            ],
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)'
                            ],
                            borderWidth: 2,
                            hoverOffset: 4
                            
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw} độc giả`;
                                    }
                                }
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
                new Chart(fineChart, {
                    type: 'line',
                    data: {
                        labels: Array.from({length: 12}, (_, i) => `Tháng ${i + 1}`),
                        datasets: [{
                            label: 'Tiền phạt theo tháng',
                            data: (() => {
                                
                                const monthlyData = new Array(12).fill(0);
                                
                                response.fine.forEach(item => {
                                    console.log('Processing item:', item);
                                    const month = parseInt(item.time_fine.split('/')[0]) - 1;
                                    console.log('Parsed month:', month);
                                    
                                    if (month >= 0 && month < 12) {
                                        monthlyData[month] = Number(item.total_amount) || 0;
                                    }
                                });
                                
                                // Debug log final data
                                console.log('Monthly data:', monthlyData);
                                return monthlyData;
                            })(),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: 'rgb(54, 162, 235)',
                            pointBorderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Tiền phạt: ' + new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND',
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        },
        error: (xhr, status, error) => {
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            chartContainer.innerHTML = '<div class="alert alert-danger">Không thể tải dữ liệu</div>';
        }
    });
});

function updateTable(data) {
    $('#borrowed-detail').empty();
    if(data.length === 0) {
        $('#borrowed-detail').append('<tr><td colspan="7">Không có dữ liệu</td></tr>');
        return;
    }
    else {
        data.forEach((item, index) => {
            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.book_name ? item.book_name : ""}</td>
                    <td>${item.borrow_date ? item.borrow_date : ""}</td>
                    <td>${item.return_date ? item.return_date : ""}</td>
                    <td>${item.quantity ? item.quantity : 0}</td>
                    <td>${item.borrow_status ? item.borrow_status : ""}</td>
                    <td>${item.fine ? item.fine : 0}</td>
                </tr>
            `;
            $('#borrowed-detail').append(row);
        });
    }
}

function prepareFineData(response) {
    // Fixed monthly labels
    const monthLabels = Array.from({length: 12}, (_, i) => 
        `Tháng ${String(i + 1).padStart(2, '0')}`
    );

    // Initialize data array with zeros
    const monthlyData = new Array(12).fill(0);

    // Map data to months
    response.fine.forEach(item => {
        const month = parseInt(item.time_fine.split('/')[1]) - 1;
        if (month >= 0 && month < 12) {
            monthlyData[month] = Number(item.total_amount) || 0;
        }
    });

    return {
        labels: monthLabels,
        values: monthlyData
    };
}
