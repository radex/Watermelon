<?php
// stale pobierane z pliku konfiguracyjnego php (php.ini)
define('PHP_COMMENT', ini_get('highlight.comment'));
define('PHP_DEFAULT', ini_get('highlight.default'));
define('PHP_KEYWORD', ini_get('highlight.keyword'));
define('PHP_STRING',  ini_get('highlight.string'));
define('PHP_HTML',  ini_get('highlight.html'));
define('HTML_STRICT',1);

class rainbowCompiler
{
    private $lineOffset = 1; // przechowuje numer pierwszej lini

    private $mode = ''; // modul parsowania
    private $mycode = ''; // kod źródlowy

    function __construct($mode='all')
    {
        $this -> mode = $mode;
    }

    function hl_sql($sql='')
    {
        // zamiana na encje(wstpene parsowanie ktore w inych przypadkach odbywa sie za pomocą highlight_string())
        $sql = str_replace(Array(' ','<','>',"\n"), Array('&nbsp;','&lt;','&gt;','<br />'), $sql);

        /*  Parsowanie kolejno
            1. Stringow
            2. Polecen glownych sql
            3. slow kluczowych
            4. wyrazen logicznych
            5. funkcji
            6. liczb i znaku *
            7. nawiasow
        */
        $sql = preg_replace(Array(
        '#("|\')(.+?)\\1#',
        "#(;|&nbsp;|^|\)|<br />|\()(USE|DESCRIBE|SHOW(?:&nbsp;TABLES|&nbsp;DATABASES)?|SELECT|ALTER(?:&nbsp;TABLE)?|CREATE(?:&nbsp;DATABASE|&nbsp;TABLE)?|DROP(?!&nbsp;COLUMN)(?:&nbsp;DATABASE|&nbsp;TABLE)?|DELETE|INSERT|UPDATE)(&nbsp|;|\(|$|\n)#i",
        "#(;|&nbsp;|^|\)|<br />)(DISTINCT|LIKE|ORDER|SET|BY|WHERE|FROM|IS|USE|VALUES|DESC|LIMIT|ADD|RENAME|DROP&nbsp;COLUMN|CHANGE|MODIFY)(&nbsp|;|\(|\)|$|\n)#i",
        "#(;|&nbsp;|^|\)|<br />)(OR(?!DER)|AND|NOT|TRUE|FALSE|NULL)(&nbsp|;|\(|\)|$|\n)#i",
        "#(;|&nbsp;|^|\)|<br />)(MAX|MIN|COUNT|SUM|AVG|IN|BETWEEN)(&nbsp|;|\(|$|\n)#i",
        '#((?:\d.*?)|\*)#',
        '#(\(|\))#'),
        Array('<span class="sqlstring">\\1\\2\\1</span>',
        '\\1<span class="sqlcommand">\\2</span>\\3',
        '\\1<span class="sqlkeyword">\\2</span>\\3',
        '\\1<span class="sqllogic">\\2</span>\\3',
        '\\1<span class="sqlfunction">\\2</span>\\3',
        '<span class="sqlnumber">\\1</span>',
        '<span class="sqlbracket">\\1</span>'), $sql);

        return $sql;
    }


    /* || jsCode || */
    function hl_js($mycode='')
    {
        if(!$mycode) $mycode = $this -> mycode;

        // zamiana znakow na podstawie których parser html wydziela kolejne znacnziki html ( &lt;)
        $mycode = str_replace('&lt;', '&#60;', $mycode);

        /* Kolejno:
         - kolorowanie stringów
         - kolorowanie parametrow
         - istniejące lub zarezerwowane dla przyszłych wersji słowa kluczowe JavaScript
         - kolorowanie komentarzy wielolinijkowych
         - kolorowanie komentarzy jednolinijkowych
        */

        $mycode = preg_replace(Array(
        '#(\&quot;|"|\')(.+?)\\1#',
        '#\.([a-zA-z0-9-_.]*)#',
        "#(;|&nbsp;|^|\)|<br />|\()(abstract|boolean|break|byte|case|catch|char|class|conts|continue|default|do(?:uble)?|else|extends|false|final|finally|float|for|function|goto|if|implements|import|int?|instanceof|inferface|long|native|new|null|package|private|protected|public|return|short|static|super|switch|synchronized|this|throws?|transient|true|try|var|void|while|with)(&nbsp|;|\(|$|\n)#i",
        '#\/\*(.+?)\*\/#',
        '#\/\/(.+?)<br />#'),
        Array(
        '<span class="jsstring">\\1\\2\\1</span>',
        '<span class="jsparam">.\\1</span>\\2',
        '\\1<span class="jskeyword">\\2</span>\\3',
        '<span class="jscomment">/*\\1*/</span>',
        '<span class="jscomment">//\\1</span><br />',
        ), $mycode);

        /*
         konwersja znaku < na encje hexadecymalna, dzieki czemu
         parser html nie wykryje tego jako rozpoczecie nowego znacznika html
        */
        $mycode = str_replace('&lt;', '&#60;', $mycode);

        return '<span class="jscode">'.$mycode.'</span>';
    }

