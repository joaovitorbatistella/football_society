<?php

include_once '../services/UserStore.php';

$dao = new UserStore();

foreach ($dao->showUser() as $data) {
    $user[] = array('id' => $data->getId(), 'name' => $data->getName(), 'username' => $data->getUsername(), 'password' => $data->getPassword());
}

echo json_encode($user);
die;