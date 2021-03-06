<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Tests\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelReferenceType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class ModelReferenceTypeTest extends TypeTestCase
{
    private $modelManager;

    protected function setUp()
    {
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $this->markTestSkipped('Testing ancient versions would be more complicated.');
        }

        $this->modelManager = $this->prophesize('Sonata\AdminBundle\Model\ModelManagerInterface');

        parent::setUp();
    }

    public function testSubmitValidData()
    {
        $formData = 42;

        $form = $this->factory->create(
            'Sonata\AdminBundle\Form\Type\ModelReferenceType',
            null,
            array(
                'model_manager' => $this->modelManager->reveal(),
                'class' => 'My\Entity',
            )
        );
        $this->modelManager->find('My\Entity', 42)->shouldBeCalled();
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
    }

    protected function getExtensions()
    {
        return array(
            new PreloadedExtension(array(
                new ModelReferenceType($this->modelManager->reveal()),
            ), array()),
        );
    }
}
