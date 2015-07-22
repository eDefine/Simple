<?php

namespace Controller;

use Form\ProfileForm;

class ProfileController extends AbstractController
{
    public function viewAction()
    {
        if (!$this->getUser()) {
            $this->getContainer()->get('flashBag')->add('error', 'You must be logged to view this section.');
            return $this->redirect($this->getPath('home', 'index'));
        }

        return $this->renderView();
    }

    public function editAction()
    {
        $user = $this->getUser();

        if (!$user) {
            $this->getContainer()->get('flashBag')->add('error', 'You must be logged to view this section.');
            return $this->redirect($this->getPath('home', 'index'));
        }

        $profileForm = new ProfileForm('profile');
        $profileForm->bindData($user);

        if ($this->getParam('profile')) {
            $profileForm->bindRequest($this->getRequest());

            if ($picture = $user->getPicture()) {
                /** @var \Edefine\Framework\Storage\UploadedFile $picture */
                $pictureName = sprintf('%s.%s', uniqid(), $picture->getExtension());
                $picturePath = sprintf('%s/public/uploads/%d/%s', APP_DIR, $user->getId(), $pictureName);
                $picture->move($picturePath);
                $user->setPictureName($pictureName);
            }

            $this->getContainer()->get('manager')->save($user);

            $this->getContainer()->get('flashBag')->add('success', 'Profile has been saved.');
            return $this->redirect($this->getPath('profile', 'view'));
        }

        return $this->renderView([
            'profileForm' => $profileForm
        ]);
    }
}