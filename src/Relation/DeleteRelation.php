<?php
/**
 * Created by dog-ears
 */

namespace dogears\L5scaffold\Relation;

use Illuminate\Filesystem\Filesystem;
use dogears\L5scaffold\Commands\ScaffoldMakeCommand;
use dogears\L5scaffold\Stubs\StubController;
use dogears\L5scaffold\Traits\MakerTrait;
use dogears\L5scaffold\Traits\NameSolverTrait;
use dogears\L5scaffold\Traits\OutputTrait;

class DeleteRelation {
    use MakerTrait,NameSolverTrait,OutputTrait;

    protected $files;
    protected $commandObj;

    public function __construct($command, Filesystem $files)
    {
        $this->files = $files;
        $this->commandObj = $command;
        $this->start();
    }

    protected function start()
    {
        $this->commandObj->info('delete');

        //short cut
        $this->model_A_name = $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.app_model_class'));
        $this->model_B_name = $this->solveName($this->commandObj->argument('model_B'), config('l5scaffold.app_name_rules.app_model_class'));

        $this->deleteModel();
        $this->deleteView();
    }

    protected function deleteModel(){

        $this->deleteModel_modelA();
        $this->deleteModel_modelB();
        $this->deleteModel_modelB2();

    }

    protected function deleteModel_modelA(){

        //get output_path and filename
        $output_path = './app/';
        $output_filename = $this->model_A_name.'.php';

        //replace word
        $pattern = '#(\n\n\s// generated by relation command - '.$this->model_A_name.','.$this->model_B_name.' - start)(.*?)}(// end)#s';
        $replacement = '';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='model_A updated successfully', $debug=false );
    }

    protected function deleteModel_modelB(){

        //get output_path and filename
        $output_path = './app/';
        $output_filename = $this->model_B_name.'.php';

        //replace word
        $pattern = '#(\n\n\s// generated by relation command - '.$this->model_A_name.','.$this->model_B_name.' - start)(.*?)}(// end)#s';
        $replacement = '';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='model_B updated successfully', $debug=false );
    }

    protected function deleteModel_modelB2(){

        //get output_path and filename
        $output_path = './app/';
        $output_filename = $this->model_B_name.'.php';

        //replace word
        $pattern = '#^.*//generated by relation command - '. $this->model_A_name. ','. $this->model_B_name. '\n#m';  //delete one line
        $replacement = '';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='model_B2 updated successfully', $debug=false );
    }

    protected function deleteView(){

        $this->deleteView_index();
        $this->deleteView_show();
        $this->deleteView_form();
    }

    protected function deleteView_index(){

        //get output_path and filename
        $output_path = './resources/views/apples/';
        $output_filename = 'index.blade.php';

        //replace word
        $pattern = '#(.*)(<th>'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.NAME_NAME')).'_NAME</th>)(.*)(<td>{{\$'.
                    $this->solveName($this->commandObj->argument('model_B'), config('l5scaffold.app_name_rules.app_model_var')).'->'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.app_model_var')).'->name}}</td>)(.*)#s';
        $replacement = '\1<th>'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.NAME_NAME')).'_ID</th>\3<td>{{$'.
                    $this->solveName($this->commandObj->argument('model_B'), config('l5scaffold.app_name_rules.app_model_var')).'->'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id}}</td>\5';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='View_index updated successfully', $debug=false );
    }

    protected function deleteView_show(){

        //get output_path and filename
        $output_path = './resources/views/apples/';
        $output_filename = 'show.blade.php';

        //replace word
        $pattern = '#(.*<label for=")'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_name">'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.NAME_NAME')).'_NAME</label>(.*)<p class="form-control-static">{{\$'.
                    $this->solveName($this->commandObj->argument('model_B'), config('l5scaffold.app_name_rules.app_model_var')).'->'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.app_model_var')).'->name}}</p>(.*)#s';
        $replacement = '\1'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id">'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.NAME_NAME')).'_ID</label>\2<p class="form-control-static">{{$'.
                    $this->solveName($this->commandObj->argument('model_B'), config('l5scaffold.app_name_rules.app_model_var')).'->'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id}}</p>\3';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='View_index updated successfully', $debug=false );
    }

    protected function deleteView_form(){

        //get output_path and filename
        $output_path = './resources/views/apples/';
        $output_filename = '_form.blade.php';

        //replace word
        $pattern = '#(.*)<label for="'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id-field">(.*)_name</label>(.*){!! Form::select\("'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id", \$list\["'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.app_model_class')).'"\], null,(.*)#s';
        $replacement = '\1<label for="'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id-field">\2_id</label>\3{!! Form::text("'.
                    $this->solveName($this->commandObj->argument('model_A'), config('l5scaffold.app_name_rules.name_name')).'_id", null,\4';

        //output(use OutputTrait)
        $this->outputReplace( $output_path, $output_filename, $pattern, $replacement, $message_success='View_form updated successfully', $debug=false );
    }
}