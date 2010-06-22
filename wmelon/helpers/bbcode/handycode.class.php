<?php
class handyCode
{
    public $AllowedTags = null;
    public $AllowedParametrs = Array(); // globalnie dozwolone parametry
    public $blockChild = Array(); // tablica znacznikow w których znaczniki-dzieci nie będą parsowane
    protected $parents = Array(); // tablica rodzicow
    private $rainbow = null; // obiekty klasy rainbowCompiler
    private $counter; // licznik wydzielonych czesci do kolorowania za pomocą klasy Rainbow
    private $cacheDir = 'cache/handycode/';
	private $parsedHTML = 0;

    public function __construct( $tags=null, $parametrs = null, $block = null )
    {
        // lista znacznikow wewnątrz ktorych żaden inny nie jest juz parsowany np. [code] [b] pogrubienie [/b] [/code]
        // [b] pogrubienie [/b] sie nie pogrubi poniewaz code jest na liscie znacznikow "block"

		// jesli nie przekazane przez parametr zaladuj domyslna konfiguracje
        $this -> blockChild = ( $block == null ) ? Array('handycode' => 1, 'code' => 1,'php' => 1, 'css' => 1, 'html' => 1, 'sql' => 1) : $block;

        // Dopuszczalne, uniwersjalne parametry które będą aktywne w każdym znaczniku
		// jesli nie przekazane przez parametr zaladuj domyslna konfiguracje
         //$this -> AllowedParametrs = ( $parametrs == null ) ? Array('id', 'class', 'color', 'size', 'position') : $parametrs;

        // do konstruktora zostala przekazana lista aktywnych znacznikow BBcode
        if( $tags != null )
        {
            if( is_array($tags) ) // w postaci tablicy
                $this -> AllowedTags = implode( '|', $tags );
            elseif( is_string($tags) ) // w gotowej postaci
                $this -> AllowedTags = $tags;
        }
        else{
        //standardowa konfiguracja
            $this -> AllowedTags = 'b|u|i|color|size|quote|img|position|url|handycode|code|php|css|html|sql|list|li';
        }
    }