    // funkcja zwracająca kod JavaScript (<script>kod</script>)
    private function getJsCode($styles)
    {
        return '&lt;script'.$styles[1].'&gt;'.$this -> hl_js($styles[2]).'&lt;/script&gt;';

    }

    // funkcja zwracająca kod JavaScript (onload="kod")
    private function getJsCodeInline($styles)
    {
        return 'on'.$styles[1].'=<span class="htmlsymbol">"</span>'.$this -> hl_js($styles[2]).'<span class="htmlsymbol">"</span>';
    }

    /* || cssCode || */
    function hl_css($css='')
    {
        if(!$css) $css = $this -> mycode;

        // wykryto kod php => zmiana trybu wyświetlania
        // if((strpos($css,'&lt;?'))!==false) return $this -> hl_all();

        // konwersja " ma encję &quot;
        $css = str_replace('"', '&quot;', $css);

        // odszukanie i przeparsowanie wszystkich parametrow css
        $offset=0;
        $mycode='';
        while($start = strpos($css,'{',$offset))
        {
            $tag='';
            $end = strpos($css,'}',$start);
            $name = substr($css,$offset,$start-$offset);
            $params = substr($css,$start+1,$end-$start-1);

            $params = preg_replace('@([a-zA-Z0-9_-]+)\:((?:&[a-z0-9#-]+?;|[^;])+)(;)?@','<span class="cssparam">\\1</span>:<span class="cssparamvalue">\\2\\3</span>',$params);

            $mycode .='<span class="csstagname">'.$name.'</span><span class="cssdefault">{</span>'.$params.'<span class="cssdefault">}</span>';

            $offset = $end+1;
        }
        $mycode .= substr($css,$offset);

        $from = Array('@\#([a-zA-Z0-9#-;&\[\]]+)</span><span class="cssdefault">{@', // kolorowanie obiektow
                      '@\.([a-zA-Z0-9#-;&\[\]]+)</span><span class="cssdefault">{@', // kolorowanie klas
                      '@\:([a-zA-Z0-9#-;&\[\]]+)</span><span class="cssdefault">{@',
                      '/(\'|&quot;)(.+?)\\1/',                                        // kolorowanie stringow
                      '#\/\*(.+?)\*\/#');                                        // kolorowanie komentarzy

        $to = Array('<span class="cssidname">#\\1</span><span class="cssdefault">{</span>',
                    '<span class="cssclassname">.\\1</span><span class="cssdefault">{</span>',
                    '<span class="csshmmname">:\\1</span><span class="cssdefault">{</span>',
                    '<span class="cssstring">\\1\\2\\1</span>',
                    '<span class="csscomment">/*\\1*/</span>');
        $mycode = preg_replace($from,$to,$mycode);

        /*
         konwersja znaku < na encje hexadecymalna, dzieki czemu
         parser html nie wykryje tego jako rozpoczecie nowego znacznika html
        */
        $mycode = str_replace('&lt;', '&#60;', $mycode);

        return $mycode;
    }


    // funkcja zwracająca kod css (<style>kod</style>)
    private function getCssCode($styles)
    {
        return '&lt;style'.$styles[1].'&gt;'.$this -> hl_css($styles[2]).'&lt;/style&gt;';
    }

    // funkcja zwracająca kod css (style="kod")
    private function getCssCodeInline($styles)
    {
        return 'style=<span class="htmlsymbol">"</span>'.$this -> hl_cssInline($styles[1]).'<span class="htmlsymbol">"</span>';
    }

