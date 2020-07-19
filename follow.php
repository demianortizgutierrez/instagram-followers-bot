<?php
set_time_limit(0);

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/resources/config.php';
require __DIR__.'/db.php';

$val =  getopt("a:");
$account = $val['a'];

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
try {
    $login = $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}
try {
    $rankToken = \InstagramAPI\Signatures::generateUUID();

    // Get the UserPK ID for "natgeo" (National Geographic).
    $userId = $ig->people->getUserIdForName($account);

    // Starting at "null" means starting at the first page.
    $maxId = null;
    do {
        // Request the page corresponding to maxId.
        $followers = $ig->people->getFollowers($userId, $rankToken, null, $maxId);
        $i = 0;
        foreach ($followers->getUsers() as $user) {
            $userId = $user->getPk();
            $userName = $user->getUsername();
            $already = true;

            if (!isAlreadySent($conn, $userId, $userName, $account)) {
                $i++;
                follow($conn, $ig, $userId, $userName, $account);
                $already = false;
            }

            printf("[%s] [%s] [%b]", $userName, $userId, $already);
            echo PHP_EOL;
        }

        // Now we must update the maxId variable to the "next page".
        // This will be a null value again when we've reached the last page!
        // And we will stop looping through pages as soon as maxId becomes null.
        $maxId = $followers->getNextMaxId();

        // Sleep for 5 seconds before requesting the next page. This is just an
        // example of an okay sleep time. It is very important that your scripts
        // always pause between requests that may run very rapidly, otherwise
        // Instagram will throttle you temporarily for abusing their API!
        echo "Sleeping for 5s...\n";
        sleep(5);
    } while ($maxId !== null); // Must use "!==" for comparison instead of "!=".
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}