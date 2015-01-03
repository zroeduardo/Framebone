<?php
    use \Framebone as FB,
        \Framebone\Library as FBlib;

    /*//////////
        Framebone © 2014. Todos os direitos reservados a seus 
        respectivos proprietários.

        @author: Eduardo Segura
        @e-mail: zro.eduardo@gmail.com
    /*/////////

    /// Carregamos o loader das classes
    include_once(   "Packs" . DIRECTORY_SEPARATOR .
                    "Framebone" . DIRECTORY_SEPARATOR .
                    "Library" . DIRECTORY_SEPARATOR .
                    "Loader.php");

    /// Iniciamos o sistema.
    $app = new FB\App();

    echo "<pre>";
    var_dump(FBLib\Stack::fetch('database'));
    echo "</pre>";


    //var_dump(Framebone\Orm\Libraries\Mysql::connect());
    //exit();

    // // create the service locator
    // $serviceLocator = new ServiceLocator\ServiceLocator;

    // // add the entry service injector to the service locator
    // $serviceLocator->addInjector('entry', new Entry\EntryInjector);
    // $serviceLocator->addInjector('author', new Author\AuthorInjector);

    // // get the entry service via the associated service injector
    // $entryService = $serviceLocator->getService('entry');
    // $authorService = $serviceLocator->getService('author');

    // // display all the entries along with their associated comments (comments are lazy-loaded from the storage)
    // $entries = $entryService->find();

    // echo "<pre>";
    //     var_dump(count($entries));
    // echo "<pre>";

    // foreach ($entries as $entry) {
    //     echo '<h2>' . $entry->title . '</h2>';
    //     echo '<p>' . $entry->content . '</p>';
    //     foreach ($entry->comments as $comment) {
    //         echo '<p>' . $comment->content . ' ' . $comment->author->name . '</p>';
    //     }
    // }

    // $authors = $authorService->find();
    // foreach ($authors as $author) {
    //     echo '<h2>' . $author->name . ' ('.$author->id.')'.'</h2>';
    //     echo '<p>' . $author->email . '</p>';
    // }

    // echo "<pre>";
    // var_dump(Framebone\Stack::fetch(array('database')));

    //$adapter = new \Packages\Framebone\Database\Model\Library\MysqlAdapter(Framebone\Stack::fetch(array('database'))['database']);
    // $create = array(
    //     'id INT',
    //     'oi VARCHAR(33)'
    // );


    // echo "</pre>";
    // var_dump($adapter->create('hihi', $create));
    // echo "</pre>";

    // $pack = array(
    //     'id VARCHAR(496)',
    //     'name TEXT',
    //     'description TEXT',
    //     'dependencies TEXT',
    // );
    
    // echo "</pre>";
    // var_dump($adapter->create('pack', $pack));
    // echo "</pre>";

    // $pack_version = array(
    //     'id INT',
    //     'version INT',
    //     'pack_id VARCHAR(496)',
    // );
    
    // echo "</pre>";
    // var_dump($adapter->create('pack_version', $pack_version));
    // echo "</pre>";

    // $pack_log = array(
    //     'id INT',
    //     'author VARCHAR(255)',
    //     'email VARCHAR(255)',
    //     'description TEXT',
    //     'version_id INT',
    // );
    
    // echo "</pre>";
    // var_dump($adapter->create('pack_log', $pack_log));
    // echo "</pre>";

    // $pack = new Package\Entity('Framebone\\Package');

    // // Executando um check no pacote
    // echo "<pre>";
    // var_dump($pack->check());
    // echo "</pre>";

    // $check = $pack->check();

    // if($check >= 0) {
    //     echo 'INSTALADO';
    //     if($check > 0) {
    //         echo "e TEM UPDATES";
    //     }
    //     echo ".";
    // } else {
    //     echo "NÃO INSTALADO.";
    // }

    // echo "<pre>";
    //    $pack->install();
    // echo "</pre>";

    // echo "<pre>";
    //     //var_dump($pack->uninstall());
    // echo "</pre>";

    // echo "<pre>";
    //     var_dump($pack->update());
    // echo "</pre>";



    // Executando instalacao e/ou update no pacote
    //$pack->update();

    //  // create the service locator
    // $serviceLocator = new ServiceLocator\ServiceLocator;

    // // add the entry service injector to the service locator
    // $serviceLocator->addInjector('pack', new Pack\PackInjector);

    // // get the entry service via the associated service injector
    // $packService = $serviceLocator->getService('pack');

    // // display all the entries along with their associated comments (comments are lazy-loaded from the storage)
    // $packs = $packService->find();

    // $pack = $packService->findByCid('blog_zed');

    // echo "<pre>";
    //     //var_dump($packService->findByCid('blogzed'));
    //     //var_dump($packService->findByCid('blogzed')->versions->findById(42)->logs->find());
    // echo "</pre>";
    // exit();

    // echo "<pre>";
    // echo "<ul>";
    // foreach ($packs as $key => $pack) {
    //     echo "<li>".$pack->id."</li>";
    //     echo "<li>".$pack->title."</li>";
    //     //echo "<li>";
    //         echo "<ul>";
    //         foreach ($pack->versions as $key2 => $version) {
    //             echo "<li>".$version->date."</li>";
    //             echo "<li>".$version->description."</li>";
    //             //echo "<li>";
    //                 echo "<ul>";
    //                 foreach ($version->logs as $key3 => $log) {
    //                     echo "<li>".$key3 .' - '. $log->description."</li>";
    //                 }
    //             echo "</ul>";
    //         }
    //         echo "</ul>";
    //     //echo "</li>";
    // }
    // echo "</ul>";
    // echo "</pre>";


    // // add a new entry to the storage
    // $entry = new Entry\Entry(array(
    //     'title'   => 'My fourth blog post',
    //     'content' => 'This is the content of the fourth blog post'
    // ));
    // $entryService->insert($entry);

    // delete an entry from the storage
    // $entryService->delete(1);

    // echo "<pre>";
    // var_dump(fb\Stack::fetch(array('database')));
    // echo "</pre>";

    //$app->debug('Stack');


    // if(\Bootstrap\Stack::fetch('ENV_STATE') === 'private')
    // {
    //     $time = microtime();
    //     $time = explode(' ', $time);
    //     $time = $time[1] + $time[0];
    //     $start = $time;

    //      //for ($i=0; $i < 3000; $i++) { 
    //       //\Framebone\System::response();
    //      //}
    //     //  new \Jasper\Response();
    //     \Framebone\System::response();

    //     $time = microtime();
    //     $time = explode(' ', $time);
    //     $time = $time[1] + $time[0];
    //     $finish = $time;
    //     $total_time = $finish - $start;
        
    //     echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; display: block;">Page generated in '.$total_time.' seconds. </div>';
    // }
    // else
    // {
    //  //new \Jasper\Response();
    // }


    //var_dump(\FRAMEWORK\System::getter('_BASE_URL_'));

    // \AAA\Teste::stackSet('abc', array('et', 'te'), false);
    // \AAA\Teste::stackSet('ads', new \FRAMEWORK\System());
    // \AAA\Teste::stackSet('dasda', 'skfj');
    // \AAA\Teste::stackSet('asdasd', 'sadasd');

    // var_dump(\AAA\Teste::stackRemove('abc'));
    // var_dump(\AAA\Teste::stackSet('abc', '1213', true));
    // var_dump(\AAA\Teste::stackSet('abc', 'isudgyf'));

    // //\FRAMEWORK\System::namespaceO();

    // echo "<pre>";
    // var_dump(\AAA\Teste::stackAll());
    //      //echo $class; var_dump(__CLASS__);
    //      //var_dump(debug_backtrace());
    //      //exit();
    //      echo "</pre>";
    // //var_dump(\Framework\System::namespaceO());



    // if( \FRAMEWORK::getter('_ENV_STATE_') === '_private_' )
    // {
    //  $time = microtime();
    //  $time = explode(' ', $time);
    //  $time = $time[1] + $time[0];
    //  $start = $time;
    
    //  for ($i=0; $i < 1000; $i++) { 
    //      \FRAMEWORK::response();
    //  }
    //  //  new \Jasper\Response();

    //  $time = microtime();
    //  $time = explode(' ', $time);
    //  $time = $time[1] + $time[0];
    //  $finish = $time;
    //  $total_time = $finish - $start;
    //  echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; display: block;">Page generated in '.$total_time.' seconds. </div>';
    // }
    // else
    // {
    //  //new \Jasper\Response();
    // }
?>