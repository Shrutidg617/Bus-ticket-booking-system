<?php
include 'includes/header.php';

$source = trim($_GET['source'] ?? '');
$destination = trim($_GET['destination'] ?? '');
$journey_date = trim($_GET['journey_date'] ?? '');

$buses = [];

if ($source && $destination && $journey_date) {
    $stmt = $conn->prepare("SELECT * FROM buses WHERE source = ? AND destination = ? AND journey_date = ? ORDER BY departure_time ASC");
    $stmt->bind_param("sss", $source, $destination, $journey_date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
    $stmt->close();
}
?>

<section class="py-5 bg-light-custom min-vh-100">
    <div class="container">
        <h2 class="section-title text-center mb-4">Available Buses</h2>

        <div class="search-summary mb-4">
            <strong>From:</strong> <?php echo h($source); ?> |
            <strong>To:</strong> <?php echo h($destination); ?> |
            <strong>Date:</strong> <?php echo h($journey_date); ?>
        </div>

        <?php if (!$source || !$destination || !$journey_date): ?>
            <div class="alert alert-warning">Please fill all search details first.</div>
        <?php elseif (empty($buses)): ?>
            <div class="alert alert-danger">No buses found for this route and date.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($buses as $bus): ?>
                    <div class="col-lg-6">
                        <div class="bus-card h-100">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="assets/images/<?php echo h($bus['image']); ?>" class="img-fluid bus-card-img" alt="Bus image">
                                </div>
                                <div class="col-md-8">
                                    <div class="p-3">
                                        <h4 class="mb-2"><?php echo h($bus['bus_name']); ?></h4>
                                        <p class="mb-1"><strong>Bus Code:</strong> <?php echo h($bus['bus_code']); ?></p>
                                        <p class="mb-1"><strong>Type:</strong> <?php echo h($bus['bus_type']); ?></p>
                                        <p class="mb-1"><strong>Departure:</strong> <?php echo date("h:i A", strtotime($bus['departure_time'])); ?></p>
                                        <p class="mb-1"><strong>Arrival:</strong> <?php echo date("h:i A", strtotime($bus['arrival_time'])); ?></p>
                                        <p class="mb-1"><strong>Price per Seat:</strong> ₹<?php echo h($bus['price']); ?></p>
                                        <p class="mb-3"><strong>Seat Layout:</strong> <?php echo h($bus['total_rows']); ?> x <?php echo h($bus['total_cols']); ?></p>

                                        <a href="booking.php?bus_id=<?php echo $bus['id']; ?>" class="btn btn-theme">
                                            <i class="fa-solid fa-ticket me-2"></i>Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>