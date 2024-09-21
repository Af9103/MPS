<?php
if (!function_exists('encrypt')) {
    function encrypt($data, $key, $iv)
    {
        $cipher = "aes-256-cbc";
        // IV harus sepanjang openssl_cipher_iv_length($cipher)
        $iv = substr($iv, 0, openssl_cipher_iv_length($cipher));
        $encrypted_data = openssl_encrypt($data, $cipher, $key, 0, $iv);
        // Encode IV dan data terenkripsi sebagai base64
        return base64_encode($encrypted_data . "::" . $iv);
    }
}

if (!function_exists('decrypt')) {
    function decrypt($data, $key)
    {
        $cipher = "aes-256-cbc";
        // Decode base64 untuk mendapatkan data dan IV
        list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
        // IV harus sepanjang openssl_cipher_iv_length($cipher)
        $iv = substr($iv, 0, openssl_cipher_iv_length($cipher));
        return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
    }
}
?>