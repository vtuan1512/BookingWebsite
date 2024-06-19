function booking_analytic(period = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/dashboard_manager.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        console.log(this.responseText); // Check the response content
        try {
            let data = JSON.parse(this.responseText);

            document.getElementById('total_bookings').textContent = data.total_bookings;
            document.getElementById('total_amt').textContent = data.total_amt + ' VND';

            document.getElementById('active_bookings').textContent = data.active_bookings;
            document.getElementById('active_amt').textContent = data.active_amt + ' VND';

            document.getElementById('payment_failed_bookings').textContent = data.payment_failed_bookings;
            document.getElementById('payment_failed_amt').textContent = data.payment_failed_amt + ' VND';

            document.getElementById('cancelled_bookings').textContent = data.cancelled_bookings;
            document.getElementById('cancelled_amt').textContent = data.cancelled_amt + ' VND';


            let inactive_booking = data.total_bookings - data.active_bookings - data.payment_failed_bookings - data.cancelled_bookings;
            let inactive_price = data.total_amt - data.active_amt - data.payment_failed_amt - data.cancelled_amt;

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Total Bookings:  " + data.total_bookings
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label}",
                    yValueFormatString: "#,##0",
                    dataPoints: [
                        { label: "Payment Failed Bookings", y: data.payment_failed_bookings },
                        { label: "Inactive Bookings", y: inactive_booking },
                        { label: "Active Bookings", y: data.active_bookings },
                        { label: "Cancelled Bookings", y: data.cancelled_bookings }
                    ]
                }]
            });
            chart.render();

            var chart = new CanvasJS.Chart("chartPrice", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Total Price:  " + data.total_amt + " VND"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label}",
                    yValueFormatString: "#,##0",
                    dataPoints: [
                        { label: "Payment Failed Price", y: data.payment_failed_amt },
                        { label: "Inactive Price", y: inactive_price },
                        { label: "Active Price", y: data.active_amt },
                        { label: "Cancelled Price", y: data.cancelled_amt }
                    ]
                }]
            });
            chart.render();
        } catch (e) {
            console.error("Failed to parse JSON response:", e);
        }
    };

    xhr.send('booking_analytic&period=' + period);
}

function renderYearlyChart(year) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/yearly_chart_manager.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        console.log(this.responseText);
        try {
            let data = JSON.parse(this.responseText);

            if (data.length > 0) {
                var chart = new CanvasJS.Chart("chartyear", {
                    title: {
                        text: "Bookings Success Per Month in " + year
                    },
                    axisY: {
                        title: "Number of Bookings"
                    },
                    data: [{
                        type: "spline",
                        dataPoints: data
                    }]
                });
                chart.render();
            } else {
                document.getElementById("chartyear").innerHTML = "<h3>No data available for " + year + "</h3>";
            }
        } catch (e) {
            console.error("Failed to parse JSON response:", e);
        }
    };

    xhr.send('year=' + year);

    let xhrPrice = new XMLHttpRequest();
    xhrPrice.open('POST', 'ajax/yearly_price_manager.php', true);
    xhrPrice.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhrPrice.onload = function () {
        console.log(this.responseText);
        try {
            let data = JSON.parse(this.responseText);

            if (data.length > 0) {
                var chart = new CanvasJS.Chart("chartyearprice", {
                    title: {
                        text: "Monthly income in " + year
                    },
                    axisY: {
                        title: "Price (VND)"
                    },
                    data: [{
                        type: "spline",
                        dataPoints: data
                    }]
                });
                chart.render();
            } else {
                document.getElementById("chartyearprice").innerHTML = "<h3>No data available for " + year + "</h3>";
            }
        } catch (e) {
            console.error("Failed to parse JSON response:", e);
        }
    };

    xhrPrice.send('year=' + year);
}

window.onload = function () {
    booking_analytic();
    renderYearlyChart(new Date().getFullYear());
    document.getElementById('yearForm').onsubmit = function (event) {
        event.preventDefault();
        let year = document.getElementById('year').value;
        renderYearlyChart(year);
    };
}
