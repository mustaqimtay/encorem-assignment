<?php 
/**
 * Create a "Random" String
 *
 * @param	string	type of random string.  basic, alpha, alnum, numeric, and nozero
 * @param	int	number of characters
 * @return	string
 */
function random_string($type = 'alnum', $len = 8)
{
    switch ($type)
    {
        case 'basic':
            return mt_rand();
        case 'alnum':
        case 'numeric':
        case 'nozero':
        case 'alpha':
            switch ($type)
            {
                case 'alpha':
                    $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum':
                    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric':
                    $pool = '0123456789';
                    break;
                case 'nozero':
                    $pool = '123456789';
                    break;
            }
            return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }
}



?>