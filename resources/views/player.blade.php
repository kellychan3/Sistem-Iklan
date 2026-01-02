<!DOCTYPE html>
<html>
<head>
    <title>Ad Player</title>
    <style>
        html, body {
            margin: 0;
            background: black;
        }
        video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
        }
    </style>
</head>
<body>

<video id="adPlayer" autoplay muted></video>

<script>
    const videos = @json($videos);
    let index = 0;
    const player = document.getElementById('adPlayer');

    function playNext() {
        if (!videos.length) return;

        player.src = videos[index].src;
        player.load();
        player.play();

        index = (index + 1) % videos.length;
    }

    player.addEventListener('ended', playNext);
    player.onerror = playNext;

    playNext();
</script>

</body>
</html>
