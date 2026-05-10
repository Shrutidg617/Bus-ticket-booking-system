<?php
include 'includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);

    $stmt = $conn->prepare("
        SELECT bp.id, bp.booking_id
        FROM booking_passengers bp
        JOIN bookings b ON bp.booking_id = b.id
        WHERE bp.id = ? AND b.user_id = ?
    ");
    $stmt->bind_param("ii", $ticket_id, $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $ticket = $res->fetch_assoc();
    $stmt->close();

    if ($ticket) {
        $stmt = $conn->prepare("DELETE FROM booking_passengers WHERE id = ?");
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();
        $stmt->close();

        $booking_id = $ticket['booking_id'];

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking_passengers WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $countRes = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($countRes['total'] == 0) {
            $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled', total_amount = 0 WHERE id = ?");
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        }

        $message = "Single ticket cancelled successfully.";
    } else {
        $message = "Ticket not found.";
    }
}

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    $stmt = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $booking = $res->fetch_assoc();
    $stmt->close();

    if ($booking) {
        $stmt = $conn->prepare("DELETE FROM booking_passengers WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled', total_amount = 0 WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $stmt->close();

        $message = "Full booking cancelled successfully.";
    } else {
        $message = "Booking not found.";
    }
}
?>

<section class="py-5 bg-light-custom min-vh-100">
    <div class="container">
        <h2 class="section-title text-center mb-4">Cancel Ticket / Booking</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo h($message); ?></div>
        <?php endif; ?>

        <div class="text-center">
            <a href="ticket.php" class="btn btn-theme">Go to My Tickets</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>