    /* || cssCode || */
    function hl_cssInline($mycode)
    {
        // kolorowanie parametrow
        $mycode = preg_replace('@([a-zA-Z0-9_-]+)\:((?:&[a-z0-9#-]+?;|[^;])+)(;)?@','<span class="cssparam">\\1</span>:<span class="cssparamvalue">\\2\\3</span>',$mycode);

        // kolorowanie stringow
        $mycode = preg_replace('/(\&quot;|\')(.+?)\\1/','<span class="cssstring">\\1\\2\\1</span>',$mycode);

        // kolorowanie komentarzy
        $mycode = preg_replace('#\/\*(.+?)\*\/#','<span class="csscomment">/*\\1*/</span>',$mycode);

        return $mycode;
    }

    /* || htmlCode || */
    public function hl_html($html='')
    {
        if(!$html) $html = "\n".$this -> mycode;
        else $html = "\n".$html;

        /*
         Tutaj jest glowny problem klasy ;) czyli parsowanie kodu html oraz wykrywanie jego bledow
         Nie ma sensu opisywania kolejnych etapow - jest to na tyle pomieszane ze mozna by było napisać
         dlugi artykul na ten temat
        */
        $mycode='';
        $offset=0;
        while($start = strpos($html,'&lt;',$offset))
        {
            $end = strpos($html,'&gt;',$start);
            // nie ma zamkniecia > pomimo wczesniejszego otwarcia <
            if($end===false)
            {
                if(($nextlt = strpos($html,'&lt;',$start+4))!==false)
                {
                    $mycode .= substr($html,0,$start).'<span class="htmlerror">'.substr($html,$start,$nextlt).'</span>';
                    $offset = $nextlt;
                }
                else
                {
                    $mycode .= '<span class="htmlerror">'.substr($html,$start).'</span>';
                    $offset = strlen($html);
                    break;
                }
            }
            else
            {
            $other = substr($html,$offset,$start-$offset);
            $tag = substr($html,$start+4,$end-$start-4);

            if($tag[0]=='!' && $tag[1]=='-' && $tag[2]=='-') // komentarz
            {
                $max = strlen($tag);

                if($tag[$max-1]!='-' || $tag[$max-2]!='-')
                {
                    $tag.='&gt;';
                    $offset =$end + 4;
                    if(($end = strpos($html,'--&gt;',$offset))===false)
                    {
                        //brak zamkniecia znacznika komentarza - skomentowanie reszty kodu html
                        $tag.=substr($html,$offset);
                        $mycode .= $other.'<span class="htmlcomment">&lt;'.$tag.'</span>';
                        // i zalamanie skryptu
                        break;
                    }

                    $tag.=substr($html,$offset,$end-$offset+2);

                    $end +=2;
                }

                $mycode .= $other.'<span class="htmlcomment">&lt;'.$tag.'&gt;</span>';
            }
            else // to nie jest komentarz
            {
                $mycode.=$other.'<span class="htmltag">&lt;</span>';

                // wystepuje spacja - tag posiada parametr(y)
                if($space = strpos($tag,'&nbsp;'))
                {
                    $tempparams = substr($tag,$space);
                    $tagname = substr($tag,0,$space);

                $string = 0;
                $left = 1;
                $error = 0;
                $tagparams = '<span class="htmlparamname">';
                for($i=0, $n=strlen($tempparams);$i<$n;$i++)
                {
                    // skladnia znacznika html
                    // <$tagname paramname($left=1)="paramvalue($left=0)" >
                    switch($tempparams[$i])
                    {
                        case '"':
                            if($string || $error)
                            {
                                if(isset($tempparams[$i+1]) && $tempparams[$i+1]!='&')
                                {
                                    if(!$error)
                                        $tagparams.='</span><span class="htmlsymbol">"</span><span class="htmlerror">';
                                    else
                                    {
                                        $tagparams.='"';
                                        $string=0;
                                    }
                                    $error = 1;
                                }
                                else
                                {
                                    if($error)
                                        $tagparams.='"</span>';
                                    else
                                        $tagparams.='</span><span class="htmlsymbol">"</span>';

                                    $string=0;
                                    $left = 1;
                                    $error=0;
                                }
                            }
                            else
                            {
                                $left = 0;
                                if((strpos($tempparams,'"',$i+1))===false)
                                {
                                    $error = 1;
                                    $tagparams .= '<span class="htmlerror">"';
                                }
                                else
                                {
                                    $string=1;
                                    $tagparams .='<span class="htmlsymbol">"</span><span class="htmlparamvalue">';
                                }
                            }
                            break;
                        case '=':
                            if($string) { $tagparams.=$tempparams[$i]; break;}
                            else $left = 0;
                            $tagparams .='=';
                            break;
                        case '&':
                            if($error && $tempparams[$i+1]=='n' && $tempparams[$i+2]=='b' && $tempparams[$i+3]=='s' && $tempparams[$i+4]=='p' && $tempparams[$i+5]==';')
                            {
                                $tagparams.='</span>&nbsp;';
                                $i+=5;
                                $error=0;
                                $left=1;
                                $string=0;
                                $tagparams.='<span class="htmlparamname">';
                            }
                            else $tagparams.='&';
                            break;
                        case "'":
                            if($i+1==$n)
                            {
                                $tagparams.='\'';
                                $error=0;
                            }
                            else $tagparams .= $tempparams[$i];
                            break;
                        case '/':
                            if($i+1==$n) {$tagparams .='<span class="htmltag">/</span>'; break;}
                        default:

                            if(!$left && !$string && !$error) {$tagparams.='</span><span class="htmlerror">'; $error = 1;}
                            elseif($left)
                            {
                                if(HTML_STRICT && $tempparams[$i]!=strtolower($tempparams[$i]))
                                {
                                    $tagparams .= '<span class="htmlerror2">'.$tempparams[$i].'</span>';
                                    break;
                                }
                            }
                            $tagparams .=$tempparams[$i];
                            break;
                    }
                }

                $tagparams.='</span>';
                if($error) $tagparams .='</span>';
                    // sprawdzanie wielkosci nazw znacznikow
                    if(HTML_STRICT && $tagname!='!DOCTYPE' && $tagname!=strtolower($tagname))
                        $mycode.= '<span class="htmlerror2">'.$tagname.'</span>';
                    else 
                        $mycode.= '<span class="htmltagname">'.$tagname.'</span>';

                }
                else // znacznik prosty(nie posiada parametrow) (np. <br/>, <hr/>
                {
                    if(HTML_STRICT && (strtolower($tag)!=$tag || $tag == 'hr' || $tag == 'br') )
                    // niepoprawna wersja znacznikow tj. <hr> zamiast <hr/>, <br> zamiast<br/>
                    // lub znacznik zapisany wielkimi literami
                        $mycode.= '<span class="htmlerror2">'.$tag.'</span>';
                    else 
                        $mycode.= '<span class="htmltagname">'.$tag.'</span>';

                    $tagparams='';
                }

                // zamykamy znacznik
                $mycode.=$tagparams.'<span class="htmltag">&gt;</span>';
            }
            // zwiekszamy offset o 4 (&gt; ma 4 znaki)
            $offset = $end+4;
            }
        }
        // doklejamy reszte
        $mycode .= substr($html,$offset);
        return $mycode;
    }

