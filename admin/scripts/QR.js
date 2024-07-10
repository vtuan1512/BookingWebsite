// let MY_BANK = {
//     BANK_ID: "MB",
//     ACCOUNT_NO: "77999979799999"
// }

const paid_content = document.getElementById("paid_content").textContent;
const paid_price = document.getElementById("paid_price").textContent;
const qr_code = document.querySelector('.course_qr_img');
let QR = `https://img.vietqr.io/image/${MY_BANK.BANK_ID}-${MY_BANK.ACCOUNT_NO}-compact2.png?amount=${paid_price}&addInfo=${paid_content}`;
qr_code.src = QR;

const countdownElement = document.getElementById("countdown");
let countdownTime = 10 * 60;
updateCountdownDisplay();

const countdownInterval = setInterval(() => {
    countdownTime--;
    updateCountdownDisplay();
    if (countdownTime <= 0) {
        handleCountdownEnd();
        clearInterval(countdownInterval);
    }
}, 1000);

function updateCountdownDisplay() {
    const minutes = Math.floor(countdownTime / 60);
    const seconds = countdownTime % 60;
    countdownElement.textContent = `Time Left: ${minutes}m ${seconds}s`;
}

function handleCountdownEnd() {
    alert("Failed: Payment not confirmed within 10 minutes.");
    update('failed')
    window.location.href = "index.php";
}
document.getElementById("confirmation_button").addEventListener("click", function () {
    checkPaid(paid_price, paid_content);
});

let isSuccess = false; // Đặt biến isSuccess để theo dõi trạng thái thanh toán

async function checkPaid(price, content) {
    if (isSuccess) {
        return;
    } else {
        try {
            const response = await fetch("https://script.google.com/macros/s/AKfycbx7_-jWwxL-ByMmYNgH71ibnlLpyi0Zegw3S8gGooweZ2qsg3e0_koYm0FgUW_QTmqI/exec");
            
            const data = await response.json();

            if (data && data.data && data.data.length > 0) {
                const lastPayment = data.data[data.data.length - 1]; 

                if (lastPayment) {
                    const lastPrice = lastPayment["Giá trị"];
                    const lastContent = lastPayment["Mô tả"];

                    if (lastPrice !== undefined && lastContent !== undefined) {
                        if (lastPrice >= price && lastContent.includes(content)) {
                            clearInterval(countdownInterval); 
                            alert("Payment success!");
                            update('success'); 
                            isSuccess = true;
                        } else {
                            console.log("Payment has not been successful.");
                            update('failed');
                        }
                    } else {
                        console.log("payment data not accept.");
                    }
                } else {
                    console.log("No data from payment.");
                }
            } else {
                console.log("No data returned from the API.");
            }
        } catch(error) {
            console.error("Error sending request to API:", error);
        }
    }
}

function update(status) {
    let paid_content = document.getElementById('paid_content').textContent;
    let paid_price = document.getElementById('paid_price').textContent;
    let data1 = new FormData();

    data1.append('update', '');
    data1.append('paid_content', paid_content);
    data1.append('paid_price', paid_price);
    data1.append('status', status); // Gửi trạng thái thanh toán lên máy chủ

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/pay_response.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            if (xhr.responseText.trim() !== "") {
                try {
                    let responseData = JSON.parse(xhr.responseText);
                    console.log(responseData);
                    if (responseData.status === 'success') {
                        console.log("payment success")
                        window.location.href = 'pay_status.php?order=' + paid_content ;
                    } else if(responseData.status ==='failed'){
                        console.log('Payment failed!');
                        window.location.href = 'pay_status.php?order=' + paid_content ;
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                    alert('Lỗi xử lý dữ liệu từ máy chủ.');
                }
            } else {
                alert('Không có dữ liệu trả về từ máy chủ.');
            }
        } else {
            alert('Đã có lỗi xảy ra từ máy chủ. Vui lòng thử lại sau.');
        }
    };
    
    xhr.send(data1);
}


