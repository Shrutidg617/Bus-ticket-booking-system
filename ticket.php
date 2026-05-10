<?php
include 'includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');
$userId = $_SESSION['user_id'];

$allBookings = [];

$stmt = $conn->prepare("
    SELECT 
        b.*, 
        bs.bus_name, bs.bus_code, bs.source, bs.destination, bs.bus_type,
        bs.departure_time, bs.arrival_time, bs.image, bs.total_rows, bs.total_cols
    FROM bookings b
    JOIN buses bs ON b.bus_id = bs.id
    WHERE b.user_id = ?
    ORDER BY b.journey_date DESC, b.id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $allBookings[] = $row;
}
$stmt->close();

$upcomingBookings = [];
$pastBookings = [];
$cancelledBookings = [];

foreach ($allBookings as $booking) {
    if ($booking['status'] === 'cancelled') {
        $cancelledBookings[] = $booking;
    } elseif ($booking['journey_date'] < $today) {
        $pastBookings[] = $booking;
    } else {
        $upcomingBookings[] = $booking;
    }
}

function renderBookingCard($booking, $conn, $showCancelButtons = true, $statusLabel = 'Booked', $statusClass = 'success') {
    $passengers = [];
    $stmt = $conn->prepare("SELECT * FROM booking_passengers WHERE booking_id = ?");
    $stmt->bind_param("i", $booking['id']);
    $stmt->execute();
    $r2 = $stmt->get_result();
    while ($row2 = $r2->fetch_assoc()) {
        $passengers[] = $row2;
    }
    $stmt->close();

    $allBookedSeats = [];
    $stmt = $conn->prepare("
        SELECT bp.seat_row, bp.seat_col
        FROM booking_passengers bp
        JOIN bookings b ON bp.booking_id = b.id
        WHERE b.bus_id = ? AND b.journey_date = ? AND b.status = 'booked'
    ");
    $stmt->bind_param("is", $booking['bus_id'], $booking['journey_date']);
    $stmt->execute();
    $seatRes = $stmt->get_result();
    while ($seatRow = $seatRes->fetch_assoc()) {
        $allBookedSeats[] = $seatRow['seat_row'] . '-' . $seatRow['seat_col'];
    }
    $stmt->close();
    ?>
    
    <div class="ticket-card mb-4">
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <img src="assets/images/<?php echo h($booking['image']); ?>" alt="Bus image" class="img-fluid rounded ticket-side-img">
            </div>

            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h3 class="mb-1"><?php echo h($booking['bus_name']); ?></h3>
                        <p class="mb-1"><strong>Booking Code:</strong> <?php echo h($booking['booking_code']); ?></p>
                        <p class="mb-1"><strong>Bus Code:</strong> <?php echo h($booking['bus_code']); ?></p>
                        <p class="mb-1">
                            <strong>Status:</strong>
                            <span class="badge bg-<?php echo h($statusClass); ?>"><?php echo h($statusLabel); ?></span>
                        </p>
                    </div>
                    <div>
                        <button onclick="window.print()" class="btn btn-theme-outline">Print Ticket</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>From:</strong> <?php echo h($booking['source']); ?></p>
                        <p class="mb-1"><strong>To:</strong> <?php echo h($booking['destination']); ?></p>
                        <p class="mb-1"><strong>Date:</strong> <?php echo h($booking['journey_date']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Departure:</strong> <?php echo date("h:i A", strtotime($booking['departure_time'])); ?></p>
                        <p class="mb-1"><strong>Arrival:</strong> <?php echo date("h:i A", strtotime($booking['arrival_time'])); ?></p>
                        <p class="mb-1"><strong>Total:</strong> ₹<?php echo h($booking['total_amount']); ?></p>
                    </div>
                </div>

                <hr>

                <?php if (empty($passengers)): ?>
                    <div class="alert alert-secondary mb-0">No passenger tickets available for this booking.</div>
                <?php else: ?>
                    <h5 class="mb-3">Passenger Tickets</h5>
                    <div class="row g-3">
                        <?php foreach ($passengers as $p): ?>
                            <div class="col-md-6">
                                <div class="mini-ticket">
                                    <p class="mb-1"><strong>Ticket Code:</strong> <?php echo h($p['ticket_code']); ?></p>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo h($p['passenger_name']); ?></p>
                                    <p class="mb-2"><strong>Seat:</strong> Row <?php echo h($p['seat_row']); ?>, Column <?php echo h($p['seat_col']); ?></p>

                                    <div class="ticket-seat-matrix mb-3">
                                        <?php for ($r = 0; $r < (int)$booking['total_rows']; $r++): ?>
                                            <div class="ticket-seat-row">
                                                <?php for ($c = 0; $c < (int)$booking['total_cols']; $c++): ?>
                                                    <?php
                                                    $seatKey = $r . '-' . $c;
                                                    $seatClass = 'seat-free';

                                                    if ($r == $p['seat_row'] && $c == $p['seat_col']) {
                                                        $seatClass = 'seat-current';
                                                    } elseif (in_array($seatKey, $allBookedSeats)) {
                                                        $seatClass = 'seat-booked';
                                                    }
                                                    ?>
                                                    <div class="ticket-seat-box <?php echo $seatClass; ?>">
                                                        <?php echo $r . ',' . $c; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endfor; ?>
                                    </div>

                                    <?php if ($showCancelButtons): ?>
                                        <a href="cancel.php?ticket_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger mt-1"
                                           onclick="return confirm('Cancel this ticket?')">Cancel This Ticket</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($showCancelButtons): ?>
                    <div class="mt-4">
                        <a href="cancel.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-danger"
                           onclick="return confirm('Cancel full booking?')">Cancel Full Booking</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php
}
?>

<section class="py-5 bg-light-custom min-vh-100">
    <div class="container">
        <h2 class="section-title text-center mb-4">My Tickets</h2>

        <?php if (empty($allBookings)): ?>
            <div class="alert alert-info">No bookings found.</div>
        <?php else: ?>

            <div class="mb-5">
                <h3 class="mb-3">Upcoming Tickets</h3>
                <?php if (empty($upcomingBookings)): ?>
                    <div class="alert alert-secondary">No upcoming tickets.</div>
                <?php else: ?>
                    <?php foreach ($upcomingBookings as $booking): ?>
                        <?php renderBookingCard($booking, $conn, true, 'Upcoming', 'success'); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <h3 class="mb-3">Past / Completed Tickets</h3>
                <?php if (empty($pastBookings)): ?>
                    <div class="alert alert-secondary">No past tickets.</div>
                <?php else: ?>
                    <?php foreach ($pastBookings as $booking): ?>
                        <?php renderBookingCard($booking, $conn, false, 'Completed', 'primary'); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <h3 class="mb-3">Cancelled Tickets</h3>
                <?php if (empty($cancelledBookings)): ?>
                    <div class="alert alert-secondary">No cancelled tickets.</div>
                <?php else: ?>
                    <?php foreach ($cancelledBookings as $booking): ?>
                        <?php renderBookingCard($booking, $conn, false, 'Cancelled', 'danger'); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</section>

<style>
.ticket-seat-matrix{
    margin-top: 10px;
}

.ticket-seat-row{
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 6px;
    margin-bottom: 6px;
}

.ticket-seat-box{
    text-align: center;
    padding: 8px 4px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.seat-free{
    background-color: #28a745;
}

.seat-booked{
    background-color: #dc3545;
}

.seat-current{
    background-color: #355070;
}

@media print {
    .ticket-seat-box{
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<?php include 'includes/footer.php'; ?>