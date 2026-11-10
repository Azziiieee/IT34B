<?php 
//Start User Session
function startUserSession($pdo)
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
        INSERT INTO user_sessions (
            user_id,
            session_start
        )
        VALUES (
            :user_id,
            NOW()
        )
    ");

     $stmt->execute([
        'user_id' => $user_id
    ]);

    return $pdo->lastInsertId();
}


// End User Session
function endUserSession($pdo)
{
    if (!isset($_SESSION['session_id'])) {
        return false;
    }

    $session_id = $_SESSION['session_id'];

    $stmt = $pdo->prepare("
        UPDATE user_sessions
        SET 
        session_end = NOW() ,
                session_duration = TIMESTAMPDIFF(SECOND, session_start, NOW())

        WHERE session_id = :session_id

     

    ");

    return $stmt->execute([
        'session_id' => $session_id
    ]);

     }

     //check if user is logged in

     function hasActiveSession($pdo, $user_id){

     $stmt = $pdo->prepare("
        SELECT session_id
        FROM user_sessions
        WHERE user_id = :user_id
        AND session_end IS NULL
        LIMIT 1

     ");


     $stmt->execute([
        'user_id' => $user_id
     ]);

     return(bool) $stmt->fetchColumn();

     }


 function checkSessionTimeout  () {
 global $pdo;

 //Time out variable
// 10 seconds for testing
// 1800s = 30mins
$timeout = 1800;


if (isset($_SESSION['last_activity'])){


$inactive_time = time() - $_SESSION['last_activity'];

if ($inactive_time > $timeout) {
   
    logActivity(
        $pdo,
        $_SESSION['user_id'],
        $_SESSION['user_email'] ?? null,
        'Log out session_timeout',
        'success'
        );




// End database session
endUserSession($pdo);



//Destroy php session
session_unset();
session_destroy();

//redirect 

header('Location:' . BASE_URL . '/index.php?timeout=1');
exit;


 }



      };

// update last activity 

$_SESSION['last_activity'] = time();





    // Session has timed out


  }
     ?>