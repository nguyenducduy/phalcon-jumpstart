<?php
/**
 * \Controller\Admin\CodegeneratorController.php
 * CodegeneratorController.php
 *
 * Codegenerator Controller for back-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-03-12
 * @category    Fly
 *
 */

namespace Controller\Admin;

use Fly\BaseController as FlyController;

class CodegeneratorController extends FlyController
{
    protected $message = '';
    protected $blockTable = [
        'fly_migration',
        'fly_user',
        'fly_logs',
    ];

    public $template = [
        'model' => '/modules/admin/views/codegenerator/generate_template/model.volt',
        'constantable' => '/modules/admin/views/codegenerator/generate_template/constantable.volt',
        'controller' => [
            'normal' => '/modules/admin/views/codegenerator/generate_template/controller_normal.volt',
            'ko' => '',
        ],
        'view' => [
            'normal' => [
                'index' => '/modules/admin/views/codegenerator/generate_template/view_index_normal.volt',
                'add' => '/modules/admin/views/codegenerator/generate_template/view_add_normal.volt',
                'edit' => '/modules/admin/views/codegenerator/generate_template/view_edit_normal.volt',
            ],
            'ko' => [],
        ],
        'lang' => '/modules/admin/views/codegenerator/generate_template/language.volt',
    ];

    public $columns;

    public function indexAction()
    {
        $this->tag->prependTitle('Code Generator');
        $this->breadcrumb->add('Code Generator', 'admin/codegenerator');
        $this->breadcrumb->add('Table list', 'admin/codegenerator');

        $this->view->setVars([
            'breadcrumb' => $this->breadcrumb->generate(),
            'listTables' => $this->db->listTables(),
            'blockTable' => $this->blockTable
        ]);
    }

