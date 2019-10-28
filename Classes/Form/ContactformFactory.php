<?php

namespace Digidennis\Libdim\Form;

use Neos\Flow\Annotations as Flow;
use Neos\Form\Core\Model\FormDefinition;

/**
 * Flow\Scope("singleton")
 */
class ContactformFactory extends \Neos\Form\Factory\AbstractFormFactory {

    /**
     * @param array $factorySpecificConfiguration
     * @param string $presetName
     * @return \Neos\Form\Core\Model\FormDefinition
     */
    public function build(array $factorySpecificConfiguration, $presetName) {
        $formConfiguration = $this->getPresetConfiguration($presetName);
        $form = new FormDefinition('contactForm', $formConfiguration);

        $page1 = $form->createPage('page1');

        $name = $page1->createElement('name', 'Neos.Form:SingleLineText');
        $name->setLabel('Name');
        $name->addValidator(new \Neos\Flow\Validation\Validator\NotEmptyValidator());

        $email = $page1->createElement('email', 'Neos.Form:SingleLineText');
        $email->setLabel('Email');
        $email->addValidator(new \Neos\Flow\Validation\Validator\NotEmptyValidator());
        $email->addValidator(new \Neos\Flow\Validation\Validator\EmailAddressValidator());

        $comments = $page1->createElement('message', 'Neos.Form:MultiLineText');
        $comments->setLabel('Message');
        $comments->addValidator(new \Neos\Flow\Validation\Validator\NotEmptyValidator());
        $comments->addValidator(new \Neos\Flow\Validation\Validator\StringLengthValidator(array('minimum' => 3)));

        /*$emailFinisher = new \Neos\Form\Finishers\EmailFinisher();
        $emailFinisher->setOptions(array(
            'templatePathAndFilename' => 'resource://Your.Package/Private/Templates/ContactForm/NotificationEmail.txt',
            'recipientAddress' => 'your@example.com',
            'senderAddress' => 'mailer@example.com',
            'replyToAddress' => '{email}',
            'carbonCopyAddress' => 'copy@example.com',
            'blindCarbonCopyAddress' => 'blindcopy@example.com',
            'subject' => 'Contact Request',
            'format' => \Neos\Form\Finishers\EmailFinisher::FORMAT_PLAINTEXT
        ));
        $form->addFinisher($emailFinisher);*/

        $redirectFinisher = new \Neos\Form\Finishers\RedirectFinisher();
        $redirectFinisher->setOptions(
            array('action' => 'index')
        );
        $form->addFinisher($redirectFinisher);

        return $form;
    }
}