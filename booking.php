<?php
include 'includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$bus_id = intval($_GET['bus_id'] ?? $_POST['bus_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM buses WHERE id = ?");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$bus) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Bus not found.</div></div>";
    include 'includes/footer.php';
    exit();
}

$bookedSeats = [];
$stmt = $conn->prepare("
    SELECT bp.seat_row, bp.seat_col
    FROM booking_passengers bp
    JOIN bookings b ON bp.booking_id = b.id
    WHERE b.bus_id = ? AND b.status = 'booked'
");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $bookedSeats[] = $row['seat_row'] . '-' . $row['seat_col'];
}
$stmt->close();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSeatsRaw = trim($_POST['selected_seats'] ?? '');
    $passengerNames = $_POST['passenger_name'] ?? [];

    if ($selectedSeatsRaw === '') {
        $error = "Please select at least one seat.";
    } else {
        $seatList = explode(',', $selectedSeatsRaw);
        $seatList = array_filter($seatList);

        $cleanNames = [];
        foreach ($passengerNames as $name) {
            $name = trim($name);
            if ($name !== '') {
                $cleanNames[] = $name;
            }
        }

        if (count($seatList) !== count($cleanNames)) {
            $error = "Passenger names must match selected seats.";
        } else {
            foreach ($seatList as $seat) {
                if (in_array($seat, $bookedSeats)) {
                    $error = "One of the selected seats is already booked.";
                    break;
                }
            }
        }

        if ($error === "") {
            $booking_code = "BK" . time() . rand(100, 999);
            $total_amount = count($seatList) * floatval($bus['price']);
            $user_id = $_SESSION['user_id'];
            $journey_date = $bus['journey_date'];

            $stmt = $conn->prepare("INSERT INTO bookings (booking_code, user_id, bus_id, journey_date, total_amount, status) VALUES (?, ?, ?, ?, ?, 'booked')");
            $stmt->bind_param("siisd", $booking_code, $user_id, $bus_id, $journey_date, $total_amount);
            $stmt->execute();
            $booking_id = $stmt->insert_id;
            $stmt->close();

            for ($i = 0; $i < count($seatList); $i++) {
                list($r, $c) = explode('-', $seatList[$i]);
                $ticket_code = "TKT" . time() . rand(1000, 9999) . $i;
                $pname = $cleanNames[$i];

                $stmt = $conn->prepare("INSERT INTO booking_passengers (booking_id, passenger_name, seat_row, seat_col, ticket_code) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isiis", $booking_id, $pname, $r, $c, $ticket_code);
                $stmt->execute();
                $stmt->close();
            }

            header("Location: ticket.php?booking_code=" . urlencode($booking_code));
            exit();
        }
    }
}

$bookedSeatsJson = json_encode($bookedSeats);
?>

<section class="py-5 bg-light-custom min-vh-100">
    <div class="container">
        <h2 class="section-title text-center mb-4">Book Ticket</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo h($error); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="booking-details-card">
                    <img src="assets/images/<?php echo h($bus['image']); ?>" class="img-fluid rounded mb-3" alt="Bus">
                    <h4><?php echo h($bus['bus_name']); ?></h4>
                    <p class="mb-1"><strong>Route:</strong> <?php echo h($bus['source']); ?> → <?php echo h($bus['destination']); ?></p>
                    <p class="mb-1"><strong>Date:</strong> <?php echo h($bus['journey_date']); ?></p>
                    <p class="mb-1"><strong>Departure:</strong> <?php echo date("h:i A", strtotime($bus['departure_time'])); ?></p>
                    <p class="mb-1"><strong>Arrival:</strong> <?php echo date("h:i A", strtotime($bus['arrival_time'])); ?></p>
                    <p class="mb-1"><strong>Type:</strong> <?php echo h($bus['bus_type']); ?></p>
                    <p class="mb-0"><strong>Price per Seat:</strong> ₹<span id="seatPrice"><?php echo h($bus['price']); ?></span></p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="seat-card">
                    <h4 class="mb-3">Select Seats</h4>

                    <div class="seat-legend mb-3">
                        <span class="legend-box available-box"></span> Available
                        <span class="legend-box selected-box ms-3"></span> Selected
                        <span class="legend-box booked-box ms-3"></span> Booked
                    </div>

                    <div id="seatGrid" class="seat-grid mb-4"></div>

                    <form method="POST" id="bookingForm">
                        <input type="hidden" name="bus_id" value="<?php echo $bus['id']; ?>">
                        <input type="hidden" name="selected_seats" id="selectedSeatsInput">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Selected Seats</label>
                            <div id="selectedSeatsText" class="selected-seat-text">No seats selected</div>
                        </div>

                        <div id="passengerFields" class="mb-3"></div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Total Amount</label>
                            <div class="total-amount-box">₹<span id="totalAmount">0</span></div>
                        </div>

                        <button type="submit" class="btn btn-theme">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const rows = <?php echo (int)$bus['total_rows']; ?>;
    const cols = <?php echo (int)$bus['total_cols']; ?>;
    const seatPrice = <?php echo (float)$bus['price']; ?>;
    const bookedSeats = <?php echo $bookedSeatsJson; ?>;
</script>

<?php include 'includes/footer.php'; ?>