    public function createAction()
    {
        $tableName = $this->dispatcher->getParam('table');
        $formData = [];
        $dbFields = $this->db->describeColumns($tableName);
        $tableAlias = '';
        if ($this->request->hasPost('fsubmit')) {
            // if ($this->security->checkToken()) {
                $formData['columnsRedefined'] = [];
                $formData['fproperty'] = $this->request->getPost('fproperty', null, []);
                $formData['fnullable'] = $this->request->getPost('fnullable', null, []);
                $formData['ffilterable'] = $this->request->getPost('ffilterable', null, []);
                $formData['fsortable'] = $this->request->getPost('fsortable', null, []);
                $formData['fsearchable'] = $this->request->getPost('fsearchable', null, []);
                $formData['fconstant'] = $this->request->getPost('fconstant', null, []);
                $formData['fexclude_i'] = $this->request->getPost('fexclude_i', null, []);
                $formData['fexclude_ae'] = $this->request->getPost('fexclude_ae', null, []);
                $formData['fvalidation'] = $this->request->getPost('fvalidation', null, []);
                $formData['finputtype'] = $this->request->getPost('finputtype', null, []);
                $formData['flabel'] = $this->request->getPost('flabel', null, []);
                $formData['ftype'] = $this->request->getPost('ftype', null, []);
                $formData['fmodelname'] = $this->request->getPost('fmodelname', null, '');
                $formData['fmodelextends'] = $this->request->getPost('fmodelextends', null, '');
                $formData['fprimary'] = $this->request->getPost('fprimary', null, '');
                $formData['fcontrollername'] = $this->request->getPost('fcontrollername', null, '');
                $formData['fmodulename'] = $this->request->getPost('fmodulename', null, '');
                $formData['frecordperpage'] = $this->request->getPost('frecordperpage', null, '1');
                $formData['fcontrollertype'] = $this->request->getPost('fcontrollertype', null, 'normal');

                //Property
                foreach ($formData['fproperty'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['property'] = $value;
                }

                //Nullable
                foreach ($formData['fnullable'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['nullable'] = $value;
                }

                //Filterable
                foreach ($formData['ffilterable'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['filterable'] = $value;
                }

                //Sortable
                foreach ($formData['fsortable'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['sortable'] = $value;
                }

                //Searchable
                foreach ($formData['fsearchable'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['searchable'] = $value;
                }

                //Constant
                foreach ($formData['fconstant'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['constant'] = $value;
                }

                //Type
                foreach ($formData['ftype'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['type'] = $value;
                }

                //Exclude Add/Edit
                foreach ($formData['fexclude_ae'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['exclude_ae'] = $value;
                }

                //Exclude Index
                foreach ($formData['fexclude_i'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['exclude_i'] = $value;
                }

                //PrimaryKey
                foreach ($formData['fprimary'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['primary'] = $value;
                }

                //Validation
                foreach ($formData['fvalidation'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['validation'] = $value;
                }

                //InputType
                foreach ($formData['finputtype'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['inputtype'] = $value;
                }

                //Label Controller
                foreach ($formData['flabel'] as $columnname => $value) {
                    $formData['columnsRedefined'][$columnname]['label'] = $value;
                }

                //generate model
                $this->generatemodel($formData['columnsRedefined'], $tableName, $formData['fmodelname'], $formData['fmodelextends']);

                //generate controller
                if ($formData['fcontrollertype'] == 'normal') {
                    $this->generatecontroller($formData['columnsRedefined'], $formData['fmodulename'], $formData['fcontrollername'], $formData['fmodelname'], $formData['frecordperpage']);
                } else {

                }

                //generate view
                if ($formData['fcontrollertype'] == 'normal') {
                    $this->generateview($formData['columnsRedefined'], $formData['fmodulename'], $formData['fcontrollername'], $formData['fmodelname']);
                } else {

                }

                //generate language
                if ($formData['fcontrollertype'] == 'normal') {
                    $this->generatelanguage($formData['columnsRedefined'], $formData['fcontrollername']);
                } else {

                }

                if (strlen($this->message) > 0) {
                    $this->flash->success($this->message);
                    $this->logger->name = 'generator'; // Your own log name
                    $this->logger->info($this->message);
                } else {
                    $this->flash->error('ERROR! GENERATING FAILED.');
                }
            // }
        }

        foreach ($dbFields as $field) {
            $column = [];
            $label = str_replace('_', ' ', $field->getName());
            $typeName = substr(\Model\Generator::getTypeName($field->getType()), 5);
            $inputType = $typeName == 'TEXT' ? 'textarea' : 'text';
            $column = [
                'name'      =>  $field->getName(),
                'property'      => str_replace('_', '', $field->getName()),
                'type'      =>  $field->getType(),
                'typename'  =>  $typeName,
                'size'      =>  $field->getSize(),
                'isNumeric' =>  $field->isNumeric(),
                'isPrimary' =>  $field->isPrimary(),
                'isNotNull' =>  $field->isNotNull(),
                'label'     =>  ucfirst($label),
                'inputtype' =>  $inputType
            ];

            if ($field->isPrimary()) {
                $formData['primarykey'] = $field->getName();
                $tableAlias = substr($formData['primarykey'], 0, strpos($formData['primarykey'], '_') + 1);
            }

            if (strlen($tableAlias) > 0) {
                $column['property'] = str_replace('_', '', str_replace($tableAlias, '', $field->getName()));
            }

            $formData['columns'][] = $column;
        }

        // Indexes List
        $formData['indexesCol'] = array();
        $myIndexList = $this->db->describeIndexes($tableName, $this->config->app_db->name);
        if ($myIndexList == true) {
            foreach ($myIndexList as $indexes) {
                foreach ($indexes->getColumns() as $indexCol) {
                    $formData['indexesCol'][] = $indexCol;
                }
            }
        }

        // Assign table Alias
        $formData['tableAlias'] = $tableAlias;

        // Assign Model Class name
        $formData['modelName'] = '';
        $tmpModelname = str_replace($this->config->app_db->prefix, '', $tableName);
        $tmpModelname = explode('_', $tmpModelname);
        foreach ($tmpModelname as $modelname) {
            $formData['modelName'] .= ucfirst($modelname) ;
        }

        // Assign Controller Class name
        $formData['controllerName'] = ucfirst(strtolower($formData['modelName']));
        // var_dump($formData);
        // die;
        $this->tag->prependTitle('Code Generator');
        $this->breadcrumb->add('Code Generator', 'admin/codegenerator');
        $this->breadcrumb->add('Table "'. $tableName .'"', 'admin/codegenerator/create');
        $this->view->setVars([
            'breadcrumb' => $this->breadcrumb->generate(),
            'tableName' => $tableName,
            'formData' => $formData,
            'blockTable' => $this->blockTable
        ]);
    }

    private function generatemodel($data, $table, $model, $basemodel)
    {
        $uploadSectionContent = '';
        $validationContent = '';
        $columnDefineContent = '';
        $constantDefineContent = '';
        $constantFunctionContent = '';
        $template = $this->filemanager->read($this->template['model']);

        // var_dump($data);
        foreach ($data as $field => $option) {
            //Column property annotation
            $columnDefine = '    /**' . "\n";

            if ($option['primary'] == 1) {
                $columnDefine .= '    * @Primary' . "\n";
                $columnDefine .= '    * @Identity' . "\n";
            }

            $columnDefine .= '    * @Column(type="';
            $columnDefine .= $option['type'] != 'INTEGER' ? 'string' : 'integer';
            $columnDefine .= '", nullable=';
            $columnDefine .= $option['nullable'] == 1 ? 'false' : 'true';
            $columnDefine .= ', column="'. $field .'")' . "\n";
            $columnDefine .= '    */' . "\n";
            $columnDefine .= '    public $';
            $columnDefine .= $option['property'] .';' . "\n\n";

            $columnDefineContent .= $columnDefine;

            //Constantable
            if (isset($option['constant']) && $option['constant'] != '') {
                $caseContent = '';
                $listContent = '';
                $listArrayContent = '';
                $templateConstantable = $this->filemanager->read($this->template['constantable']);
                $templateConstantable = str_replace('{{CONSTANT_FUNCTION_NAME}}', ucfirst($option['property']), $templateConstantable);
                $templateConstantable = str_replace('{{CONSTANT_PROPERTY}}', $option['property'], $templateConstantable);
                $templateConstantable = str_replace('{{CONSTANT_LABEL_NAME}}', ucfirst($option['property']), $templateConstantable);

                $constArray = explode(',', $option['constant']);
                // gen const value at top of a file
                for ($i = 0; $i < count($constArray); $i++) {
                    $caseString = '';
                    $listString = '';
                    $listArrayString = '';
                    $constantString = '';

                    $strArray = explode(':', $constArray[$i]);
                    $constKey = $strArray[0];
                    $constValue = $strArray[1];
                    $constName = $strArray[2];

                    $constantString .= "    const ";
                    $constantString .= $constKey ." = ". $constValue ."; \n";
                    $constantDefineContent .= $constantString;

                    $caseString .= '            case self::'. $constKey .':' . "\n";
                    $caseString .= '                $name = $this->lang->get("label_'. strtolower($constKey) .'");' . "\n";
                    $caseString .= '                break;' . "\n";
                    $caseContent .= $caseString;

                    $listString .= '            [' . "\n";
                    $listString .= '                "name" => $lang->get("label_'. strtolower($constKey) .'"),' . "\n";
                    $listString .= '                "value" => self::'. $constKey .'' . "\n";
                    $listString .= '            ],' . "\n";
                    $listContent .= $listString;

                    $listArrayString .= '            self::'. $constKey .',' . "\n";
                    $listArrayContent .= $listArrayString;
                }

                $templateConstantable = str_replace('{{CONSTANT_CASE}}', $caseContent, $templateConstantable);
                $templateConstantable = str_replace('{{CONSTANT_LIST}}', $listContent, $templateConstantable);
                $templateConstantable = str_replace('{{CONSTANT_LISTARRAY}}', $listArrayContent, $templateConstantable);
                $constantFunctionContent .= $templateConstantable . "\n";
            }

            //Validation
            if ($option['validation'] != 'none') {
                $validationString = '';
                switch ($option['validation']) {
                    case 'presenceof':
                        $validationString .= '        $this->validate(new \Phalcon\Mvc\Model\Validator\PresenceOf(' . "\n";
                        $validationLanguage = 'notempty';
                        break;
                    case 'email':
                        $validationString .= '        $this->validate(new \Phalcon\Mvc\Model\Validator\Email(' . "\n";
                        $validationLanguage = 'valid_email';
                        break;
                    case 'numericality':
                        $validationString .= '        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(' . "\n";
                        $validationLanguage = 'isnum';
                        break;
                    case 'uniqueness':
                        $validationString .= '        $this->validate(new \Phalcon\Mvc\Model\Validator\Uniqueness(' . "\n";
                        $validationLanguage = 'unique';
                        break;
                }
                $validationString .= '            [' . "\n";
                $validationString .= '                \'field\'  => \''. $option['property'] .'\',' . "\n";
                $validationString .= '                \'message\' => $this->lang->get(\'message_'. $option['property'] .'_'. $validationLanguage .'\')' . "\n";
                $validationString .= '            ]' . "\n";
                $validationString .= '        ));' . "\n\n";
                $validationContent .= $validationString;
            }

            //Dropzone
            if (isset($option['inputtype']) && $option['inputtype'] == 'dropzone') {
                $uploadSectionString = '';
                $uploadSectionString .= '        $this->'. $option['property'] .' = DI::getDefault()->get(\'config\')->'. strtolower($model) .'[\'directory\'] . date(\'Y\') . \'/\' . date(\'m\');' . "\n";
                // $uploadSectionString .= '        $this->useDynamicUpdate(true);' . "\n";
                $uploadSectionString .= '        $this->addBehavior(new \Phalcon\Behavior\Imageable([' . "\n";
                $uploadSectionString .= '            \'isoverwrite\' => false,' . "\n";
                $uploadSectionString .= '            \'sanitize\' => true,' . "\n";
                $uploadSectionString .= '            \'uploadPath\' => $this->'. $option['property'] .',' . "\n";
                $uploadSectionString .= '        ]));' . "\n\n";
                $uploadSectionContent .= $uploadSectionString;
            }
        }

        if ($validationContent != '') {
            $validationContent .= "\n" . '        return $this->validationHasFailed() != true;';
        }

        $template = str_replace('{{MODEL_NAME}}', $model, $template);
        $template = str_replace('{{BASE_MODEL}}', $basemodel, $template);
        $template = str_replace('{{TABLE_NAME}}', $table, $template);
        $template = str_replace('{{COLUMN_DEFINED}}', $columnDefineContent, $template);
        $template = str_replace('{{CONSTANT_DEFINED}}', $constantDefineContent, $template);
        $template = str_replace('{{CONSTANT_FUNCTION}}', $constantFunctionContent, $template);
        $template = str_replace('{{VALIDATION_CONTENT}}', $validationContent, $template);
        $template = str_replace('{{UPLOAD_SECTION}}', chop($uploadSectionContent, "\n"), $template);

        //Write file to model directory
        $filePath = '/models/'. $model .'.php';

        if ($this->filemanager->put($filePath, $template)) {
            $this->message .= 'MODEL saved to <code>'. $filePath .'</code> successfully. </br>';
        }
        // var_dump($template);
        // die;
    }

    private function generatecontroller($data, $module, $controller, $model, $recordPerPage)
    {
        $constantListContent = '';
        $uploadSectionContent = '';
        $formDataToModel = '';
        $modelToFormData = '';
        $searchInContent = '        $searchKeywordInData = [' . "\n";
        $filterContent = '';
        $filterParamsContent = '';
        $template = $this->filemanager->read($this->template['controller']['normal']);

        foreach ($data as $field => $option) {
            if (isset($option['filterable'])) {
                $filterOptType = (string) 'string';
                $defaultOptType = (string) '\'\'';
                if ($option['type'] == 'INTEGER') {
                    $filterOptType = (string) 'int';
                    $defaultOptType = (string) '0';
                }
                $filterContent .= '        $'. $option['property'] .' = ('. $filterOptType .') $this->request->getQuery(\''. $option['property'] .'\', null, '. $defaultOptType .');' . "\n";

                $filterParamsContent .= '                \''. $option['property'] .'\' => $'. $option['property'] .',' . "\n";
            }

            if (isset($option['searchable'])) {
                $searchInContent .= '            \'' . $option['property'] . '\',' . "\n";
            }

            if (!isset($option['exclude_ae'])) {
                $formDataToModel .= '                    \''. $option['property'] .'\' => $formData[\'f'. $option['property'] .'\'],' . "\n";
            }

            if (!isset($option['exclude_ae'])) {
                $modelToFormData .= '        $formData[\'f'. $option['property'] .'\'] = $my'. $controller .'->'. $option['property'] .';' . "\n";
            }

            //Dropzone
            if (isset($option['inputtype']) && $option['inputtype'] == 'dropzone') {
                $uploadSectionString = '';
                $uploadSectionString .= '    public function upload'. $option['property'] .'Action()' . "\n";
                $uploadSectionString .= '    {' . "\n";
                $uploadSectionString .= '        $jsondata = [];' . "\n";
                $uploadSectionString .= '        $success = false;' . "\n";
                $uploadSectionString .= '        $my'. $controller .' = new \Model\\'. $model .'();' . "\n";
                $uploadSectionString .= '        $upload = $my'. $controller .'->processUpload();' . "\n";
                $uploadSectionString .= '        if ($upload == $my'. $controller .'->isSuccessUpload()) {' . "\n";
                $uploadSectionString .= '            $jsondata = $my'. $controller .'->getInfo();' . "\n";
                $uploadSectionString .= '        }' . "\n";
                $uploadSectionString .= '        $this->view->setVars([' . "\n";
                $uploadSectionString .= '            \'jsondata\' => $jsondata,' . "\n";
                $uploadSectionString .= '        ]);' . "\n";
                $uploadSectionString .= '    }' . "\n";
                $uploadSectionContent .= $uploadSectionString;
            }

            //Constant list
            if (isset($option['constant']) && $option['constant'] != '') {
                $constantListString = '';
                $constantListString .= '            \''. $option['property'] .'List\' => \Model\\'. $model .'::get'. ucfirst($option['property']) .'List(),' . "\n";
                $constantListContent .= $constantListString;
            }
        }

        $searchInContent .= '        ];';

        $template = str_replace('{{MODULE_NAME}}', ucfirst($module), $template);
        $template = str_replace('{{CONTROLLER_NAME}}', $controller, $template);
        $template = str_replace('{{MODEL_NAME}}', $model, $template);
        $template = str_replace('{{RECORD_PER_PAGE}}', $recordPerPage, $template);
        $template = str_replace('{{SEARCH_KEYWORD_IN_DATA}}', $searchInContent, $template);
        $template = str_replace('{{CONTROLLER_URL}}', strtolower($controller), $template);
        $template = str_replace('{{ASSIGN_FORMDATA_TO_MODEL}}', chop($formDataToModel, "\n"), $template);
        $template = str_replace('{{ASSIGN_MODEL_TO_FORMDATA}}', chop($modelToFormData, "\n"), $template);
        $template = str_replace('{{FILTERABLE_QUERY_PARAMS}}', chop($filterContent, "\n"), $template);
        $template = str_replace('{{FILTERABLE_PARAMS}}', chop($filterParamsContent, "\n"), $template);
        $template = str_replace('{{UPLOAD_SECTION}}', $uploadSectionContent, $template);
        $template = str_replace('{{CONSTANT_LIST}}', chop($constantListContent, "\n"), $template);

        //Write file to controller directory
        $filePath = '/modules/admin/controllers/'. $controller .'Controller.php';

        if ($this->filemanager->put($filePath, $template)) {
            $this->message .= 'CONTROLLER saved to <code>'. $filePath .'</code> successfully. </br>';
        }

        // var_dump($template);
        // die;
    }

    private function generateview($data, $module, $controller, $model)
    {
        $colspan = 2;
        $addInputFunction = '';
        $uploadSectionContent = '';
        $searchInLabel = '';
        $thContent = '';
        $tdContent = '';
        $addInputContent = '';
        $templateIndex = $this->filemanager->read($this->template['view']['normal']['index']);
        $templateAdd = $this->filemanager->read($this->template['view']['normal']['add']);
        $templateEdit = $this->filemanager->read($this->template['view']['normal']['edit']);

        foreach ($data as $field => $option) {
            if (isset($option['searchable'])) {
                $searchInLabel .= ''. ucfirst($option['property']) .', ';
            }

            //Table head sortable
            if (!isset($option['exclude_i'])) {
                if (isset($option['sortable'])) {
                    $thContent .= '                                <th>' . "\n";
                    $thContent .= '                                    <a href="{{ config.app_baseUri }}admin/'. strtolower($controller) .'?orderby='. $option['property'] .'&ordertype={% if formData[\'orderType\']|lower == \'desc\'%}asc{% else %}desc{% endif %}{% if formData[\'conditions\'][\'keyword\'] != \'\' %}&keyword={{ formData[\'conditions\'][\'keyword\'] }}{% endif %}">' . "\n";
                    $thContent .= '                                        '. $option['label'] .'' . "\n";
                    $thContent .= '                                    </a>' . "\n";
                    $thContent .= '                                </th>' . "\n";
                } else {
                    $thContent .= '                                <th>'. $option['label'] .'</th>' . "\n";
                }
            }

            //table column content
            if (!isset($option['exclude_i'])) {
                $colspan++;

                if (isset($option['constant']) && $option['constant'] != '') {
                    $tdContent .= '                                <td><span class="label label-primary">{{ '. strtolower($controller) .'.get'. ucfirst($option['property']) .'Name()|upper }}</span></td>' . "\n";
                } else {
                    $tdContent .= '                                <td>{{ '. strtolower($controller) .'.'. $option['property'] .' }}</td>' . "\n";
                }
            }

            if (!isset($option['exclude_ae'])) {
                //Add form input type
                switch ($option['inputtype']) {
                    case 'none':
                        if (isset($option['constant']) && $option['constant'] != '') {
                            $addInputContent .= '                <div class="form-group" >' . "\n";
                            $addInputContent .= '                    <label class="control-label">'. ucfirst($option['property']) .'</label>' . "\n";
                            $addInputContent .= '                    <select name="f'. $option['property'] .'" class="form-control input-sm">' . "\n";
                            $addInputContent .= '                        <option value="0">- - - -</option>' . "\n";
                            $addInputContent .= '                        {% for '. $option['property'] .' in '. $option['property'] .'List %}' . "\n";
                            $addInputContent .= '                            <option value="{{ '. $option['property'] .'[\'value\'] }}" {% if formData[\'f'. $option['property'] .'\'] is defined and formData[\'f'. $option['property'] .'\'] == '. $option['property'] .'[\'value\'] %}selected="selected"{% endif %}>{{ '. $option['property'] .'[\'name\'] }}</option>' . "\n";
                            $addInputContent .= '                        {% endfor %}' . "\n";
                            $addInputContent .= '                    </select>' . "\n";
                            $addInputContent .= '                </div>' . "\n";
                        } else {
                            $addInputContent .= '                <div class="form-group" >' . "\n";
                            $addInputContent .= '                    <label class="control-label">'. ucfirst($option['property']) .'</label>' . "\n";
                            $addInputContent .= '                    <input type="text" name="f'. $option['property'] .'" value="{% if formData[\'f'. $option['property'] .'\'] is defined %}{{ formData[\'f'. $option['property'] .'\'] }}{% endif %}" class="form-control input-sm" />' . "\n";
                            $addInputContent .= '                </div>' . "\n";
                        }
                        break;
                    case 'dropzone':
                        $addInputContent .= '                <div class="form-group">' . "\n";
                        $addInputContent .= '                    <div id="upload'. ucfirst($option['property']) .'" class="dropzone"></div>' . "\n";
                        $addInputContent .= '                    <input type="hidden" name="f'. $option['property'] .'" value="{% if formData[\'f'. $option['property'] .'\'] is defined %}{{ formData[\'f'. $option['property'] .'\'] }}{% endif %}" id="upload'. ucfirst($option['property']) .'Input"/>' . "\n";
                        $addInputContent .= '                </div>' . "\n";

                        $addInputFunction .= '<script type="text/javascript">' . "\n";
                        $addInputFunction .= '    $(document).ready(function() {' . "\n";
                        $addInputFunction .= '        Dropzone.autoDiscover = false;' . "\n";
                        $addInputFunction .= '        $(\'div#upload'. ucfirst($option['property']) .'\').dropzone({' . "\n";
                        $addInputFunction .= '            url: root_url + \'/admin/'. strtolower($controller) .'/upload'. $option['property'] .'\',' . "\n";
                        $addInputFunction .= '            paramName: \'f'. $option['property'] .'\',' . "\n";
                        $addInputFunction .= '            maxFileSize: 2,' . "\n";
                        $addInputFunction .= '            maxFiles: 1,' . "\n";
                        $addInputFunction .= '            init: function() {' . "\n";
                        $addInputFunction .= '                this.on("maxfilesexceeded", function(file){' . "\n";
                        $addInputFunction .= '                    toastr.error("Cannot upload more than 1 file!");' . "\n";
                        $addInputFunction .= '                });' . "\n";
                        $addInputFunction .= '                this.on("addedfile", function(file) {' . "\n";
                        $addInputFunction .= '                    var removeButton = Dropzone.createElement("<button class=\'btn btn-default btn-sm\'><i class=\'fa fa-times\'></i></button>");' . "\n";
                        $addInputFunction .= '                    var _this = this;' . "\n";
                        $addInputFunction .= '                    removeButton.addEventListener("click", function(e) {' . "\n";
                        $addInputFunction .= '                        e.preventDefault();' . "\n";
                        $addInputFunction .= '                        e.stopPropagation();' . "\n";
                        $addInputFunction .= '                        _this.removeFile(file);' . "\n";
                        $addInputFunction .= '                    });' . "\n";
                        $addInputFunction .= '                    file.previewElement.appendChild(removeButton);' . "\n";
                        $addInputFunction .= '                });' . "\n";
                        $addInputFunction .= '                this.on("success", function(file, response) {' . "\n";
                        $addInputFunction .= '                    var path = response.jsondata.f'. $option['property'] .'.path' . "\n";
                        $addInputFunction .= '                    $("#upload'. ucfirst($option['property']) .'Input").val(path.replace("/public", ""));' . "\n";
                        $addInputFunction .= '                    toastr.success("File upload OK!");' . "\n";
                        $addInputFunction .= '                });' . "\n";
                        $addInputFunction .= '            },' . "\n";
                        $addInputFunction .= '        });' . "\n";
                        $addInputFunction .= '    });' . "\n";
                        $addInputFunction .= '</script>' . "\n";

                        $this->message .= '<div style="padding: 20px;margin: 20px;background-color: whitesmoke;">';
                        $this->message .= '<h5>DROPZONE Setting ['. $option['property'] .']</h5>';
                        $this->message .= '<p>Open file <code>/conf/global.php</code> and paste this code below to <code>$setting</code> Array.</p>';
                        $this->message .= '<code>&nbsp;&nbsp;&nbsp;&nbsp;\''. strtolower($controller) .'\' => [ </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'directory\' => \'/public/uploads/'. strtolower($controller) .'/\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'minsize\'   =>  1000, </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'maxsize\'   =>  1000000, </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'mimes\'     =>  [ </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'image/gif\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'image/jpeg\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'image/png\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;], </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'extensions\'     =>  [ </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'gif\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'jpeg\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'jpg\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'png\', </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;], </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'sanitize\' => true, </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'hash\'     => \'md5\' </br>';
                        $this->message .= '&nbsp;&nbsp;&nbsp;&nbsp;]</code> </br>';
                        $this->message .= '</div>';
                        break;
                }
            }
        }

        $templateIndex = str_replace('{{CONTROLLER_URL}}', strtolower($controller), $templateIndex);
        $templateIndex = str_replace('{{CONTROLLER_NAME}}', $controller, $templateIndex);
        $templateIndex = str_replace('{{SEARCH_IN_LABEL}}', $searchInLabel, $templateIndex);
        $templateIndex = str_replace('{{TH_PROPERTY}}', chop($thContent, "\n"), $templateIndex);
        $templateIndex = str_replace('{{TD_PROPERTY}}', chop($tdContent, "\n"), $templateIndex);
        $templateIndex = str_replace('{{TF_COLSPAN}}', $colspan, $templateIndex);

        $templateAdd = str_replace('{{CONTROLLER_URL}}', strtolower($controller), $templateAdd);
        $templateAdd = str_replace('{{INPUT_PROPERTY}}', chop($addInputContent, "\n"), $templateAdd);
        $templateAdd = str_replace('{{INPUT_FUNCTION}}', chop($addInputFunction, "\n"), $templateAdd);

        $templateEdit = str_replace('{{CONTROLLER_URL}}', strtolower($controller), $templateEdit);
        $templateEdit = str_replace('{{INPUT_PROPERTY}}', chop($addInputContent, "\n"), $templateEdit);
        $templateEdit = str_replace('{{INPUT_FUNCTION}}', chop($addInputFunction, "\n"), $templateEdit);

        //Write file to view directory
        $message = '';
        $filePath = '/modules/admin/views/'. strtolower($controller) .'/';

        if ($this->filemanager->put($filePath . 'index.volt', $templateIndex)) {
            $message .= 'VIEW_INDEX saved to <code>'. $filePath . 'index.volt' .'</code> successfully. </br>';
        }

        if ($this->filemanager->put($filePath . 'add.volt', $templateAdd)) {
            $message .= 'VIEW_ADD saved to <code>'. $filePath . 'add.volt' .'</code> successfully. </br>';
        }

        if ($this->filemanager->put($filePath . 'edit.volt', $templateEdit)) {
            $message .= 'VIEW_EDIT saved to <code>'. $filePath . 'edit.volt' .'</code> successfully. </br>';
        }

        $this->message .= $message;

        // var_dump($templateIndex);
        // die;
    }

    private function generatelanguage($data, $controller)
    {
        $constantLabelContent = '';
        $validationMessageContent = '';
        $templateLang = $this->filemanager->read($this->template['lang']);

        foreach ($data as $field => $option) {
            //Validation
            if ($option['validation'] != 'none') {
                switch ($option['validation']) {
                    case 'presenceof':
                        $validationMessageContent .= '    \'message_'. $option['property'] .'_notempty\' => \'The '. ucfirst($option['property']) .' is required.\',' . "\n";
                        break;
                    case 'email':
                        $validationMessageContent .= '    \'message_'. $option['property'] .'_valid_email\' => \'The '. ucfirst($option['property']) .' not a valid email.\',' . "\n";
                        break;
                    case 'numericality':
                        $validationMessageContent .= '    \'message_'. $option['property'] .'_isnum\' => \'The '. ucfirst($option['property']) .' must be a numericality.\',' . "\n";
                        break;
                    case 'uniqueness':
                        $validationMessageContent .= '    \'message_'. $option['property'] .'_unique\' => \'The '. ucfirst($option['property']) .' already existed.\',' . "\n";
                        break;
                }
            }

            //Constantable
            if (isset($option['constant']) && $option['constant'] != '') {
                $constArray = explode(',', $option['constant']);
                // gen const value at top of a file
                for ($i = 0; $i < count($constArray); $i++) {
                    $constantString = '';

                    $strArray = explode(':', $constArray[$i]);
                    $constKey = $strArray[0];
                    $constValue = $strArray[1];
                    $constName = $strArray[2];

                    $constantString = '    \'label_'. strtolower($constKey) .'\' => \''. ucfirst($constName) .'\',' . "\n";
                    $constantLabelContent .= $constantString;
                }
            }
        }

        $templateLang = str_replace('{{CONTROLLER_NAME}}', $controller, $templateLang);
        $templateLang = str_replace('{{MESSAGE_VALIDATION}}', $validationMessageContent, $templateLang);
        $templateLang = str_replace('{{CONSTANT_LABEL}}', chop($constantLabelContent, "\n"), $templateLang);

        $fileLanguagePath = '/language/en/admin/'. strtolower($controller) .'.php';
        if ($this->filemanager->put($fileLanguagePath, $templateLang)) {
            $this->message .= 'LANGUAGE EN saved to <code>'. $fileLanguagePath .'</code>. </br>';
        }
    }
}