<?php
include("connect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - Your Trusted Pharmacy</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #002147;
            line-height: 1.6;
        }

        /* Utility */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Bar */
        .top-bar {
            text-align: center;
            font-size: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            color: #666;
        }

        /* Header */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .logo-circle {
            width: 35px;
            height: 35px;
            background-color: #002147;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon {
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-radius: 4px;
            transform: rotate(45deg);
        }

        .search-container {
            flex: 1;
            margin: 0 40px;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 10px 20px 10px 40px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: #002147;
            font-size: 14px;
            font-weight: 500;
        }

        .user-circle {
            width: 35px;
            height: 35px;
            background-color: #002147;
            border-radius: 50%;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 60px 0;
        }

        .hero h1 {
            font-size: 72px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 18px;
            font-weight: 500;
        }

        .hero-banner {
            width: 100%;
            height: 350px;
            background-color: #002147;
            border-radius: 20px;
            margin-top: 40px;
        }

        /* About Section */
        .about-section {
            text-align: center;
            padding: 80px 0;
        }

        .about-section h2 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .about-description {
            max-width: 900px;
            margin: 0 auto 60px;
            color: #333;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 40px 25px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card h3 {
            font-size: 28px;
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .card p {
            font-size: 14px;
            color: #444;
            text-align: center;
        }

        .card ul {
            text-align: left;
            font-size: 14px;
            color: #2563eb;
            padding-left: 0;
            list-style: none;
        }

        .card ul li {
            margin-bottom: 15px;
            text-decoration: underline;
            cursor: pointer;
        }

        /* Project Info */
        .project-info {
            border: 1px solid #002147;
            border-radius: 20px;
            padding: 60px;
            text-align: center;
            margin-bottom: 100px;
        }

        .project-info h2 {
            font-size: 48px;
            margin-bottom: 30px;
        }

        .project-info p {
            font-size: 14px;
            margin-bottom: 20px;
            color: #333;
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            background-color: #002147;
            color: white;
            padding: 60px 0 20px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .footer-contact p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .footer-contact a {
            color: white;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 300px;
        }

        .footer-logo {
            width: 40px;
            height: 40px;
            border: 1px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .footer-right p {
            font-size: 13px;
            line-height: 1.4;
        }

        .copyright {
            text-align: center;
            font-size: 10px;
            opacity: 0.6;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
        }

        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 48px;
            }
            header {
                flex-direction: column;
                gap: 20px;
            }
            .search-container {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">MediTrack</div>

    <div class="container">
        <header>
            <a href="#" class="text-decoration-none">
            <img src="assets/medilogo.png" height="20" class="me-2">
        </a>
            
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search Medicine">
            </div>

            <nav>
                <a href="#">Home</a>
                <a href="#">Medicines</a>
                <div class="user-circle"></div>
            </nav>
        </header>

        <section class="hero">
            <h1>Welcome to<br>MediTrack</h1>
            <p>For Every Family. For Every Health Need.</p>
            <div class="hero-banner"></div>
        </section>

        <section class="about-section">
            <h2>About</h2>
            <p class="about-description">
                Welcome to MediTrack – your trusted partner in accessing affordable and reliable medicines online. We make it simple for you to browse, learn about, and order the health products you need, all from the comfort of your home.
            </p>

            <div class="cards-grid">
                <div class="card">
                    <h3>Our<br>Commitment</h3>
                    <p>To become the most trusted and convenient online pharmacy platform in the Philippines — where customers feel confident, informed, and cared for.</p>
                </div>

                <div class="card">
                    <h3>Why Choose<br>MediTrack?</h3>
                    <ul>
                        <li>Explore a wide selection of medicines organized by category for easier navigation</li>
                        <li>Check real-time stock availability before you buy</li>
                        <li>Understand each product better with helpful details and transparent pricing</li>
                    </ul>
                </div>

                <div class="card">
                    <h3>Our Vision</h3>
                    <p>We aim to empower every customer with a hassle-free and informative pharmacy experience. Whether you're managing prescriptions or looking for over-the-counter remedies, MediTrack is here to help every step of the way.</p>
                </div>
            </div>
        </section>

        <section class="project-info">
            <h2>Project Information</h2>
            <p>MediTrack Pharmacy is a final project developed as a course requirement by the following BS Information Technology 2-2 students: Ilagan, Jan Maridel T., Mercado, Jerome P., Marasigan, Marcus Gabriel O., and Villanueva, Fiona Jade M. of the Polytechnic University of the Philippines - Sto. Tomas Campus.</p>
            <p>This project represents the application of their learning in real-world software development — focusing on customer service, pharmacy operations, inventory management, and e-commerce functionality.</p>
            <p>It showcases the use of web technologies such as HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL, aiming to bridge the gap between classroom theory and practical solutions in healthcare accessibility.</p>
        </section>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-contact">
                    <p>Email: <a href="mailto:support@meditrack.com">support@meditrack.com</a></p>
                    <p>Phone: +63 912 345 6789</p>
                    <p>123 Health St., Makati City, Philippines</p>
                </div>
                <div class="footer-right">
                    <div class="footer-logo">
                        <div class="logo-icon" style="width:15px; height:15px;"></div>
                    </div>
                    <p>Your health, our priority — trusted care from MediTrack Pharmacy.</p>
                </div>
            </div>
            <div class="copyright">
                © 2025 MediTrack Pharmacy. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>