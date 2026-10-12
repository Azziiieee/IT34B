<?php
// Start User Session
function startUserSession($pdo){

    if(!isset($_SESSION['user_id'])){
        return false;
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
    INSERT INTO user_sessions(
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


?>











































function checkSessionTimeout(){
    global $pdo;

    // Timeout variable
    //10 seconds for testing 
    //1800s = 30 mins 
    $timeout = 1800;

    if(isset($_SESSION['last_activity'])){

    $inactive = time() - $_SESSION['last_activity'];

    if($inactive>=$timeout){
        logActivity(
            $pdo,
            $_SESSION['user_id'],
            $_SESSION['user_email'] ?? null,
            'logout-session-timeout',
            'success'
        );
    }

    // End database session 
    endUserSession($pdo);

    // Destroy php session
    session_unset();
    session_destroy();

    //Redirect 
    header(
        'Location: ',
        BASE_URL . 
        '/index.php?timeout=1'
    );
    
    }
}