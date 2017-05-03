# AutoloaderClass #

Com essa classe de simples implementação você não precisa mais usar vários require ou include.

Modo de usar:


  require_once 'AutoloaderClass.php';

  AutoloaderClass::init( dirname( __FILE__ ) );  


Viu como é simples, agora você pode gerenciar toda a sua aplicação sem a necessidade de uso de require ou include, mas atenção, você não pode usar nomes iguais para classes em diferentes diretórios, se você for usar nomes iguais, então use "namespace" e o composer.
