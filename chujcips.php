<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" placeholder="Nazwa pliku" name="a"><br>
        <input type="submit" value="Zatwierdź">
    </form>
    <form action="" method="POST">
        <input type="text" placeholder="Nazwa pliku" name="b"><br>
        <input type="submit" value="Zatwierdź">
    </form>
    <form action="" method="POST">
        <input type="text" placeholder="Nazwa pliku" name="c"><br>
        <input type="submit" value="Zatwierdź">
    </form>
    <form action="" method="POST">
        <input type="text" placeholder="Nazwa pliku" name="d"><br>
        <input type="submit" value="Zatwierdź">
    </form>
    <?php
        @$a = $_POST['a'];
        @$b = $_POST['b'];
        @$c = $_POST['c'];
        @$d = $_POST['d'];

        
    ?>
</body>
</html>


