<?php

namespace Admin\Controller;


use Application\Controller\BaseController as BaseController;
use Admin\Entity\Category as EntityCategory;
use Admin\Form\CategoryAddForm as InsertForm;
use Admin\Form\CategoryEditForm as UpdateForm;

class CategoryController extends BaseController
{
    public function indexAction()
    {
        $query = $this->getDoctrine()->createQuery('SELECT u FROM \Admin\Entity\Category u ORDER BY u.idCategory DESC');
        $rows = $query->getResult();

        $results = array(
            'categorys' => $rows,
        );

        return $results;
    }

    public function addAction()
    {
        $form = new InsertForm();

        $status = '';
        $message = '';

        $dc = $this->getDoctrine();
        $request = $this->getRequest();

        if ($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()) {
                $category = new EntityCategory();
                $category->exchangeArray($form->getData());
                $dc->persist($category);
                $dc->flush();
                $status = 'success';
                $message = 'Категория добавлена';
            } else {
                $status = 'error';
                $message = 'Category [Param Form] error';
            }
        }
        else{
            return array('form' => $form);
        }
        if ($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/category');
    }
    public function editAction()
    {
        $form = new UpdateForm();
        $status = $message = '';
        $dc = $this->getDoctrine();

        $idCategory = (int) $this->params()->fromRoute('id', 0);

        $category = $dc->find('\Admin\Entity\Category',$idCategory);

        if (empty($category)){
            $status = 'error';
            $message = 'Category [Param Form] error';
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
            return $this->redirect()->toRoute('admin/category');
        }

        $form->bind($category);

        $request = $this->getRequest();

        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);

            if($form->isValid()) {

                $dc->persist($category);
                $dc->flush();

                $status = 'success';
                $message = 'Категория обновлена';
            } else {
                $status = 'error';
                $message = 'Category [Param Form] error';
                foreach ($form->getInputFilter()->getInvalidInput() as $errors){
                    foreach ($errors->getMessages() as $error){
                        $message .= ' '.$error;
                    }
                }
            }

        }
        else{
            return array('form' => $form, 'idCategory' => $idCategory);
        }

        if ($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }

        return $this->redirect()->toRoute('admin/category');
    }

    public function removeAction()
    {
        $idCategory = (int) $this->params()->fromRoute('id', 0);
        $dc = $this->getDoctrine();

        $status = 'success';
        $message = 'Категория удалена';

        $category = $dc->find('\Admin\Entity\Category',$idCategory);

        try {
            $repository = $dc->getRepository('Admin\Entity\Category');
            $category = $repository->find($idCategory);
            $dc->remove($category);
            $dc->flush();
        }
        catch (\Exception $ex){
            $status = 'error';
            $message = 'Ошибка удаления записи: '. $ex->getMessage();
        }

        $this->flashMessenger()
             ->setNamespace($status)
             ->addMessage($message);

        return $this->redirect()->toRoute('admin/category');
    }
}