    /* || phpCode || */
    public function hl_php($mycode='')
    {
        if(!$mycode) $mycode = $this -> mycode;

        // zamiana kolorow na klasy
        // stale pobrane za pomocą ini_get(); (patrz kod nad klasą)
        $from = Array('style="color: '.PHP_STRING.'"','style="color: '.PHP_DEFAULT.'"','style="color: '.PHP_KEYWORD.'"','style="color: '.PHP_COMMENT.'"','style="color: '.PHP_HTML.'"');
        $to = Array('class="phpstring"','class="phpdefault"','class="phpkeyword"','class="phpcomment"','class="phphtml"');

        return str_replace($from, $to,$mycode);
    }

    // funkcja zwraca wygenerowany kod php
    private function getPhpCode($id)
    {
        return $this -> phpcode[$id[1]];
    }

    public function hl_all($code='')
    {
        if(!$code) $code = $this -> mycode;
        $this -> mode = 'all';

		$code = str_replace('<span style="color: '.PHP_DEFAULT.'">&lt;?php', '&lt;?php<span style="color: '.PHP_DEFAULT.'">',$code);
		$code = str_replace('?&gt;</span>', '</span>?&gt;',$code);

        if($phpstart = strpos($code,'&lt;?php')!==false)
        {
            $parse['php'] = 1;

            // wycinanie kodu php(aby nie zostal pomylony z kodem html)
            $offset=0;
            $i=0;
            $newcode='';

            while( ($phpstart=strpos($code,'&lt;?', $offset))!==false && ($phpend = strpos($code,'?&gt;',$phpstart+2))!==false)
            {
                $this -> phpcode[] = $this -> hl_php(substr($code,$phpstart,$phpend-$phpstart+5));
                $newcode .= substr($code,$offset,$phpstart-$offset).'[phpcode['.$i.']]';
                $i++; // zwiekszenie licznika phpcode
                $offset = $phpend +5;
                $phpstart = 0;
            }
            // jesli jest gdzies niezamkniety znacznik <?php
            if($phpstart){
                $this -> phpcode[] = $this -> hl_php(substr($code,$phpstart));
                $newcode .= substr($code,$offset,$phpstart-$offset).'[phpcode['.$i.']]';
//                $newcode .= str_replace('&lt;?php','&#60;?php',substr($code,$offset));
}            else
                $newcode .= substr($code,$offset);
        }
        else $newcode = $code;

        // kolorowanie kodu JavaScript (<script> kod </script>)
        $newcode = preg_replace_callback('#&lt;script(.*?)&gt;(.*?)&lt;/script&gt;#',Array($this,'getJsCode'),$newcode);

        // kolorowanie kodu css (<style> kod </style>)
        $newcode = preg_replace_callback('#&lt;style(.*?)&gt;(.*?)&lt;/style&gt;#',Array($this,'getCssCode'),$newcode);

        // kolorowanie kodu html
       $newcode = $this -> hl_html($newcode);


        // kolorowanie kodu JavaScript ze znacznikow html np. onload ="kod"
        $newcode = preg_replace_callback('#on(abort|blur|change|click|dblclick|error|focus|keydown|keypress|keyup|load|mousedown|mousemove|mouseout|mouseover|mouseup|reset|resize|select|submit|unload)=<span class="htmlsymbol">"</span><span class="htmlparamvalue">(.*?)</span><span class="htmlsymbol">"</span>#',Array($this,'getJsCodeInline'),$newcode);

        // kolorowanie kodu css ze znacznikow html np. style="kod"
        $newcode = preg_replace_callback('#style=<span class="htmlsymbol">"</span><span class="htmlparamvalue">(.*?)</span><span class="htmlsymbol">"</span>#',Array($this,'getCssCodeInline'),$newcode);

        // wklejanie spowrotem pokolorowanego kodu php
        if(isset($parse['php']))
        $newcode = preg_replace_callback('#\[phpcode\[(\d+)\]\]#',Array($this,'getPhpCode'),$newcode);

// echo '<pre>'.htmlspecialchars( $newcode, ENT_COMPAT, 'UTF-8').'</pre>'; 


		$newcode = str_replace('&lt;?php<span class="phpdefault">', '<span class="phpdefault">&lt;?php', $newcode);
		$newcode = str_replace('</span>?&gt;','?&gt;</span>', $newcode);
// echo '<pre>'.htmlspecialchars( $newcode, ENT_COMPAT, 'UTF-8').'</pre>'; 

        return $newcode;
    }

    // ustawienia specjalne w zaleznosci od parametrow
    public function setParams($params)
    {
        if( isset($params['line']) && is_numeric($params['line']) && $params['line']>0)
            $this -> lineOffset = $params['line'];
        else
            $this -> lineOffset = 1;
    }

    // brak parametrow w znaczniku - czyscimy stare pozostalosci
    public function resetParams()
    {
        $this -> lineOffset = 1;
    }

    public function display($mycode)
    {
        // generowanie numerow lini
        $lines_list='';
        $bits = explode("<br />",$mycode);
        $lines = count($bits);
        $lines+=$this -> lineOffset;
        $htmlcode='';
        for($i=$this -> lineOffset;$i<$lines;$i++)
        {
            $lines_list.=$i.'<br>';
        }
        
        $mycode = str_replace('<br />', '<br>', $mycode);

        $mycode = '<div class="codecontainer"><div class="codelines">'.$lines_list.'</div><div class="code">'.$mycode.'</div></div>';

        return $mycode;
    }
}
?>