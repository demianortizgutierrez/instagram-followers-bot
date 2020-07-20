<?php
try {
    $conn = new PDO("mysql:host=$dbserver;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function getInvites($conn) {
    $date = new DateTime();

    $sql = "SELECT * FROM invites WHERE converted IS NULL AND date <= ( CURDATE() - INTERVAL 3 DAY ) ORDER BY date ASC";
    $query = $conn->query($sql);
    $result = $query->fetchAll(); 
    $count = count($result);

    if ($count > 0) {
        return $result;
    }
    return [];
}

function unfollow($conn, $ig, $userId, $userName, $account) {
    $sql = "UPDATE invites SET converted = 0 WHERE user_id = '".$userId."' AND taken_from = '".$account."'";
    $conn->query($sql);    

    $ig->people->unfollow($userId);
    sleep(10);
}

function setConverted($conn, $userId, $account) {
    $sql = "UPDATE invites SET converted = 1 WHERE user_id = '".$userId."' AND taken_from = '".$account."'";
    $conn->query($sql);
}

function isAlreadySent($conn, $userId, $userName, $account) {
    $sql = "SELECT * FROM invites WHERE user_id =".$userId;
    $query = $conn->query($sql);
    $result = $query->fetchAll(); 
    $count = count($result);

    if ($count > 0) {
        return true;
    }
    return false;
}

function follow($conn, $ig, $userId, $userName, $account) {
    $sql = "INSERT INTO invites (account, user_id, username, taken_from, date) VALUES ('chicojeringa',".$userId.", '".$userName."', '".$account."', NOW())";
    $conn->query($sql);

    $ig->people->follow($userId);
    $rand = rand(5,10);
    echo "Following and -> sleeping for ".$rand."s...\n";
    sleep($rand);
}
?>