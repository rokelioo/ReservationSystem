document.addEventListener('DOMContentLoaded', function() {
    function updateDisplay() {
        const specialistId = document.getElementById('reservation-codes').getAttribute('data-specialist-id');
        fetch('/api/display/' + specialistId)
            .then(response => response.json())
            .then(data => {
                const reservationCodesElement = document.getElementById('reservation-codes');
                reservationCodesElement.innerHTML = ''; // Clear current codes

                let upcomingReservationShown = false;

                data.forEach(reservation => {
                    const div = document.createElement('div');
                    div.classList.add('reservation-code');
                    if (reservation.status === "Begin") {
                        div.textContent = "Current visit number: " + reservation.code;
                    }
                    else if (!upcomingReservationShown) {
                        div.textContent = "Upcoming visit number: " + reservation.code;
                        upcomingReservationShown = true;
                    }
                    else {
                        div.textContent = reservation.code;
                    }

                    reservationCodesElement.appendChild(div);
                });
            });
    }

    setInterval(updateDisplay, 3000); // update every 3 seconds
});