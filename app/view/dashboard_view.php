<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
        }
        h1 {
            margin-bottom: 40px;
            color: #333;
        }
        .carousel-container {
            width: 350px;
            perspective: 1000px;
        }
        .carousel {
            width: 350px;
            height: 220px;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s ease;
        }
        .carousel-item {
            position: absolute;
            width: 300px;
            height: 180px;
            background: #007BFF;
            color: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            text-align: center;
            line-height: 180px;
            font-size: 24px;
            cursor: pointer;
            user-select: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .carousel-item:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            transform: scale(1.05);
        }

        
        .item1 { transform: rotateY(0deg) translateZ(350px); }
        .item2 { transform: rotateY(120deg) translateZ(350px); }
        .item3 { transform: rotateY(240deg) translateZ(350px); }

        
        .controls {
            margin-top: 30px;
            text-align: center;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?= htmlspecialchars($userName) ?>!</h1>

    <div class="carousel-container">
        <div class="carousel" id="carousel">
            <div class="carousel-item item1" onclick="location.href='friend_challenges.php'">Friend Challenges</div>
            <div class="carousel-item item2" onclick="location.href='workout_logger.php'">Workout Logger</div>
            <div class="carousel-item item3" onclick="location.href='water_tracker.php'">Water Tracker</div>
        </div>
    </div>

    <div class="controls">
        <button onclick="rotateCarousel(-1)">&#8592; Prev</button>
        <button onclick="rotateCarousel(1)">Next &#8594;</button>
    </div>

<script>
    let angle = 0;
    const carousel = document.getElementById('carousel');

    function rotateCarousel(direction) {
        angle += direction * 120;
        carousel.style.transform = `translateZ(-350px) rotateY(${angle}deg)`;
    }
</script>

</body>
</html>
