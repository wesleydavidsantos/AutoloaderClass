<?php 
/**
 * Library autoloader - spl_autoload_register
 * Realiza o include de qualquer classe do sistema
*/
class AutoloaderClass{

	public static $loader;
	
    /**
     * Recebe a lista de diretórios a serem pesquisados
     * @default APPPATH
    */
	private $directories;
    
    /**
     * Nome do arquivo a ser procurado
    */
    private $file;
        
    /**
     * Diretório a ser pesquisado para realizar o AutoLoad
     * @param $dir Mixed <Array> or <String> - Informa o nome dos diretórios a serem pesquisados
    */
	private function __construct( $dir ) {
	   
		spl_autoload_register(Array($this, 'autoLoadClass'));
        
        $this->setDir( $dir );
        
	}
    
    /**
     * Registra o diretório no array
     * @param $dir <String>
    */
    private function registerDir( $dir ){
        if( !isset( $this->directories[ $dir ] ) ) $this->directories[] = $dir;
    }
    
    /**
     * Armazena a lista de diretórios
     * @param $dir Mixed <Array> or <String> - Informa o nome dos diretórios a serem pesquisados
    */
    public function setDir( $dir ){
        
        if( is_array( $dir ) ){
            foreach( $dir as $d ){
                $this->registerDir( $d );
            }
        }else{
            $this->registerDir( $dir );
        }
        
    }
    
	public static function init( $dir ) {
	   
		if (!function_exists('spl_autoload_register')) {
			throw new Exception("AutoloaderClass: Standard PHP Library (SPL) is required.");
			return false;
		}
		if (self::$loader == null) {
			self::$loader = new AutoloaderClass( $dir );
		}else{
		    self::$loader->setDir( $dir );  
		}
        
		return self::$loader;
        
	}
    
    /**
     * Verifica se o arquivo existe se sim ele recebe um require_once
     * @return Boolean
    */
    private function validFile( $dir ){
        
        # No do arquivo a ser procurado
        $dir = rtrim( $dir, '/' ); # Retira a barra no final, desta forma impede de ser adicionada 2 barras
        $file = $dir . '/' . $this->file;

        if (file_exists( $file )) {
            require_once $file;
            return true;
		}
        
        return false;
        
    }
	
	private function autoLoadClass($class) {
        
        # Nome do arquivo a ser procurado
        $this->file = $class . '.php';
        
        # Pecorre a lista de diretórios fornecida        
        for( $x=0; $x < sizeof( $this->directories ); $x++ ){
            
            $dir = $this->directories[ $x ];
            
            # Retira a barra no final
            $dir = rtrim( $dir, '/' ); 
            
            # Primeira verificação é feita na raiz do diretório
            if( !$this->validFile( $dir ) ){
                
                # Abre o diretório
                $handler = opendir( $dir );
                
                while( $item = readdir( $handler ) ){
                    
                    if( $item != '.' && $item != '..' ){
                        
                        $sub_dir = $dir . '/' . $item;
   
                        # Verifica se é um diretório, se sim verifica se o arquivo se encontra dentro dele
                        if( is_dir( $sub_dir ) ){
                            
                            # Registra o subdiretório para realizar pesquisa dentro deles, trabalhando de forma recursiva
                            $this->registerDir( $sub_dir );
                            
                            # Se o arquivo estiver dentro retorna TRUE, ( É usado o return em vez de break para finalizar os 2 loops )
                            if( $this->validFile( $sub_dir ) ) return true;
                                
                        }
                        
                    }
                    
                }
            
            }
            
        }
        
	}
	
}