    // funkcja odpowiedzialna za wywolanie odpowienich metod z klasy Rainbow kolorującej kod
    public function rainbow( $data )
    {
        $mode = $data[1]; // zmienna pomocnicza, modul jaki zostal wybrany(nazwa znacznika np. css, html etc. )

        if(!$this -> rainbow)
        {
            include_once('rainbow_compiler.class.php');
            $this -> rainbow = new rainbowCompiler($mode);
            $this -> counter=0;
        }
        else $this -> counter++;

    /***************** Kod funkcji rainbow ******************************/
    /*  warto go przepisac aby uniknac niepotrzebnego includowania dodatkowego pliku */
    //  $data[2] - parametry
    //  $data[3] - kod do przeparsowania

        // wycinanie początkowego entera
        if( strpos($data[3],"\n")===0 )  $data[3] = substr($data[3],1);

        // wycinanie końcowego entera
        if( strrpos($data[3],"\n")===strlen($data[3])-1)  $data[3] = substr($data[3],0,-1);

        // tutaj bedziemy mieli tablice parametrow w postaci: Array(nazwa => wartosc, nazwa2 => wartosc2)
        $specialParams = Array();
        // są jakies parametry
        if( $data[2] )
        {
            if( $data[2][0] == ' ') $data[2] = substr($data[2],1);
            $params = explode(' ',$data[2]);
            for( $i=0, $n=count($params); $i<$n; $i++)
            {
                $bracket = strpos($params[$i],'=');
                $paramName = substr($params[$i],0,$bracket);
                $paramValue = str_replace('"','',substr($params[$i],1+$bracket));
                // wiem wiem mozna to przyspieszyc usuwajac te 2 zbedne zmienne ale tak jest wiekszy porzadek
                $specialParams[$paramName] = $paramValue;
            }
            // przekazujemy parametry do obiektu klasy rainbow
            $this -> rainbow -> setParams($specialParams);
        }
        else $this -> rainbow -> resetParams();

        // sql - znacznik specjalny który nie wymaga przedparsowania za pomocą highlight_string();
        if( $mode=='sql' )
            $this -> mycode = $data[3];
        else
        {
            /* jesli kod php nie posiada znakow <?php ?> to musimy je tymczasowo dodac aby kod sie pokolorowal */
            if( $mode == 'php' && strpos($data[3],'<?php')===false )
            {
                $data[3] = '<?php'.$data[3].'?>';
                $deletePhpTags = true;
            }

            /* przygotowanie kodu do obrobki
               funkcja highlight_string() świetnie konwertuje zmienną $code wyręczając kilka wyrażen regularnych
               dodatkowo kolorując kod php */
            ob_start();
            highlight_string($data[3]);
            $this -> mycode = ob_get_clean();

            // usuwanie przejsc do nastepnej lini
            $this -> mycode = str_replace("\n",'', $this -> mycode);

            /*
                zamiana wszyskich \" na kod ktory nie bedzie parsowany jako cudzyslow htmlowy
                oraz protekcja cudzyslowow w przypadku gdy na backslashu konczy sie parametr html
                np. a href="c:\Program Files\"
            */
            $this -> mycode = preg_replace('@(="[^\"]*)\\\"(&nbsp;|&gt;)@','\\1&#92;"\\2', $this -> mycode);
            $this -> mycode = str_replace("\\\"",'\\&quot;', $this -> mycode);
        }

        // sprawdzanie czy dany tryb jest poprawnie wybrany, jesli nie zmiana trybu na "all"
        if($mode == 'code' || ($mode == 'html' && (strpos($this -> mycode,'style')!==false) || strpos($this -> mycode,'script')!==false)) $mode = 'all';

        // usuwamy zbedny kod generowany przez highlight_string()
        $this -> mycode = str_replace('<code><span style="color: #000000">','',$this -> mycode);
        $this -> mycode = str_replace('</span></code>','',$this -> mycode);

        $mode = 'hl_'.$mode;

        $readyCode = $this -> rainbow -> display($this -> rainbow -> $mode($this -> mycode));
        /* jesli wczesniej dodalismy to teraz usuwamy <?php ?> */
        if( isset($deletePhpTags) )
        {
            $readyCode = str_replace('&lt;?php','',$readyCode);
            $readyCode = str_replace('?&gt;','',$readyCode);
        }
    /******************************************************/
        $this -> codes[$this -> counter] = $readyCode;

        return '[!--CODE-'.$this -> counter.'--]';
    }

    public function getCache( $id )
    {
        $hash = md5($id.'/handyCode/');
        if(file_exists($this -> cacheDir.$hash.'.cache'))
            return file_get_contents($this -> cacheDir.$hash.'.cache');
        else return false;
    }

    public function doCache( $id, $code )
    {
        $hash = md5($id.'/handyCode/');

        if(@file_put_contents($this -> cacheDir.$hash.'.cache',$code))
            return true;
        else echo 'handyCode error in doCache(); - file_put_contents('.$this -> cacheDir.$hash.'.cache)';
    }

    public function deleteCache( $id )
    {
        $hash = md5($id.'/handyCode/');

        if( file_exists($this -> cacheDir.$hash.'.cache') )
            unlink($this -> cacheDir.$hash.'.cache');
        else return false;
    }

    public function getCode( $id )
    {
        if(isset($this -> codes[$id[1]])) return $this -> codes[$id[1]];
        else return '';
    }

