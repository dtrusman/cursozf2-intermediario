<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use SONUser\Form\User as FormUser;

class IndexController extends AbstractActionController
{

    public function registerAction()
    {
        $form = new FormUser;
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $service = $this->getServiceLocator()->get('SONUser\Service\User');
                if($service->insert($request->getPost()->toArray()))
                {
                    $fm = $this->flashMessenger()
                               ->setNamespace('SONUser')
                               ->addMessage("Usuário cadastrado com sucesso");
                }

                return $this->redirect()->toRoute('sonuser-register');
            }
        }

        $messages = $this->flashMessenger()
                         ->setNamespace('SONUser')
                         ->getMessages();

        return new ViewModel(array('form' => $form, 'messages' => $messages));
    }

    public function activateAction()
    {
        $activationKey = $this->params()->fromRoute('key');

        $userService = $this->getServiceLocator()->get('SONUser\Service\User');
        $result = $userService->activate($activationKey);

        if($result)
        {
            return new ViewModel(array('user' => $result));
        } else {
            return new ViewModel();
        }
    }

    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);

        if(empty($data['password']))
            unset($data['password']);

        (new Hydrator\ClassMethods())->hydrate($data, $entity);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}
