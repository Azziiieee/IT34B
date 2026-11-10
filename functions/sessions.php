<?php

function startUserSession($pdo)
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    }


    $user_id = $_SESSION['user_id'];


    $stmt = $pdo->prepare(
        "
        INSERT INTO user_sessions (
        user_id,
        session_start
        )

        VALUES(
            :user_id,
            NOW()
        )
        "
    
    );

    $stmt->execute([
        'user_id' => $user_id
    ]);

    $_SESSION['sessions_id'] = $pdo->lastInsertId();

    return $_SESSION['sessions_id'];
}

// End User session


function endUserSession($pdo){
    if(!isset($_SESSION['sessions_id'])){
        return false;
    }

    $session_id = $_SESSION['sessions_id'];

    $stmt = $pdo->prepare("
        UPDATE user_sessions
        SET
            session_end = NOW(),
            session_duration = TIMESTAMPDIFF(
                SECOND,
                session_start,
                NOW()
            )
        WHERE sessions_id = :sessions_id
    ");
    return $stmt -> execute ([
        'sessions_id'=> $session_id
    ]);
}

// Check if user is already logged in
function hasActiveUserSession($pdo,$user_id){
    $stmt= $pdo->prepare("
        SELECT sessions_id
        FROM user_sessions
        WHERE user_id = :user_id
        AND session_end IS NULL
        LIMIT 1
    ");

    $stmt->execute([
        'user_id' => $user_id
    ]);

    return (bool) $stmt->fetchColumn();
}


function checkSessionTimeout  () {
global $pdo;

 //Time out variable
// 10 seconds for testing
// 1800s = 30mins
$timeout = 1000;


if (isset($_SESSION['last_activity'])){


$inactive_time = time() - $_SESSION['last_activity'];

if ($inactive_time > $timeout) {
   
    logActivity(
        $pdo,$_SESSION['user_id'],
        $_SESSION['user_email'] ?? null,
        'session_timeout',
        'success'
        );

}  


// End database session
endUserSession($pdo);



//Destroy php session
session_unset();
session_destroy();

//Redirect 

header('Location:' . BASE_URL . '/index.php?timeout=1');
exit;




      };

$_SESSION['last_activity'] = time();

}


?>