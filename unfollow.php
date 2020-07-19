<?php
set_time_limit(0);

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/resources/config.php';
require __DIR__.'/db.php';

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
try {
    $login = $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

$invites = getInvites($conn);
$i = 0;

foreach($invites as $invite) {

    $userId = $invite['user_id'];
    $userName = $invite['username'];
    $account = $invite['taken_from'];
    $status = 0;
    $sleeping = rand(1,25);

    sleep($sleeping);

    try {
        $isFollowingMe = $ig->people->getFriendship($userId)->getFollowedBy();
    } catch(Exception $e) {
        $message = $e->getMessage();

        if ($message == "Requested resource does not exist.") {
            $sql = "UPDATE invites SET converted = 0 WHERE user_id = '".$userId."' AND taken_from = '".$account."'";
            $conn->query($sql);
        }

        continue;
    }

    if (!$isFollowingMe) {
        $i++;
        unfollow($conn, $ig, $userId, $userName, $account);
    } else {
        $status = 1;
        setConverted($conn, $userId, $account);
    }

    printf("[%s] [%s] [%s] [%b]", $userName, $userId, $account, $status);
    echo PHP_EOL;
}