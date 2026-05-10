document.addEventListener("DOMContentLoaded", function () {
    const seatGrid = document.getElementById("seatGrid");
    const selectedSeatsInput = document.getElementById("selectedSeatsInput");
    const selectedSeatsText = document.getElementById("selectedSeatsText");
    const passengerFields = document.getElementById("passengerFields");
    const totalAmount = document.getElementById("totalAmount");

    if (!seatGrid || typeof rows === "undefined") {
        return;
    }

    let selectedSeats = [];

    function renderPassengerFields() {
        passengerFields.innerHTML = "";

        selectedSeats.forEach((seat, index) => {
            const card = document.createElement("div");
            card.className = "passenger-input-card";

            card.innerHTML = `
                <label class="form-label fw-semibold">Passenger Name for Seat ${seat}</label>
                <input type="text" name="passenger_name[]" class="form-control" placeholder="Enter passenger name" required>
            `;

            passengerFields.appendChild(card);
        });
    }

    function updateSelectedData() {
        selectedSeatsInput.value = selectedSeats.join(",");
        selectedSeatsText.textContent = selectedSeats.length ? selectedSeats.join(", ") : "No seats selected";
        totalAmount.textContent = selectedSeats.length * seatPrice;
        renderPassengerFields();
    }

    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            const seatKey = `${r}-${c}`;
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "seat";
            btn.textContent = `${r},${c}`;

            if (bookedSeats.includes(seatKey)) {
                btn.classList.add("booked");
                btn.disabled = true;
            } else {
                btn.classList.add("available");
                btn.addEventListener("click", function () {
                    if (selectedSeats.includes(seatKey)) {
                        selectedSeats = selectedSeats.filter(s => s !== seatKey);
                        btn.classList.remove("selected");
                        btn.classList.add("available");
                    } else {
                        selectedSeats.push(seatKey);
                        btn.classList.remove("available");
                        btn.classList.add("selected");
                    }
                    updateSelectedData();
                });
            }

            seatGrid.appendChild(btn);
        }
    }
});