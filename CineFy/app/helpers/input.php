<?php

function sanitize(string $value): string {
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function raw_value(string $value): string {
    return trim($value);
}