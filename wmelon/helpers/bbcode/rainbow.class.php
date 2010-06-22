<?php
class Rainbow
{
	public $cache_dir = 'cache/rainbow/';
	public $mycode='';
	public $id = '';
	private $mode = '';

	public function loadFile($file)
	{
		$this -> assignCode(file_get_contents($file));
	}

	// cache functions
	public function getCache($id)
	{
		$hash = md5($id.'/code/');
		if(file_exists($this -> cache_dir.$hash.'.cache'))
			return file_get_contents($this -> cache_dir.$hash.'.cache');
		else return false;
	}

	public function doCache($id, $readycode)
	{
		$hash = md5($id.'/code/');

		if(@file_put_contents($this -> cache_dir.$hash.'.cache',$readycode))
			return true;
		else echo 'error in doCache(); - file_put_contents('.$this -> cache_dir.$hash.'.cache)';
	}

	public function deleteCache($id = '')
	{
		$hash = md5($id.'/code/');

		if(file_exists($this -> cache_dir.$hash.'.cache'))
			unlink($this -> cache_dir.$hash.'.cache');
	}

    public function make($mode, $code ='', $id = false, $cache=1)
    {
        // sprawdzamy czy tryb cachowania wlączony a cache dostepny
        // jesli tak pobieramy i zwracamy cache
        if($cache && $id && $cachecode = $this -> getCache($id)) return $cachecode;

        // sql znacznik specjalny który nie wymaga przedparsowania za pomocą highlight_string();
        $deletePhpTags= false;

        if($mode=='sql')
            $this -> mycode = $code;
        else
        {
            /* jesli kod php nie posiada znakow <?php ?> to musimy je tymczasowo dodac aby kod sie pokolorowal */
            if($mode == 'php' && strpos($this -> mycode,'<?php')===false)
            {
                $code = '<?php'.$code.'?>';
                $deletePhpTags = true;
            }

            /* przygotowanie kodu do obrobki
               funkcja highlight_string() świetnie konwertuje zmienną $code wyręczając kilka wyrażen regularnych
               dodatkowo kolorując kod php
            */

            ob_start();
            highlight_string($code);
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

        if($mode == 'code' || ($mode == 'html' && (strpos($this -> mycode,'style')!==false) || strpos($this -> mycode,'script')!==false)) $mode = 'all';

        include_once('rainbow_compiler.class.php');
        $compiler = new rainbowCompiler($mode);

        $mode = 'hl_'.$mode;
        $newcode = $compiler  -> $mode($this -> mycode);

        $readyCode = $compiler -> display($newcode);
        if($deletePhpTags)
        {
            $readyCode = str_replace('&lt;?php','',$readyCode);
            $readyCode = str_replace('?&gt;','',$readyCode);
        }
        if($cache && $id) $this -> doCache($id, $readyCode);

        return $readyCode;
    }
}
?>