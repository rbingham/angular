<?php
function createRandomPassword($length = 8){
                        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
                        $password = "";
                        for ($i = 0; $i < $length; $i++) {
                                $x = mt_rand(0, strlen($chars) - 1);
                                $password .= $chars[$x];
                        }
                        return $password;
                }

echo createRandomPassword() . "\n";
?>
