<?php
class Template
{
    private $templateDir = "templates/";

    private $languageDir = "language/";

    private $leftDelimiter = '{$';

    private $rightDelimiter = '}';

    private $leftDelimiterF = '{';

    private $rightDelimiterF = '}';

    private $leftDelimiterC = '\{\*';

    private $rightDelimiterC = '\*\}';

    private $leftDelimiterL = '\{L_';

    private $rightDelimiterL = '\}';

    private $templateFile = "";

    private $languageFile = "";

    private $templateName = "";

    private $template = "";

    public function __construct($tpl_dir = "", $lang_dir = "") {
        if ( !empty($tpl_dir) ) {
            $this->templateDir = $tpl_dir;
        }
        if ( !empty($lang_dir) ) {
            $this->languageDir = $lang_dir;
        }
    }

    public function load($file)    {
        $this->templateName = $file;
        $this->templateFile = $this->templateDir.$file;

        if( !empty($this->templateFile) ) {
            if( file_exists($this->templateFile) ) {
                $this->template = file_get_contents($this->templateFile);
            } else {
                return false;
            }
        } else {
           return false;
        }

        // Funktionen parsen
        $this->parseFunctions();
    }

    public function assign($replace, $replacement) {
        $this->template = str_replace( $this->leftDelimiter .$replace.$this->rightDelimiter,
                                       $replacement, (string) $this->template );
    }

    public function loadLanguage($files) {
        $this->languageFiles = $files;

        for( $i = 0; $i < count( $this->languageFiles ); $i++ ) {
            if ( !file_exists( $this->languageDir .$this->languageFiles[$i] ) ) {
                return false;
            } else {
                 include_once( $this->languageDir .$this->languageFiles[$i] );
            }
        }

        $this->replaceLangVars($lang);

        return $lang;
    }

    private function replaceLangVars($lang) {
        $this->template = preg_replace("/\{L_(.*)\}/isUe", "\$lang[strtolower('\\1')]", (string) $this->template);
    }

    private function parseFunctions() {
        while( preg_match( "/" .$this->leftDelimiterF ."include file=\"(.*)\.(.*)\""
                           .$this->rightDelimiterF ."/isUe", (string) $this->template) )
        {
            $this->template = preg_replace( "/" .$this->leftDelimiterF ."include file=\"(.*)\.(.*)\""
                                            .$this->rightDelimiterF."/isUe",
                                            "file_get_contents(\$this->templateDir.'\\1'.'.'.'\\2')",
                                            (string) $this->template );
        }

        $this->template = preg_replace_callback( "/" .$this->leftDelimiterC ."(.*)" .$this->rightDelimiterC ."/isUe",
                                        "", (string) $this->template );
    }

    public function display() {
        echo $this->template;
    }
}
?>