<style>
        .footer {
            background-color: var(--dark);
            color: var(--white);
            padding: 4rem 2rem 2rem;
            margin-top: 4rem;
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -0.5rem;
            width: 50px;
            height: 2px;
            background-color: var(--accent);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }</style>


<footer class="footer">
        <div class="footer-grid">
            <div class="footer-column">
                <h3>About Us</h3>
                <p>Paradise Resort offers luxurious accommodations with world-class amenities and exceptional service.</p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="http://localhost/resort-ms/user/view/home.php">Home</a></li>
                    <li><a href="http://localhost/resort-ms/user/booking/rooms.php">Rooms</a></li>
                    <li><a href="http://localhost/resort-ms/user/booking/index.php">Bookings</a></li>
                    <li><a href="http://localhost/resort-ms/user/payment/index.php">Payment</a></li>
                    <li><a href="http://localhost/resort-ms/user/feedback/index.php">Feedback</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact Us</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-phone"></i> +1 234 567 890</li>
                    <li><i class="fas fa-envelope"></i> info@paradiseresort.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Paradise Street, Beach City</li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Paradise Resort. All rights reserved.</p>
        </div>
    </footer>
