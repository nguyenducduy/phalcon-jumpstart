<?php
namespace Controller\Admin;

use Fly\BaseController as FlyController;

class LogsController extends FlyController
{
    protected $recordPerPage = 30;

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
                        $myLogs = \Model\Logs::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                        // If fail stop a transaction
                        if ($myLogs == false) {
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
        $searchKeywordInData = [
            'name',
            'content',
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $type = (int) $this->request->getQuery('type', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'type' => $type,
            ]
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $myLogs = \Model\Logs::getLogsList($formData, $this->recordPerPage, $page);

        $this->tag->prependTitle($this->lang->get('title_index'));
        $this->breadcrumb->add($this->lang->get('title_index'), 'admin/logs');
        $this->breadcrumb->add(''. $this->lang->get('title_listing') .' ('. $myLogs->total_items .')', 'admin/logs');
        $this->view->setVars([
            'redirectUrl' => base64_encode(urlencode($currentUrl)),
            'formData' => $formData,
            'myLogs' => $myLogs,
            'recordPerPage' => $this->recordPerPage,
            'breadcrumb' => $this->breadcrumb->generate(),
            'paginator' => $myLogs,
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

                $myLogs = new \Model\Logs();
                $myLogs->assign([
                    'name' => $formData['fname'],
                    'type' => $formData['ftype'],
                    'content' => $formData['fcontent'],
                    'datecreated' => $formData['fdatecreated'],
                ]);

                if ($myLogs->create()) {
                    $this->flash->success($this->lang->get('message_add_success'));
                } else {
                    foreach ($myLogs->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_add'));
        $this->breadcrumb->add($this->lang->get('title_add'), 'admin/logs');
        $this->breadcrumb->add($this->lang->get('title_adding'), 'admin/logs/add');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'typeList' => \Model\Logs::getTypeList(),
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        $myLogs = \Model\Logs::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);
        $formData['fname'] = $myLogs->name;
        $formData['ftype'] = $myLogs->type;
        $formData['fcontent'] = $myLogs->content;
        $formData['fdatecreated'] = $myLogs->datecreated;

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myLogs->assign([
                    'name' => $formData['fname'],
                    'type' => $formData['ftype'],
                    'content' => $formData['fcontent'],
                    'datecreated' => $formData['fdatecreated'],
                ]);

                if ($myLogs->update()) {
                    $this->flash->success(str_replace('###id###', $myLogs->id, $this->lang->get('message_edit_success')));
                } else {
                    foreach ($myLogs->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_edit'));
        $this->breadcrumb->add($this->lang->get('title_edit'), 'admin/logs');
        $this->breadcrumb->add($this->lang->get('title_editing'), 'admin/logs/edit');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'typeList' => \Model\Logs::getTypeList(),
        ]);
    }

    public function deleteAction()
    {
        $message = '';
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));

        $myLogs = \Model\Logs::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myLogs) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->get('message_delete_success')));
        } else {
            foreach ($myLogs->getMessages() as $msg) {
                $message .= $msg->getMessage() . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect($redirectUrl);
    }


}
