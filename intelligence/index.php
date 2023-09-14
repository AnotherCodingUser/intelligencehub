<?php 
    include 'header.html';
    $AUTHTOKEN = 42;
?>

<form action="search.php" method="get">
<input type="hidden" name="id" value="<?php echo $AUTHTOKEN ?>">
<input type="hidden" name="search_type" value="user">
<input type="text" name="qry" placeholder="User ID here...">
<input type="Submit">
</form>


<form action="search.php" method="get">
<input type="hidden" name="id" value="<?php echo $AUTHTOKEN ?>">
<input type="hidden" name="search_type" value="group">
<input type="text" name="qry" placeholder="Group ID here...">
<input type="Submit">
</form>


<?php
    include 'footer.html';
?>