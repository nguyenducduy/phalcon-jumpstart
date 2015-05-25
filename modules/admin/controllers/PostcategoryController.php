<?php
namespace Controller\Admin;

use Fly\BaseController as FlyController;

class PostcategoryController extends FlyController
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
                        $myPostcategory = \Model\PostCategory::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                        // If fail stop a transaction
                        if ($myPostcategory == false) {
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
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $slug = (string) $this->request->getQuery('slug', null, '');
        $parent = (int) $this->request->getQuery('parent', null, 0);
        $status = (int) $this->request->getQuery('status', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'slug' => $slug,
                'parent' => $parent,
                'status' => $status,
            ]
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $myPostcategory = \Model\PostCategory::getPostCategoryList($formData, $this->recordPerPage, $page);

        $this->tag->prependTitle($this->lang->get('title_index'));
        $this->breadcrumb->add($this->lang->get('title_index'), 'admin/postcategory');
        $this->breadcrumb->add(''. $this->lang->get('title_listing') .' ('. $myPostcategory->total_items .')', 'admin/postcategory');
        $this->view->setVars([
            'redirectUrl' => base64_encode(urlencode($currentUrl)),
            'formData' => $formData,
            'myPostcategory' => $myPostcategory,
            'recordPerPage' => $this->recordPerPage,
            'breadcrumb' => $this->breadcrumb->generate(),
            'paginator' => $myPostcategory,
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

                $myPostcategory = new \Model\PostCategory();
                $myPostcategory->assign([
                    'name' => $formData['fname'],
                    'description' => $formData['fdescription'],
                    'parent' => $formData['fparent'],
                    'status' => $formData['fstatus'],
                ]);

                if ($myPostcategory->create()) {
                    $this->flash->success($this->lang->get('message_add_success'));
                } else {
                    foreach ($myPostcategory->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_add'));
        $this->breadcrumb->add($this->lang->get('title_add'), 'admin/postcategory');
        $this->breadcrumb->add($this->lang->get('title_adding'), 'admin/postcategory/add');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\PostCategory::getStatusList(),
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        $myPostcategory = \Model\PostCategory::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);
        $formData['fname'] = $myPostcategory->name;
        $formData['fdescription'] = $myPostcategory->description;
        $formData['fparent'] = $myPostcategory->parent;
        $formData['fstatus'] = $myPostcategory->status;

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myPostcategory->assign([
                    'name' => $formData['fname'],
                    'description' => $formData['fdescription'],
                    'parent' => $formData['fparent'],
                    'status' => $formData['fstatus'],
                ]);

                if ($myPostcategory->update()) {
                    $this->flash->success(str_replace('###id###', $myPostcategory->id, $this->lang->get('message_edit_success')));
                } else {
                    foreach ($myPostcategory->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_edit'));
        $this->breadcrumb->add($this->lang->get('title_edit'), 'admin/postcategory');
        $this->breadcrumb->add($this->lang->get('title_editing'), 'admin/postcategory/edit');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\PostCategory::getStatusList(),
        ]);
    }

    public function deleteAction()
    {
        $message = '';
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));

        $myPostcategory = \Model\PostCategory::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myPostcategory) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->get('message_delete_success')));
        } else {
            foreach ($myPostcategory->getMessages() as $msg) {
                $message .= $msg->getMessage() . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect($redirectUrl);
    }


}
