<?php
if (isset($_POST['submit'])) {
    include('task.php');
    $task = new Task();
    $task->application_id = $_POST['application_id'];
    $task->secret = $_POST['secret'];
    $task->lat = $_POST['lat'];
    $task->lon = $_POST['lon'];
    $task->whetherAppId = $_POST['whetherAppId'];
    $task->phone = '+306911111111';
    $check = $task->sendMessageToUser();
    $message =  "Message sent to user";
    if ($check == false) {
        $message =  "Message didn't send to user";
    }
    echo $message;
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
</head>
<style>
    input {
        width: 200px;
        height: 40px;
        ;
    }
</style>

<body style="margin:60px 100px 100px 600px;">
    <div> Configrations Of Whether app</div>
    <br>

    <form action="#" method="post">

        <input type="text" name="application_id" placeholder="Route Application id" require="required" value="62401e082d985400016d1a92">
        <br>
        <br>
        <input type="text" name="secret" placeholder="Route Application secret" require="required" value="toVqY7OVMN">
        <br>
        <br>
        <input type="text" name="lat" placeholder="Latitude of location" require="required" value="37.39">
        <br>
        <br>
        <input type="text" name="lon" placeholder="Longitude of location" require="required" value="-122.08">
        <br>
        <br>
        <input type="text" name="whetherAppId" placeholder="Whether application id" require="required" value="b385aa7d4e568152288b3c9f5c2458a5">
        <br>
        <br>
        <input type="text" name="phone" disabled value="" placeholder="Phone is given 306911111111">
        <br>
        <br>
        <input type="submit" name="submit" value="Send Tempreture to user" style="background-color: green; border-radius: 1px; width:200px;">



    </form>



</body>

</html>