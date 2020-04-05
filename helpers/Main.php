<?php 

namespace App\Helpers;

/**
 * summary
 */
class Main
{
    public $url = null;
    public $query = null;

    public function __construct() 
    {
        $this->url = parse_url($_SERVER['REQUEST_URI']);
        $this->query = !empty($this->url['query'])? explode('&', $this->url['query']): null;
    }

    /**
     * метод добавляет или изменяет параметры ссылки
     * @param array $query
     * @param string $param
     * @param string $preg
     * @return array
     */
    private function paramReplace($query, $param, $preg)
    {
        foreach ($query as $key => $value) {
            if (preg_match($preg, $value) and preg_match($preg, $param))
                $query[$key] = $param;
        }
        
        array_push($query, $param);
        return array_unique($query);
    }

    /**
     * Метод возвращает ссылку с параметрами для пагинатора
     * @param string $param
     * @return strung
     */
    public static function filterURL($param = null)
    {   
        $main = new self;

        if (!empty($main->query[0])) {
            $query = $main->paramReplace($main->query, $param, '/(page=[0-9]+)/m');
            return $main->url['path'] .'?' .implode('&', $query);
        }
        
        return $main->url['path'] .'?' .$param;
    }

    /**
     * Метод возвращает ссылку с параметрами для сортировки
     * @param string $replice
     * @return strung
     */
    public static function sortChange($replice = null)
    {
        if (!is_null($replice)) {
            $main = new self;
            
            if (!empty($main->query)) {
                $query = implode('&', $main->query);
                $re = "/sort=([a-z_]+)\.([asc]{3}|[desc]{4})/m";
                preg_match($re, $query, $matches, PREG_OFFSET_CAPTURE, 0);
            }
                
            if (!empty($matches[1][0])) {
                $col = $matches[1][0];

                if ($matches[2][0] == 'asc' and $col == $replice) {
                    $query = str_replace("sort=$col.asc", "sort=$replice.desc", $query);               
                } elseif ($matches[2][0] == 'desc' and $col == $replice) {
                    $query = str_replace("sort=$col.desc", "sort=$replice.asc", $query);
                } else {
                    $query = str_replace($matches[0][0], "sort=$replice.asc", $query);               
                }
            } else {
                $main->query[] = "sort=$replice.asc";
                return $main->url['path'] .'?' .implode('&', array_unique($main->query));
            }

            return  $main->url['path'] .'?' .$query;
        }
    }

    /**
     * @return type
     */
    public static function rootDir()
    {
        return dirname(__FILE__, 2);
    }

    public static function viewDir()
    {
        return self::rootDir() .DS.'views'.DS;
    }

    /**
     * @param string $msg
     * @param string $msg
     * @return string
     */
    public static function exception($msg = false, $code = false)
    {
        header("HTTP/1.0 $code $msg");
        echo $msg;
        exit();
    }
}
