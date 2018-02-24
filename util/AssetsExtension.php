<?php

/*
 *
 * @dev jomino2017
 * 
 */

namespace Util;

class AssetsExtension extends \Twig_Extension
{

    /**
     * @var String
     */
    private $domain = "vendor";

    /**
     * @var String
     */
    private $ext = "json";

    /**
     * @var String
     */
    private $type = "js";
        
    /**
     * @var String
     */
    private $assets;

    /**
     * @var String
     */
    private $base;

    /**
     * @var Boolean
     */
    private $minified;

    /**
     * @var String
     */
    private $filename;
    
    /**
     * @var Array
     */
    private $patterns = array(
        "css" => '<link rel="stylesheet" type="text/css" href="%s" />',
        "js" => '<script type="text/javascript" src="%s"></script>',
        "images" => '<img alt="" src="%s">'  
    );

    public function __construct($abs_path,$rel_path,$minified)
    {
        $this->assets = $abs_path;
        $this->base = $rel_path;
        $this->minified = $minified;
    }

    public function getName()
    {
        return 'slim_assets';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('assets', array($this, 'getAssets')),
            new \Twig_SimpleFunction('asset_path', array($this, 'getPaths')),
            new \Twig_SimpleFunction('image', array($this, 'getImage'))
        ];
    }

    private function _setAssets($name,$type,$domain,$filename)
    {

        if($name==""){
            return;
        }

        if($domain!=""){
            $this->domain = $domain;
        }

        if($type!=""){
            $this->type = $type;
        }

        $this->filename = $filename;

    }

    public function getImage($filename="",$class="")
    {
        
        $name = "default";
        $domain="custom";
        $type = "images";

        if($filename==""){
            return;
        }

        if(empty($class)){
            return $this->getAssets($name,$type,$domain,$filename);
        }

        $path = $this->getPaths($name,$type,$domain,$filename);

        if(!empty($path)){
            return preg_replace( "#^<img(\s)*#", " class=\"{$class}\" ", $this->_getHtml($path[0]));
        }

    }

    public function getAssets($name="",$type="",$domain="",$filename="")
    {
        $this->_setAssets($name,$type,$domain,$filename);
        return implode(PHP_EOL,array_map(array($this,'_getHtml'),$this->_getPaths($name)));
    }

    public function getPaths($name="",$type="",$domain="",$filename="")
    {
        $this->_setAssets($name,$type,$domain,$filename);
        return $this->_getPaths($name);
    }

    private function _getPaths($name)
    {

        $paths = array();
        $config = $this->_getAssetsFile();

        if(!empty($config->{$name})){
            foreach($config->{$name} as $package=>$local){
                if(!empty($local->{$this->type})){
                    $files_patts = $local->{$this->type};
                    if(is_array($files_patts)){
                        for($i=0;$i<sizeof($files_patts);$i++){
                            if(strpos($files_patts[$i],'/*')!==false){
                                $dir_patt = str_replace('/*','',$files_patts[$i]);
                                $curr_dir = "{$this->domain}/{$package}/{$dir_patt}";
                                $rel_dir = "{$this->base}/{$curr_dir}";
                                $file_name = $this->filename;
                                $paths = array_merge( $paths, array_map( function($item) use($rel_dir,$file_name){
                                    if(!empty($file_name)){
                                        if($file_name==$item){
                                            return("{$rel_dir}/{$item}");
                                        }
                                    }else{
                                        return("{$rel_dir}/{$item}");
                                    }
                                }, $this->_getFiles(rtrim("{$this->assets}/".$curr_dir,'/'))));
                            }else{
                                $paths = array_merge( $paths,[
                                    "{$this->base}/{$this->domain}/{$package}/".$files_patts[$i]
                                ]);
                            }
                        }
                    }
                }
            }
        }

        //var_dump($paths);

        return $paths;
        
    }

    private function _getHtml($path)
    {
        $patt = $this->patterns[$this->type];
        return sprintf($patt,$path);
    }

    private function _getAssetsFile()
    {
        $path = "{$this->assets}/{$this->domain}.{$this->ext}";
        //print($path.'<br>');
        return $this->_loadJson($path);
    }

    private function _loadJson($resource)
    {
        //print((is_file($resource) ? 'IS_FILE':'NO_FILE').'<br>');
        return is_file($resource) ? json_decode(file_get_contents($resource), false):null;
    }

    private function _getFiles($path)
    {
        $files = array();
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $item)
        {
            if (!$item->isDot() && $item->isFile() && $item->getExtension()==$this->type)
            {
                if($this->minified===true){
                    if(strpos($item->getFilename(),'.min')!==false){ $files[] = $item->getFilename(); }
                }else{
                    if(strpos($item->getFilename(),'.min')===false){ $files[] = $item->getFilename(); }
                }
            }
        }
        return $files;
    }

}