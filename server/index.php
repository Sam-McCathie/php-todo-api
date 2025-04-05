<?php
    $user = getEnv("MYSQL_USER");

    echo json_encode(["message" => "Laoded env vars",
    "MYSQL_USER" => $user
]);
?>