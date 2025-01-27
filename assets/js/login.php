<?php
session_start();

require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;

$serviceAccountPath = __DIR__. '/login-it-684fe-firebase-adminsdk-8w1px-149a72aa30.json';
$factory = (new Factory)
    ->withServiceAccount($serviceAccountPath)
    ->withDatabaseUri('https://it-login-32690-default-rtdb.asia-southeast1.firebasedatabase.app/');

$auth = $factory->createAuth();
$database = $factory->createDatabase();

if (isset($_POST['idTokenString'])) {
    $idTokenString = $_POST['idTokenString'];

    $verifiedIdToken = $auth->verifyIdToken($idTokenString);
    $uid = $verifiedIdToken->claims()->get('sub');

    $user = $auth->getUser($uid);

    //ตรวจสอบว่ามีข้อมูลอยู่ในฐานข้อมูลหรือยัง
    $userRef = $database->getReference('users/' . $uid);
    $userSnapshot = $userRef->getSnapshot();

    if(!$userSnapshot->exists()){
        $userRef->set([
            'name' => $user->displayName,
            'email' => $user->email,
            'photo' => $user->photoUrl,
            'role' => 'users'
        ]);
    }

    $_SESSION['user'] = [
        'uid' => $uid,
        'name' => $user->displayName,
        'email' => $user->email,
        'photo' => $user->photoUrl,
        'role' => $userSnapshot->getChild('role')->getValue() ?? 'users',
    ];

    header('Location: /php-login/index.php');
    exit();
}
?>