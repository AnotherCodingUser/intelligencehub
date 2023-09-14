<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Doc Title</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../styles/flexbox_grid.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="grid-container">
        <div class="grid-item header"><h1>SCPF GOI Database</h1></div>
        <div class="grid-item sidebar">Sidebar</div>
        <div class="grid-item content">
        <?php 
            include 'new-search.php';
        ?>  
        </div>
        <div class="grid-item footer">Footer</div>
    </div>
</body>
</html>


