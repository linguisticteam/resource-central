<?php
define('START', true);

require_once('lib/config.php');
require_once('/lib/database.php');

$connection = db_connect();
if(!$connection) {
    echo "Could not connect to database: " . mysql_error();
}
?>
<h2>Add a new video tutorial:</h2>
<form method="post" action="lib/add_entry.php" name="add_video">
    Author: <input type="text" name="author"><br>
    Title: <input type="text" name="title"><br>
    URL: <input type="text" name="url"><br>
    Parts: <input type="text" name="parts"><br> <!-- Note: to remove this field and instead get the number of parts from the number of legally filled in fields.-->
    Description: <br>
    <textarea name="description"></textarea>
    <br><br>
    <div>+ Add another part (would be clickable)</div>
    <div>
        <h3>Part 2:</h3>
        <div style="float:left;">
            Part title:<br>
            <input type="text" name="part_2_title">
        </div>
        <div style="float:left;">
            URL of this part:<br>
            <input type="text" name="part_2_url">
        </div>
    </div>
    <div style="clear:both;"></div>
    <br><br>
    <input type="submit" value="submit">
</form>