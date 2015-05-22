<?php
namespace Controller\Admin;

use Fly\BaseController as FlyController;

class UserController extends FlyController
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
                        $myUser = \Model\User::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                        // If fail stop a transaction
                        if ($myUser == false) {
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
            'email',
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $id = (int) $this->request->getQuery('id', null, 0);
        $password = (string) $this->request->getQuery('password', null, '');
        $role = (int) $this->request->getQuery('role', null, 0);
        $datecreated = (int) $this->request->getQuery('datecreated', null, 0);
        $datemodified = (int) $this->request->getQuery('datemodified', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'id' => $id,
                'password' => $password,
                'role' => $role,
                'datecreated' => $datecreated,
                'datemodified' => $datemodified,
            ]
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $myUser = \Model\User::getUserList($formData, $this->recordPerPage, $page);

        $this->tag->prependTitle($this->lang->get('title_index'));
        $this->breadcrumb->add($this->lang->get('title_index'), 'admin/user');
        $this->breadcrumb->add(''. $this->lang->get('title_listing') .' ('. $myUser->total_items .')', 'admin/user');
        $this->view->setVars([
            'redirectUrl' => base64_encode(urlencode($currentUrl)),
            'formData' => $formData,
            'myUser' => $myUser,
            'recordPerPage' => $this->recordPerPage,
            'breadcrumb' => $this->breadcrumb->generate(),
            'paginator' => $myUser,
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

                $myUser = new \Model\User();
                $myUser->assign([
                    'name' => $formData['fname'],
                    'email' => $formData['femail'],
                    'password' => $this->security->hash($formData['fpassword']),
                    'role' => $formData['frole'],
                    'avatar' => $formData['favatar'],
                    'status' => $formData['fstatus'],
                ]);

                if ($myUser->create()) {
                    $this->flash->success($this->lang->get('message_add_success'));
                } else {
                    foreach ($myUser->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_add'));
        $this->breadcrumb->add($this->lang->get('title_add'), 'admin/user');
        $this->breadcrumb->add($this->lang->get('title_adding'), 'admin/user/add');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\User::getStatusList(),
            'roleList' => \Model\User::getRoleList(),
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        $myUser = \Model\User::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);
        $formData['fname'] = $myUser->name;
        $formData['femail'] = $myUser->email;
        $formData['frole'] = $myUser->role;
        $formData['favatar'] = $myUser->avatar;
        $formData['fstatus'] = $myUser->status;

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myUser->assign([
                    'name' => $formData['fname'],
                    'email' => $formData['femail'],
                    'role' => $formData['frole'],
                    'avatar' => $formData['favatar'],
                    'status' => $formData['fstatus'],
                ]);

                if ($myUser->update()) {
                    $this->flash->success(str_replace('###id###', $myUser->id, $this->lang->get('message_edit_success')));
                } else {
                    foreach ($myUser->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_edit'));
        $this->breadcrumb->add($this->lang->get('title_edit'), 'admin/user');
        $this->breadcrumb->add($this->lang->get('title_editing'), 'admin/user/edit');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\User::getStatusList(),
            'roleList' => \Model\User::getRoleList(),
        ]);
    }

    public function deleteAction()
    {
        $message = '';
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));


        // $myUser = \Model\User::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        // if ($myUser) {
        //     $this->flash->success(str_replace('###id###', $id, $this->lang->get('message_delete_success')));
        // } else {
        //     foreach ($myUser->getMessages() as $msg) {
        //         $message .= $msg->getMessage() . "</br>";
        //     }
        //     $this->flashSession->error($message);
        // }

        return $this->response->redirect($redirectUrl, true);
    }

    public function uploadavatarAction()
    {
        $jsondata = [];
        $success = false;
        $myUser = new \Model\User();
        $upload = $myUser->processUpload();
        if ($upload == $myUser->isSuccessUpload()) {
            $jsondata = $myUser->getInfo();
        }
        $this->view->setVars([
            'jsondata' => $jsondata,
        ]);
    }

}
