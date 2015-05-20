<?php
namespace Controller\{{MODULE_NAME}};

use Fly\BaseController as FlyController;

class {{CONTROLLER_NAME}}Controller extends FlyController
{
    protected $recordPerPage = {{RECORD_PER_PAGE}};

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $currentUrl = $this->getCurrentUrl();
        $formData = $jsonData = [];

        if ($this->request->hasPost('fsubmitbulk')) {
            $bulkid = $this->request->getPost('fbulkid', null, []);

            if (empty($bulkid)) {
                $this->flash->warning($this->lang->get('message_no_bulk_selected'));
            } else {
                $formData['fbulkid'] = $bulkid;

                if ($this->request->getPost('fbulkaction') == 'delete') {
                    $deletearr = $bulkid;

                    // Start a transaction
                    $this->db->begin();
                    $successId = [];

                    foreach ($deletearr as $deleteid) {
                        $my{{CONTROLLER_NAME}} = \Model\{{MODEL_NAME}}::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                        // If fail stop a transaction
                        if ($my{{CONTROLLER_NAME}} == false) {
                            $this->db->rollback();
                            return;
                        } else {
                            $successId[] = $deleteid;
                        }
                    }
                    // Commit a transaction
                    if ($this->db->commit() == true) {
                        $this->flash->success(str_replace('###idlist###', implode(', ', $successId), $this->lang->get('message_bulk_delete_success')));

                        $formData['fbulkid'] = null;
                    } else {
                        $this->flash->error($this->lang->get('message_bulk_delete_fail'));
                    }
                } else {
                    $this->flash->warning($this->lang->get('message_no_bulk_action'));
                }
            }
        }

        // Search keyword in specified field model
{{SEARCH_KEYWORD_IN_DATA}}
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
{{FILTERABLE_QUERY_PARAMS}}
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
{{FILTERABLE_PARAMS}}
            ]
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $my{{CONTROLLER_NAME}} = \Model\{{MODEL_NAME}}::get{{MODEL_NAME}}List($formData, $this->recordPerPage, $page);

        $this->tag->prependTitle($this->lang->get('title_index'));
        $this->breadcrumb->add($this->lang->get('title_index'), 'admin/{{CONTROLLER_URL}}');
        $this->breadcrumb->add(''. $this->lang->get('title_listing') .' ('. $my{{CONTROLLER_NAME}}->total_items .')', 'admin/{{CONTROLLER_URL}}');
        $this->view->setVars([
            'redirectUrl' => base64_encode(urlencode($currentUrl)),
            'formData' => $formData,
            'my{{CONTROLLER_NAME}}' => $my{{CONTROLLER_NAME}},
            'recordPerPage' => $this->recordPerPage,
            'breadcrumb' => $this->breadcrumb->generate(),
            'paginator' => $my{{CONTROLLER_NAME}},
            'paginateUrl' => $paginateUrl
        ]);
    }

    public function addAction()
    {
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $my{{CONTROLLER_NAME}} = new \Model\{{MODEL_NAME}}();
                $my{{CONTROLLER_NAME}}->assign([
{{ASSIGN_FORMDATA_TO_MODEL}}
                ]);

                if ($my{{CONTROLLER_NAME}}->create()) {
                    $this->flash->success($this->lang->get('message_add_success'));
                } else {
                    foreach ($my{{CONTROLLER_NAME}}->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_add'));
        $this->breadcrumb->add($this->lang->get('title_add'), 'admin/{{CONTROLLER_URL}}');
        $this->breadcrumb->add($this->lang->get('title_adding'), 'admin/{{CONTROLLER_URL}}/add');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
{{CONSTANT_LIST}}
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        $my{{CONTROLLER_NAME}} = \Model\{{MODEL_NAME}}::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);
{{ASSIGN_MODEL_TO_FORMDATA}}

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $my{{CONTROLLER_NAME}}->assign([
{{ASSIGN_FORMDATA_TO_MODEL}}
                ]);

                if ($my{{CONTROLLER_NAME}}->update()) {
                    $this->flash->success(str_replace('###id###', $my{{CONTROLLER_NAME}}->id, $this->lang->get('message_edit_success')));
                } else {
                    foreach ($my{{CONTROLLER_NAME}}->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_edit'));
        $this->breadcrumb->add($this->lang->get('title_edit'), 'admin/{{CONTROLLER_URL}}');
        $this->breadcrumb->add($this->lang->get('title_editing'), 'admin/{{CONTROLLER_URL}}/edit');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
{{CONSTANT_LIST}}
        ]);
    }

    public function deleteAction()
    {
        $message = '';
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));

        $my{{CONTROLLER_NAME}} = \Model\{{MODEL_NAME}}::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($my{{CONTROLLER_NAME}}) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->get('message_delete_success')));
        } else {
            foreach ($my{{CONTROLLER_NAME}}->getMessages() as $msg) {
                $message .= $msg->getMessage() . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect($redirectUrl);
    }

{{UPLOAD_SECTION}}
}
