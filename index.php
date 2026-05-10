<?php include 'includes/header.php'; ?>

<section class="hero-section" style="background-image: url('assets/images/background-banner.png');">
    <div class="hero-overlay">
        <div class="container text-center">
            <div class="hero-box">
                <h1 class="display-4 fw-bold mb-3">Horizon Haul</h1>
                <p class="lead mb-0">Book bus tickets quickly, safely, and comfortably.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light-custom">
    <div class="container">
        <div class="info-box mx-auto mb-4">
            <ol class="mb-0">
                <li>Login or signup</li>
                <li>Enter start location, destination, and date</li>
                <li>Search for buses and book tickets</li>
            </ol>
        </div>

        <div class="search-card" id="search-section">
            <h2 class="section-title text-center mb-4">Search Buses</h2>
            <form action="search.php" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">From</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-location-arrow"></i></span>
                        <input type="text" name="source" class="form-control" placeholder="Enter start location" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">To</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-map-pin"></i></span>
                        <input type="text" name="destination" class="form-control" placeholder="Enter destination location" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Journey Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                        <input type="date" name="journey_date" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme w-100">
                        <i class="fa-solid fa-magnifying-glass-location me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="book-banner-section">
    <img src="assets/images/booknow.jpg" alt="Book now banner" class="img-fluid w-100 book-banner-img">
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Why Choose Us</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="offer-card text-center h-100">
                    <img src="assets/images/Red Modern Fashion Sale Instagram Story.jpg" alt="Offer image" class="offer-img">
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card feature-card-brown text-center h-100">
                    <img src="assets/images/greenbus.jpg" alt="Green bus" class="feature-img mb-3">
                    <h5>Relax. Ride. Reach on time.</h5>
                    <p class="mb-0">Travel through beautiful routes with spacious seating, clean interiors, and a smooth ride experience.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card feature-card-blue text-center h-100">
                    <img src="assets/images/busimg.jpg" alt="Premium bus" class="feature-img mb-3">
                    <h5>Premium buses. Maximum comfort.</h5>
                    <p class="mb-0">Enjoy premium buses with air-conditioning, reclining seats, GPS tracking, and professional drivers.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card feature-card-orange text-center h-100">
                    <img src="assets/images/nightbus.jpg" alt="Night bus" class="feature-img mb-3">
                    <h5>Safe night travel. Anytime, anywhere.</h5>
                    <p class="mb-0">Book night buses and long-distance routes easily with flexible timings and affordable fares.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light-custom">
    <div class="container">
        <h2 class="section-title text-center mb-4">More About Our Travel</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="gallery-card">
                    <img src="assets/images/ash-gerlach-6fF-Ojxov6o-unsplash.jpg" alt="Inside bus" class="gallery-img">
                    <h5 class="mt-3">Comfortable Interiors</h5>
                    <p>Travel with better seating, better atmosphere, and a smooth experience.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="gallery-card">
                    <img src="assets/images/ghat.jpg" alt="Road trip image" class="gallery-img">
                    <h5 class="mt-3">Scenic Routes</h5>
                    <p>Enjoy beautiful journeys on mountain and highway routes.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="gallery-card">
                    <img src="assets/images/roadbus.jpg" alt="Road bus" class="gallery-img">
                    <h5 class="mt-3">Easy City Travel</h5>
                    <p>Fast and accessible routes for daily and long-distance travel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Popular Routes</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="route-card">
                    <h5>Pune → Mumbai</h5>
                    <p class="mb-0">Fast and comfortable buses with AC and Sleeper options.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="route-card">
                    <h5>Pune → Nashik</h5>
                    <p class="mb-0">Affordable buses with good timings and seat choices.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="route-card">
                    <h5>Hyderabad → Gujarat</h5>
                    <p class="mb-0">Reliable seating buses available for long routes.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>