    private function parseTag( $data, $parent='' )
    {
        /* $data Array
        [0] - kod ze znacznikami np "[b id="moj"] kod [/b]"
        [1] - nazwa znacznika np. "b"
        [2] - parametry np. " id="moj""
        [3] - wznętrze znacznika np. " kod "
        */

        // sprawdzamy blokowanie znacznikow potomnych
        if( !isset($this -> blockChild[$data[1]]) || !$this -> blockChild[$data[1]] )
        {
            // dodajemy znacznik do listy rodziców
            $this -> parents[] = $data[1];

            // parsujemy znaczniki potomne
            $data[3] = preg_replace_callback('# \[('.$this -> AllowedTags.')(\s=?.+?|=.+?)?\] ((?: (?(R) [^\[]++  | [^\[]*+) | (?R)) *) \[/\\1\]#x',Array($this, 'parseTag'), $data[3]);

            // usuwamy znacznik z listy rodzicow
            array_pop($this -> parents);
        }

        $mainparam='';
        $addParams='';
        $styles='';
        $specialParams = Array();
        if( !empty($data[2]) ) // parsowanie parametrow
        {
            $params = explode(' ',$data[2]);
            for( $i=0,$n=count($params); $i<$n; $i++ )
            {
                if( !empty($params[$i]) )
                {
                    // parametr główny
                    if( $params[$i][0]=='=' )
                    {
                        $mainparam=str_replace('"','',substr($params[$i],1));
                    }
                    else
                    {
                        $bracket = strpos($params[$i],'=');
                        $paramName = substr($params[$i],0,$bracket);
                        $paramValue = str_replace('"','',substr($params[$i],1+$bracket));
                        if( in_array($paramName,$this -> AllowedParametrs) )
                        {
                            /* PARAMETRY */
                            switch( $paramName )
                            {
                                case 'color':
                                $styles .= 'color:'.$paramValue.';';
                                break;
                                case 'size':
                                $styles .= 'font-size:'.$paramValue.'px;';
                                break;
                                case 'position':
                                $styles .= 'float:'.$paramValue.'; text-align:'.$paramValue.';';
                                break;
                                default:
                                // inne globalnie dopuszczalne parametry
                                $addParams .= ' '.$paramName.'="'.str_replace('"','',$paramValue).'"';
                            }
                        }
                        else $specialParams[$paramName] = $paramValue;
                    }
                }
            }
//             if( $styles ) $addParams.= ' style="'.$styles.'"';
        }

        // jesli istnieje funkcja uzytkownika do obslugi danego znacznika
        $func = 'tag_'.$data[1];
        if( is_callable(array($this, $func)) )
        {
            $return = $this -> $func($data[3], $mainparam, $addParams, $styles, $specialParams);
        }
        else
        {
            switch( $data[1] )
            {
                case 'php': case 'html': case 'css': case 'code':
                case 'quote':
                    $return = '<div class="quote">'. (empty($mainparam) ? '' : '<strong>' . $mainparam . ' napisał:</strong>') .$data[3].'</div>';
                    break;
                case 'color':
                    $return = '<span style="color:'.$mainparam.';">'.$data[3].'</span>';
                    break;
                case 'size':
                    $return = '<span style="font-size:'.$mainparam.'px;">'.$data[3].'</span>';
                    break;
                case 'img':
                    $return = '<img src="'.$data[3].'" alt="" '.$addParams.(($styles) ? ' style="'.$styles.'"' : '').'/>';
                    break;
                case 'position':
                    if($mainparam!='center' && $mainparam!='right') $mainparam='left';
                    $return = '<div style="text-align:'.$mainparam.';">'.$data[3].'</div>';
                    break;
                case 'url':
                    if($mainparam)
                        $return = '<a href="'.$mainparam.'"'.$addParams.(($styles) ? ' style="'.$styles.'"' : '').'>'.$data[3].'</a>';
                    else
                        $return = '<a href="'.$data[3].'"'.$addParams.(($styles) ? ' style="'.$styles.'"' : '').'>'.$data[3].'</a>';
                    break;
                case 'b':
                case 'i':
                    $return = '<'.$data[1].$addParams.(($styles) ? ' style="'.$styles.'"' : '').'>'.$data[3].'</'.$data[1].'>';
                    break;
                case 'u':
                    $return = '<span'.$addParams.' style="text-decoration:underline; '.$styles.'">'.$data[3].'</span>';
                    break;
                case 'list':
                    // jesli istnieje chodz jeden element listy
                    if(strpos($data[3],'<li')!==false)
                    {
                        // wyrzucamy wszystko co znajduje sie pomiędzy <ul> a <li>, </li> a <li> lub </li> a </ul>
                        // dzięki temu uzyskamy w pełni semantyczny kod html ;)
                        $string = '';
                        // wyrażenie od którego wszystko się zaczęlo ;) Dzięki FiDO!
                        preg_match_all('#\<li[^<>]*?> ( (?: (?R) | (?:.*?(?!\\\</li>)) )* ) \\</li>#x', $data[3], $lis, PREG_SET_ORDER);

                        for($i=0; isset($lis[$i][0]); $i++)
                        {
                            $string .= $lis[$i][0];
                        }
                        $return = '<ul'.$addParams.(($styles) ? ' style="'.$styles.'"' : '').'>'.$string.'</ul>';
                    }
                    else $return = $data[0];
                    break;
                case 'li':
                    if(end($this -> parents)=='list')
                        $return = '<li'.$addParams.(($styles) ? ' style="'.$styles.'"' : '').'>'.$data[3].'</li>';
                    else
                        $return = $data[0];
                    break;
                case 'handycode':
                    $return = $data[3];
                    break;
                default:
                    $return = $data[0];
                    break;
            }
        }
        return $return;
    }

