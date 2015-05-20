<?php
namespace League\Flysystem;

/**
 * Format helper class
 *
 * @package   Uploader
 * @subpackage   Uploader\Helpers
 * @since     PHP >=5.4
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Format
{
    /**
     * Cyrillic symbols
     *
     * @var array
     */
    static private $cyr = array(
        'Щ', 'Ш', 'Ч', 'Ц', 'Ю', 'Я', 'Ж', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О',
        'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ь', 'Ы', 'Ъ', 'Э', 'Є', 'Ї', 'І',
        'щ', 'ш', 'ч', 'ц', 'ю', 'я', 'ж', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о',
        'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ь', 'ы', 'ъ', 'э', 'є', 'ї', 'і');

    /**
     * Latin symbols
     *
     * @var array
     */
    static private $lat = array(
        'Shh', 'Sh', 'Ch', 'C', 'Ju', 'Ja', 'Zh', 'A', 'B', 'V', 'G', 'D', 'Je', 'Jo', 'Z', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Kh', 'Y', 'Y', '', 'E', 'Je', 'Ji', 'I',
        'shh', 'sh', 'ch', 'c', 'ju', 'ja', 'zh', 'a', 'b', 'v', 'g', 'd', 'je', 'jo', 'z', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'y', 'y', '', 'e', 'je', 'ji', 'i');

    static private $search = array(
        '&#225;', '&#224;', '&#7843;', '&#227;', '&#7841;',                 // a' a` a? a~ a.
        '&#259;', '&#7855;', '&#7857;', '&#7859;', '&#7861;', '&#7863;',    // a( a('
        '&#226;', '&#7845;', '&#7847;', '&#7849;', '&#7851;', '&#7853;',    // a^ a^'..
        '&#273;',                                                       // d-
        '&#233;', '&#232;', '&#7867;', '&#7869;', '&#7865;',                // e' e`..
        '&#234;', '&#7871;', '&#7873;', '&#7875;', '&#7877;', '&#7879;',    // e^ e^'
        '&#237;', '&#236;', '&#7881;', '&#297;', '&#7883;',                 // i' i`..
        '&#243;', '&#242;', '&#7887;', '&#245;', '&#7885;',                 // o' o`..
        '&#244;', '&#7889;', '&#7891;', '&#7893;', '&#7895;', '&#7897;',    // o^ o^'..
        '&#417;', '&#7899;', '&#7901;', '&#7903;', '&#7905;', '&#7907;',    // o* o*'..
        '&#250;', '&#249;', '&#7911;', '&#361;', '&#7909;',                 // u'..
        '&#432;', '&#7913;', '&#7915;', '&#7917;', '&#7919;', '&#7921;',    // u* u*'..
        '&#253;', '&#7923;', '&#7927;', '&#7929;', '&#7925;',               // y' y`..

        '&#193;', '&#192;', '&#7842;', '&#195;', '&#7840;',                 // A' A` A? A~ A.
        '&#258;', '&#7854;', '&#7856;', '&#7858;', '&#7860;', '&#7862;',    // A( A('..
        '&#194;', '&#7844;', '&#7846;', '&#7848;', '&#7850;', '&#7852;',    // A^ A^'..
        '&#272;',                                                           // D-
        '&#201;', '&#200;', '&#7866;', '&#7868;', '&#7864;',                // E' E`..
        '&#202;', '&#7870;', '&#7872;', '&#7874;', '&#7876;', '&#7878;',    // E^ E^'..
        '&#205;', '&#204;', '&#7880;', '&#296;', '&#7882;',                 // I' I`..
        '&#211;', '&#210;', '&#7886;', '&#213;', '&#7884;',                 // O' O`..
        '&#212;', '&#7888;', '&#7890;', '&#7892;', '&#7894;', '&#7896;',    // O^ O^'..
        '&#416;', '&#7898;', '&#7900;', '&#7902;', '&#7904;', '&#7906;',    // O* O*'..
        '&#218;', '&#217;', '&#7910;', '&#360;', '&#7908;',                 // U' U`..
        '&#431;', '&#7912;', '&#7914;', '&#7916;', '&#7918;', '&#7920;',    // U* U*'..
        '&#221;', '&#7922;', '&#7926;', '&#7928;', '&#7924;'                // Y' Y`..
    );

    static private $search2 = array(
        'á', 'à', 'ả', 'ã', 'ạ',                // a' a` a? a~ a.
        'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ',   // a( a('
        'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ',   // a^ a^'..
        'đ',                                                        // d-
        'é', 'è', 'ẻ', 'ẽ', 'ẹ',                // e' e`..
        'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',   // e^ e^'
        'í', 'ì', 'ỉ', 'ĩ', 'ị',                    // i' i`..
        'ó', 'ò', 'ỏ', 'õ', 'ọ',                    // o' o`..
        'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ',   // o^ o^'..
        'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',   // o* o*'..
        'ú', 'ù', 'ủ', 'ũ', 'ụ',                    // u'..
        'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',   // u* u*'..
        'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ',                // y' y`..

        'Á', 'À', 'Ả', 'Ã', 'Ạ',                    // A' A` A? A~ A.
        'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ',   // A( A('..
        'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',   // A^ A^'..
        'Đ',                                                            // D-
        'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ',                // E' E`..
        'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',   // E^ E^'..
        'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',                    // I' I`..
        'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ',                    // O' O`..
        'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ',   // O^ O^'..
        'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',   // O* O*'..
        'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ',                    // U' U`..
        'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',   // U* U*'..
        'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'             // Y' Y`..
    );

    static private $replace = array(
        'a', 'a', 'a', 'a', 'a',
        'a', 'a', 'a', 'a', 'a', 'a',
        'a', 'a', 'a', 'a', 'a', 'a',
        'd',
        'e', 'e', 'e', 'e', 'e',
        'e', 'e', 'e', 'e', 'e', 'e',
        'i', 'i', 'i', 'i', 'i',
        'o', 'o', 'o', 'o', 'o',
        'o', 'o', 'o', 'o', 'o', 'o',
        'o', 'o', 'o', 'o', 'o', 'o',
        'u', 'u', 'u', 'u', 'u',
        'u', 'u', 'u', 'u', 'u', 'u',
        'y', 'y', 'y', 'y', 'y',

        'A', 'A', 'A', 'A', 'A',
        'A', 'A', 'A', 'A', 'A', 'A',
        'A', 'A', 'A', 'A', 'A', 'A',
        'D',
        'E', 'E', 'E', 'E', 'E',
        'E', 'E', 'E', 'E', 'E', 'E',
        'I', 'I', 'I', 'I', 'I',
        'O', 'O', 'O', 'O', 'O',
        'O', 'O', 'O', 'O', 'O', 'O',
        'O', 'O', 'O', 'O', 'O', 'O',
        'U', 'U', 'U', 'U', 'U',
        'U', 'U', 'U', 'U', 'U', 'U',
        'Y', 'Y', 'Y', 'Y', 'Y'
    );

    /**
     * Format byte code to human understand
     *
     * @param int $bytes number of bytes
     * @param int $precision after comma numbers
     * @return string
     */
    public static function bytes($bytes, $precision = 2)
    {
        $size = array('bytes', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    /**
     * Transliterate cyrillic to latin
     *
     * @param string $string original string
     * @param string $separator word separator
     * @param boolean $clean to lower & all non understand symbols remove
     * @return
     */
    static public function toLatin($string, $separator = '', $clean = false)
    {
        for($i = 0; $i<count(self::$cyr); $i++)
        {
            $string = str_replace(self::$cyr[$i], self::$lat[$i], $string);
        }

        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $string);
        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}y", $string);
        $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
        $string = preg_replace("/^kh/", "h", $string);
        $string = preg_replace("/^Kh/", "H", $string);

        $string = trim($string);

        if(empty($separator) === false) {

            $string = str_replace(' ', $separator, $string);
            $string = preg_replace('/['.$separator.']{2,}/', '', $string);
        }

        if($clean !== false) {

            $string = strtolower($string);
            $string = preg_replace('/[^-_a-z0-9.]+/', '', $string);
        }

        return $string;
    }

    static public function toLatinVN($string = '', $alphabetOnly = false, $tolower = true)
    {
        $output =  $string;
        if ($output != '') {
            $output = str_replace(self::$search, self::$replace, $output);
            $output = str_replace(self::$search2, self::$replace, $output);

            if ($alphabetOnly) {
                $output = self::alphabetonly($output);
            }

            if ($tolower) {
                $output = strtolower($output);
            }
        }

        return $output;
    }

    public static function alphabetonly($string = '')
    {
        $output = $string;
        //replace no alphabet character
        $output = preg_replace("/[^a-zA-Z0-9]/", "-", $output);
        $output = preg_replace("/-+/", "-", $output);
        $output = trim($output, '-');

        return $output;
    }

}