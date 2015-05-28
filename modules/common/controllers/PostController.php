<?php
namespace Controller\Common;

use Fly\BaseController as FlyController;

class PostController extends FlyController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {

    }

    public function showAction()
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
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'filterBy' => [
                'pcid' => (string) $myPost->pcid
            ]
        ];
        $formData['orderBy'] = 'datecreated';
        $formData['orderType'] = 'desc';
        $myFeaturePost = \Model\Post::getPostList($formData, 4, 1);

        $this->tag->prependTitle($myPost->title);
        $this->view->setVars([
            'myPost' => $myPost,
            'categoryList' => \Model\PostCategory::find(),
            'featurePost' => $myFeaturePost
        ]);
    }
}