    public function parse( $code, $id=null, $cache=1, $rainbow=1, $parsedHTML = 0 )
    {
		$this -> parsedHTML = $parsedHTML;

        $code = stripslashes($code);

        if( $cache && $id && $cachecode = $this -> getCache($id) )
            return $cachecode;

        // parsoweanie kodu php/html/css/sql/js
        if( $rainbow )
        {
            // wydzielenie i przeparsowanie kodów źródłowych
            // (musze to przemyslec dokladniej bo puki co wyrazenie niewystarcza dla wszystkich wypadkow)

            $code = preg_replace_callback("#\[(code|php|css|html|sql|js)(\s=?.+?|=.+?)?]( (?: (?R) | (?:.*?(?!\\\[/\\1])) )* )\[/\\1]#six",Array($this, 'rainbow'), $code);
        }

        // parsowanie kodu html
		if(!$parsedHTML)
		{
			$code = str_replace("<","&lt;",$code);
			$code = str_replace(">","&gt;",$code);

			$code = str_replace("\n","<br>",$code);
		}


        $code = str_replace(Array('\[','[ ','\]'),Array('&#91;','&#91; ','&#93;'),$code);

        $code = preg_replace('#\[((?!/?(?:'.$this -> AllowedTags.'))[^\]]*?)\]#', '&#91;\\1&#93;', $code);

        // odnajdowanie linkow
        $code = preg_replace('@(^| |,|\(|\))((?:(?:file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)://)+(?:(?:[a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|(?:[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})|localhost)(?:/[a-zA-Z0-9\&amp;%,_\./-~-]*)?(?:#[a-zA-Z0-9\&amp;%,_\./-~-]+)?)@','\\1<a href="\\2">\\2</a>', $code);

        // protekcja adresow e-mail (w przyszlosci bedzie tutaj callback do funkcji generującej obrazek)
        $code = preg_replace('#([a-zA-Z0-9._%-]+)@((?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4})#', '\\1 (at) \\2', $code);

        // przez tą linijkę nie mogłem spać przez tydzien =)
        // odnajdywanie znacznikow i przekazywanie ich do oddzielnej funkcji ( tutaj jest prawie idealnie :) )
        $code = preg_replace_callback('# \[('.$this -> AllowedTags.')(\s=?.+?|=.+?)?\] ( (?: (?(R) [^\[]++ | [^\[]*+) | (?R)) *) \[/\\1\] #x',Array($this, 'parseTag'), $code);


        // parsoweanie kodu php/html/css etc.
        if( $rainbow )
            $code = preg_replace_callback('$&#91;!--CODE-(\d+)--&#93;$is',Array($this, 'getCode'),$code);

        // tworzenie cachu
        if( $cache && $id ) $this -> doCache($id,$code);

        return $code;
    }
}
?>