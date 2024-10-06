<?php

require_once 'session.php';
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 !@#$%^&*()_+=-`~\\|;:'\",.<>?/";

function vigenereEncrypt($text, $key) {
    global $CHARACTERS;
    $result = "";
    $textLength = strlen($text);
    $keyLength = strlen($key);
    
    for ($i = 0, $j = 0; $i < $textLength; $i++) {
        $c = $text[$i];
        $index = strpos($CHARACTERS, $c);
        if ($index === false) {
            $result .= $c;
        } else {
            $keyIndex = strpos($CHARACTERS, $key[$j]);
            $encryptedIndex = ($index + $keyIndex) % strlen($CHARACTERS);
            $result .= $CHARACTERS[$encryptedIndex];
            $j = ++$j % $keyLength;
        }
    }
    return $result;
}

function vigenereDecrypt($text, $key) {
    global $CHARACTERS;
    $result = "";
    $textLength = strlen($text);
    $keyLength = strlen($key);
    
    for ($i = 0, $j = 0; $i < $textLength; $i++) {
        $c = $text[$i];
        $index = strpos($CHARACTERS, $c);
        if ($index === false) {
            $result .= $c;
        } else {
            $keyIndex = strpos($CHARACTERS, $key[$j]);
            $decryptedIndex = ($index - $keyIndex + strlen($CHARACTERS)) % strlen($CHARACTERS);
            $result .= $CHARACTERS[$decryptedIndex];
            $j = ++$j % $keyLength;
        }
    }
    return $result;
}
?>