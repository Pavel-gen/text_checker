<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проверка текста</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>Проверка текста</h1>
    <form id="textForm">
        <textarea name="text" rows="10" cols="50"></textarea><br>
        <button type="submit">Проверить</button>
    </form>
    <div id="result"></div>
    <h2>История проверок</h2>
    <ul id="history"></ul>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
