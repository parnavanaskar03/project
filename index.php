<?php
include("config/db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Smart Campus Management System</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#f8fafc;
    color:#334155;
}

/* NAVBAR */

.navbar{
    height:72px;
    background:#172554;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 7%;
    color:white;
}

.logo{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo-icon{
    width:42px;
    height:42px;
    background:#2563eb;
    border-radius:11px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.logo h2{
    font-size:21px;
}

.nav-links{
    display:flex;
    align-items:center;
    gap:10px;
}

.nav-links a{
    color:#dbeafe;
    text-decoration:none;
    font-size:14px;
    padding:10px 15px;
    border-radius:8px;
}

.nav-links a:hover{
    background:#2563eb;
    color:white;
}

.nav-register{
    background:#2563eb;
    color:white !important;
}

/* HERO */

.hero{
    background:#172554;
    color:white;
    padding:85px 7% 100px;
    position:relative;
    overflow:hidden;
}

.hero-content{
    max-width:720px;
}

.hero-tag{
    display:inline-block;
    background:rgba(255,255,255,0.10);
    color:#bfdbfe;
    padding:8px 14px;
    border-radius:20px;
    font-size:12px;
    margin-bottom:20px;
}

.hero h1{
    font-size:48px;
    line-height:1.15;
    margin-bottom:18px;
}

.hero h1 span{
    color:#60a5fa;
}

.hero p{
    color:#cbd5e1;
    font-size:16px;
    line-height:1.7;
    max-width:650px;
}

.hero-buttons{
    display:flex;
    gap:13px;
    margin-top:30px;
}

.primary-btn{
    display:inline-block;
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:14px 24px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.primary-btn:hover{
    background:#1d4ed8;
}

.secondary-btn{
    display:inline-block;
    background:white;
    color:#172554;
    text-decoration:none;
    padding:14px 24px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.secondary-btn:hover{
    background:#e2e8f0;
}

/* HERO BOX */

.hero-box{
    position:absolute;
    right:7%;
    top:75px;
    width:300px;
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.12);
    border-radius:18px;
    padding:25px;
}

.hero-box h3{
    font-size:18px;
    margin-bottom:18px;
}

.hero-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 0;
    border-bottom:1px solid rgba(255,255,255,0.10);
}

.hero-item:last-child{
    border-bottom:none;
}

.hero-item-icon{
    width:38px;
    height:38px;
    background:#2563eb;
    border-radius:9px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.hero-item span{
    font-size:13px;
    color:#dbeafe;
}

/* FEATURES */

.features-section{
    padding:70px 7%;
    background:#f8fafc;
}

.section-heading{
    text-align:center;
    max-width:650px;
    margin:0 auto 40px;
}

.section-heading h2{
    color:#172554;
    font-size:30px;
    margin-bottom:10px;
}

.section-heading p{
    color:#64748b;
    font-size:14px;
    line-height:1.6;
}

.feature-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    max-width:1100px;
    margin:auto;
}

.feature-card{
    background:white;
    padding:25px;
    border-radius:15px;
    border:1px solid #e8edf5;
    box-shadow:0 5px 18px rgba(0,0,0,0.05);
    transition:0.2s;
}

.feature-card:hover{
    transform:translateY(-4px);
    box-shadow:0 9px 25px rgba(0,0,0,0.08);
}

.feature-card-icon{
    width:50px;
    height:50px;
    border-radius:12px;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:23px;
    margin-bottom:18px;
}

.feature-card h3{
    color:#172554;
    font-size:17px;
    margin-bottom:8px;
}

.feature-card p{
    color:#64748b;
    font-size:13px;
    line-height:1.6;
}

/* HOW IT WORKS */

.how-section{
    background:white;
    padding:70px 7%;
}

.steps{
    max-width:1000px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.step{
    text-align:center;
    padding:20px;
}

.step-number{
    width:45px;
    height:45px;
    background:#2563eb;
    color:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px;
    font-weight:bold;
}

.step h3{
    color:#172554;
    font-size:15px;
    margin-bottom:7px;
}

.step p{
    color:#64748b;
    font-size:12px;
    line-height:1.5;
}

/* CTA */

.cta{
    margin:0 7% 60px;
    background:#172554;
    border-radius:18px;
    padding:45px;
    text-align:center;
    color:white;
}

.cta h2{
    font-size:27px;
    margin-bottom:10px;
}

.cta p{
    color:#bfdbfe;
    font-size:14px;
    margin-bottom:22px;
}

.cta a{
    display:inline-block;
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:13px 25px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.cta a:hover{
    background:#1d4ed8;
}

/* FOOTER */

footer{
    background:#0f172a;
    color:#94a3b8;
    text-align:center;
    padding:25px 15px;
}

footer h3{
    color:white;
    font-size:17px;
    margin-bottom:7px;
}

footer p{
    font-size:12px;
    line-height:1.6;
}

/* MOBILE */

@media(max-width:900px){

    .hero-box{
        display:none;
    }

    .hero h1{
        font-size:40px;
    }

    .feature-grid{
        grid-template-columns:1fr 1fr;
    }

    .steps{
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:600px){

    .navbar{
        padding:0 20px;
    }

    .nav-links a{
        padding:8px;
        font-size:12px;
    }

    .logo h2{
        font-size:18px;
    }

    .hero{
        padding:60px 20px 70px;
    }

    .hero h1{
        font-size:32px;
    }

    .hero p{
        font-size:14px;
    }

    .hero-buttons{
        flex-direction:column;
    }

    .primary-btn,
    .secondary-btn{
        text-align:center;
        width:100%;
    }

    .features-section,
    .how-section{
        padding:50px 20px;
    }

    .section-heading h2{
        font-size:25px;
    }

    .feature-grid{
        grid-template-columns:1fr;
    }

    .steps{
        grid-template-columns:1fr;
    }

    .cta{
        margin:0 20px 45px;
        padding:35px 20px;
    }

    .cta h2{
        font-size:23px;
    }

}

</style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">

        <div class="logo-icon">
            🏫
        </div>

        <h2>Campus Care</h2>

    </div>


    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="login.php">
            Login
        </a>

        <a href="register.php" class="nav-register">
            Register
        </a>

    </div>

</nav>


<!-- HERO -->

<section class="hero">

    <div class="hero-content">

        <div class="hero-tag">
            SMART CAMPUS MANAGEMENT SYSTEM
        </div>

        <h1>
            Making Campus Life
            <span>Smarter & Safer</span>
        </h1>

        <p>
            Campus Care is a digital platform that connects
            students, teachers and college administration to
            report issues, track solutions and access important
            campus services easily.
        </p>


        <div class="hero-buttons">

            <a href="login.php" class="primary-btn">
                Get Started →
            </a>

            <a href="register.php" class="secondary-btn">
                Create Account
            </a>

        </div>

    </div>


    <!-- HERO FEATURE BOX -->

    <div class="hero-box">

        <h3>
            Campus Services
        </h3>


        <div class="hero-item">

            <div class="hero-item-icon">
                📋
            </div>

            <span>
                Complaint Management
            </span>

        </div>


        <div class="hero-item">

            <div class="hero-item-icon">
                📢
            </div>

            <span>
                Smart Notice Board
            </span>

        </div>


        <div class="hero-item">

            <div class="hero-item-icon">
                🔎
            </div>

            <span>
                Lost & Found
            </span>

        </div>


        <div class="hero-item">

            <div class="hero-item-icon">
                🚨
            </div>

            <span>
                Emergency SOS
            </span>

        </div>

    </div>

</section>


<!-- FEATURES -->

<section class="features-section">

    <div class="section-heading">

        <h2>
            Everything in One Place
        </h2>

        <p>
            Campus Care brings important campus services
            together in one simple digital platform.
        </p>

    </div>


    <div class="feature-grid">


        <div class="feature-card">

            <div class="feature-card-icon">
                📋
            </div>

            <h3>
                Smart Complaints
            </h3>

            <p>
                Students can report campus problems and
                track their complaint status.
            </p>

        </div>


        <div class="feature-card">

            <div class="feature-card-icon">
                📢
            </div>

            <h3>
                Notice Board
            </h3>

            <p>
                Important college notices can be accessed
                easily from one place.
            </p>

        </div>


        <div class="feature-card">

            <div class="feature-card-icon">
                🔎
            </div>

            <h3>
                Lost & Found
            </h3>

            <p>
                Report lost items and check found item
                information inside the campus.
            </p>

        </div>


        <div class="feature-card">

            <div class="feature-card-icon">
                🚨
            </div>

            <h3>
                Emergency SOS
            </h3>

            <p>
                Students can send an emergency request
                to the responsible campus team.
            </p>

        </div>


        <div class="feature-card">

            <div class="feature-card-icon">
                ⭐
            </div>

            <h3>
                Feedback
            </h3>

            <p>
                Students can share feedback and help
                improve campus services.
            </p>

        </div>


        <div class="feature-card">

            <div class="feature-card-icon">
                🔐
            </div>

            <h3>
                Role Based Access
            </h3>

            <p>
                Different dashboards are provided for
                students, teachers and administration.
            </p>

        </div>

    </div>

</section>


<!-- HOW IT WORKS -->

<section class="how-section">

    <div class="section-heading">

        <h2>
            How Campus Care Works
        </h2>

        <p>
            A simple process for managing campus services.
        </p>

    </div>


    <div class="steps">


        <div class="step">

            <div class="step-number">
                1
            </div>

            <h3>
                Register
            </h3>

            <p>
                Create your Campus Care account.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                2
            </div>

            <h3>
                Report
            </h3>

            <p>
                Submit a complaint or use another
                campus service.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                3
            </div>

            <h3>
                Track
            </h3>

            <p>
                Check the status of your request.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                4
            </div>

            <h3>
                Resolve
            </h3>

            <p>
                The responsible campus team works
                to resolve the issue.
            </p>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="cta">

    <h2>
        Ready to Use Campus Care?
    </h2>

    <p>
        Login to your account or create a new account
        to access campus services.
    </p>

    <a href="login.php">
        Login to Campus Care →
    </a>

</section>


<!-- FOOTER -->

<footer>

    <h3>
        Campus Care
    </h3>

    <p>
        Smart Campus Management System
        <br>
        © 2026 Campus Care | Developed by Team CampusCare
    </p>

</footer>


</body>

</html>