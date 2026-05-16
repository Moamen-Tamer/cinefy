<?php

require_once __DIR__ . '/../app/helpers/functions.php';

session_unset();
session_destroy();
session_start();

set_note('success', 'You have been logged out.');
redirect('index.php');
