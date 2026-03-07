<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd Tools Home</title>
    <?php require_once(__DIR__ . '/includes/favicon.php'); ?>
    <style>
        :root {
            --primary: #055489;
            --primary-dark: #044073;
            --primary-light: #0668aa;
            --bg-soft: #eef4fb;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #4b5563;
            --border: #dbe7f5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 15% 10%, #dceaf8 0%, #eef4fb 45%, #f7fbff 100%);
            color: var(--text-main);
            padding: 20px;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
        }

        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 12px 28px rgba(5, 84, 137, 0.28);
            margin-bottom: 24px;
        }

        .hero h1 {
            font-size: clamp(1.4rem, 2.6vw, 2.1rem);
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }

        .hero p {
            font-size: clamp(0.95rem, 1.4vw, 1.05rem);
            opacity: 0.95;
            line-height: 1.5;
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .app-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 40px 24px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            min-height: 320px;
        }

        .app-card:hover,
        .app-card:focus-visible {
            transform: translateY(-4px);
            border-color: #9ec3ea;
            box-shadow: 0 14px 32px rgba(5, 84, 137, 0.18);
            outline: none;
        }

        .app-icon {
            width: 140px;
            height: 140px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 0.4px;
            margin-bottom: 20px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 8px 20px rgba(5, 84, 137, 0.25);
        }

        .app-title {
            color: var(--text-main);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .app-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
            flex-grow: 1;
        }

        .app-cta {
            margin-top: 16px;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 700;
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .hero {
                padding: 20px 16px;
                border-radius: 12px;
            }

            .app-card {
                padding: 32px 20px;
                min-height: 280px;
            }

            .app-icon {
                width: 110px;
                height: 110px;
                border-radius: 16px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="hero" aria-label="Dashboard Intro">
            <h1>DepEd HRMPSB Systems Dashboard</h1>
            <p>Select a tool below to open the system. Large cards are provided for simple and quick navigation.</p>
        </section>

        <section class="apps-grid" aria-label="System Launchers">
            <a class="app-card" href="index.php">
                <div class="app-icon" aria-hidden="true">EV</div>
                <div class="app-title">Evaluation System</div>
                <div class="app-desc">Process qualification criteria, compute scores, and generate individual evaluation outputs.</div>
                <div class="app-cta">Open System</div>
            </a>

            <a class="app-card" href="reclassification_form.php">
                <div class="app-icon" aria-hidden="true">RC</div>
                <div class="app-title">Reclassification</div>
                <div class="app-desc">Evaluate teaching positions and manage RFTP form-based reclassification records.</div>
                <div class="app-cta">Open System</div>
            </a>

            <a class="app-card" href="dtr_generator_redesigned.php">
                <div class="app-icon" aria-hidden="true">DTR</div>
                <div class="app-title">DTR Generator</div>
                <div class="app-desc">Upload attendance files and generate DTR outputs for download and archiving.</div>
                <div class="app-cta">Open System</div>
            </a>

            <a class="app-card" href="gwa_calculator.html">
                <div class="app-icon" aria-hidden="true">GWA</div>
                <div class="app-title">GWA Calculator</div>
                <div class="app-desc">Compute grade weighted averages, save applicant records, and compare GWA history.</div>
                <div class="app-cta">Open System</div>
            </a>
        </section>
    </main>
</body>
</html>
