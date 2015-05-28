<?php
/**
 * \Controller\Common\IndexController.php
 * IndexController.php
 *
 * Index Controller for front-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Controller\Common;

use Fly\BaseController as FlyController;

class IndexController extends FlyController
{
    protected $recordPerPage = 10;

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        // Search keyword in specified field model
        $searchKeywordInData = [
            'title',
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'datecreated');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'desc');
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

        $this->tag->prependTitle('Home ');
        $this->view->setVars([
            'formData' => $formData,
            'myPost' => $myPost,
            'recordPerPage' => $this->recordPerPage,
            'paginator' => $myPost,
            'paginateUrl' => $paginateUrl
        ]);
    }

    public function detailAction()
    {
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $day = $this->dispatcher->getParam('day');
        $slug = $this->dispatcher->getParam('slug');
        $remixSlug = $year . '/' . $month . '/' . $day . $slug;

        $myPost = \Model\Post::findFirst([
            'slug = :s: AND status = :status:',
            'bind' => [
                's' => $remixSlug,
                'status' => \Model\Post::STATUS_ENABLE
            ]
        ]);

        $parsedown = new \Fly\Parsedown();
        $myPost->content = $parsedown->text($myPost->content);

        // //Get feature post
        // $formData['columns'] = '*';
        // $formData['conditions'] = [
        //     'filterBy' => [
        //         'pcid' => $myPost->pcid
        //     ]
        // ];
        // $formData['orderBy'] = 'datecreated';
        // $formData['orderType'] = 'desc';
        // $myFeaturePost = \Model\Post::getPostList($formData, 4, 1);

        $this->tag->prependTitle($myPost->title);
        $this->view->setVars([
            'myPost' => $myPost,
            'categoryList' => \Model\PostCategory::find(),
            // 'featurePost' => $myFeaturePost
        ]);
    }
}
