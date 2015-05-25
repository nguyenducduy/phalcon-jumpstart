<?php
namespace Controller\Admin;

use Fly\BaseController as FlyController;

class PostController extends FlyController
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
                        $myPost = \Model\Post::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                        // If fail stop a transaction
                        if ($myPost == false) {
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
            'title',
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $uid = (int) $this->request->getQuery('uid', null, 0);
        $pcid = (int) $this->request->getQuery('pcid', null, 0);
        $slug = (string) $this->request->getQuery('slug', null, '');
        $status = (int) $this->request->getQuery('status', null, 0);
        $type = (int) $this->request->getQuery('type', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'uid' => $uid,
                'pcid' => $pcid,
                'slug' => $slug,
                'status' => $status,
                'type' => $type,
            ]
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $myPost = \Model\Post::getPostList($formData, $this->recordPerPage, $page);

        $this->tag->prependTitle($this->lang->get('title_index'));
        $this->breadcrumb->add($this->lang->get('title_index'), 'admin/post');
        $this->breadcrumb->add(''. $this->lang->get('title_listing') .' ('. $myPost->total_items .')', 'admin/post');
        $this->view->setVars([
            'redirectUrl' => base64_encode(urlencode($currentUrl)),
            'formData' => $formData,
            'myPost' => $myPost,
            'recordPerPage' => $this->recordPerPage,
            'breadcrumb' => $this->breadcrumb->generate(),
            'paginator' => $myPost,
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

                $myPost = new \Model\Post();
                $myPost->assign([
                    'uid' => $formData['fuid'],
                    'pcid' => $formData['fpcid'],
                    'title' => $formData['ftitle'],
                    'summary' => $formData['fsummary'],
                    'content' => $formData['fcontent'],
                    'tags' => $formData['ftags'],
                    'cover' => $formData['fcover'],
                    'status' => $formData['fstatus'],
                    'type' => $formData['ftype'],
                ]);

                if ($myPost->create()) {
                    $this->flash->success($this->lang->get('message_add_success'));
                } else {
                    foreach ($myPost->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_add'));
        $this->breadcrumb->add($this->lang->get('title_add'), 'admin/post');
        $this->breadcrumb->add($this->lang->get('title_adding'), 'admin/post/add');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\Post::getStatusList(),
            'typeList' => \Model\Post::getTypeList(),
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));
        $formData = [];
        $message = '';

        $myPost = \Model\Post::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);
        $formData['fuid'] = $myPost->uid;
        $formData['fpcid'] = $myPost->pcid;
        $formData['ftitle'] = $myPost->title;
        $formData['fsummary'] = $myPost->summary;
        $formData['fcontent'] = $myPost->content;
        $formData['ftags'] = $myPost->tags;
        $formData['fcover'] = $myPost->cover;
        $formData['fstatus'] = $myPost->status;
        $formData['ftype'] = $myPost->type;

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myPost->assign([
                    'uid' => $formData['fuid'],
                    'pcid' => $formData['fpcid'],
                    'title' => $formData['ftitle'],
                    'summary' => $formData['fsummary'],
                    'content' => $formData['fcontent'],
                    'tags' => $formData['ftags'],
                    'cover' => $formData['fcover'],
                    'status' => $formData['fstatus'],
                    'type' => $formData['ftype'],
                ]);

                if ($myPost->update()) {
                    $this->flash->success(str_replace('###id###', $myPost->id, $this->lang->get('message_edit_success')));
                } else {
                    foreach ($myPost->getMessages() as $msg) {
                        $message .= $msg->getMessage() . "</br>";
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error('CSRF Detected.');
            }
        }

        $this->tag->prependTitle($this->lang->get('title_edit'));
        $this->breadcrumb->add($this->lang->get('title_edit'), 'admin/post');
        $this->breadcrumb->add($this->lang->get('title_editing'), 'admin/post/edit');
        $this->view->setVars([
            'redirectUrl' => $redirectUrl,
            'formData' => $formData,
            'breadcrumb' => $this->breadcrumb->generate(),
            'statusList' => \Model\Post::getStatusList(),
            'typeList' => \Model\Post::getTypeList(),
        ]);
    }

    public function deleteAction()
    {
        $message = '';
        $id = (int) $this->dispatcher->getParam('id');
        $redirectUrl = (string) urldecode(base64_decode($this->dispatcher->getParam('redirect')));

        $myPost = \Model\Post::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myPost) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->get('message_delete_success')));
        } else {
            foreach ($myPost->getMessages() as $msg) {
                $message .= $msg->getMessage() . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect($redirectUrl);
    }

    public function uploadcoverAction()
    {
        $jsondata = [];
        $success = false;
        $myPost = new \Model\Post();
        $upload = $myPost->processUpload();
        if ($upload == $myPost->isSuccessUpload()) {
            $jsondata = $myPost->getInfo();
        }
        $this->view->setVars([
            'jsondata' => $jsondata,
        ]);
    }

}
