<?php
/**
 * Created by dog-ears

[入力]
・スタブパス
・カスタムリプレース（配列）
・
[処理]
スタブを取得し、
リプレースをし、
出力する。

[出力]
・コンパイルされたスタブ（テキスト）
 
 */

namespace dogears\L5scaffold\Stubs;

use Illuminate\Filesystem\Filesystem;
use dogears\L5scaffold\Commands\ScaffoldMakeCommand;
use dogears\L5scaffold\Migrations\SchemaParser;
use dogears\L5scaffold\Migrations\SyntaxBuilder;
use dogears\L5scaffold\Traits\NameSolverTrait;

class StubController {

    use NameSolverTrait;

    protected $commandObj;
    protected $files;
    protected $stub_pathname;
    protected $schema_repalce_type;
    protected $custom_replace;

    public function __construct($command, Filesystem $files, $stub_pathname, $schema_repalce_type = null, $custom_replace = null)
    {
        $this->commandObj = $command;
        $this->files = $files;
        $this->stub_pathname = $stub_pathname;
        $this->schema_repalce_type = $schema_repalce_type;
        $this->custom_replace = $custom_replace;
    }

    public function getCompiled(){

        //スタブの取得
        $stub = $this->files->get($this->stub_pathname);
        
        //アプリ名コンパイル
        $this->compileAppName($stub);
        
        //スキーマコンパイル
        if( $this->schema_repalce_type ){
            $this->compileSchema($stub, $this->schema_repalce_type);
        }

        //カスタムコンパイル
        if( $this->custom_replace ){
            $this->compileCustomReplace($stub);
        }

        return $stub;
    }

    protected function compileAppName(&$stub){

        //get command name
        $commandObjNames = explode('\\',get_class($this->commandObj));
        $this->commandObjName = end( $commandObjNames );

        //config取得
        $app_name_rules = config('l5scaffold.app_name_rules');

        if( $this->commandObjName === 'MakeRelationCommand' || $this->commandObjName === 'DeleteRelationCommand' ){ //for RelationCommand

            foreach($app_name_rules as $keyword => $type){
    
                $stub = str_replace(
                    '{{model_A.'.$keyword.'}}',
                    $this->solveName( $this->commandObj->argument('model_A'), $type ),
                    $stub
                );
                $stub = str_replace(
                    '{{model_B.'.$keyword.'}}',
                    $this->solveName( $this->commandObj->argument('model_B'), $type ),
                    $stub
                );
            }

        }else{  //for sccafoldCommand
            
            foreach($app_name_rules as $keyword => $type){
    
                $stub = str_replace(
                    '{{'.$keyword.'}}',
                    $this->solveName( $this->commandObj->argument('name'), $type ),
                    $stub
                );
            }
        }

        return $this;
    }

    protected function compileSchema(&$stub, $type='migration')
    {
        //スキーマの取得
        if ($schema = $this->commandObj->option('schema')) {
            $schema = (new SchemaParser)->parse($schema);
        }

        //状況に応じて、スキーマ処理
        if($type === 'migration'){

            // Create migration fields
            $schema = (new SyntaxBuilder)->create($schema, $this->commandObj->getMeta());
            $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);

        } else if($type === 'factory'){

            // Create controllers fields
            $schema = (new SyntaxBuilder)->create($schema, $this->commandObj->getMeta(), 'factory');
            $stub = str_replace('{{schema_factory}}', $schema, $stub);

        } else if($type === 'model'){

            // Create mass assignment fields in model
            $schema = (new SyntaxBuilder)->create($schema, $this->commandObj->getMeta(), 'model');
            $stub = str_replace('{{schema_model}}', $schema, $stub);

        } else {}

        return $this;
    }

    protected function compileCustomReplace(&$stub){

        foreach( $this->custom_replace as $before => $after ){
            $stub = str_replace('{{'.$before.'}}', $after, $stub);
        }

        return $this;

    }
    
}










