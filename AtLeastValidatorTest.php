<?php
/**
 * AtLeastValidatorTest
 *
 * @author   Vladimir Galajda <galajda@gmail.com>
 *
 * Function getModelMock() inspired by Kevin Bradwick <kbradwick@gmail.com>
 */
class AtLeastValidatorTest extends CTestCase
{

    public function setupEmailANdPhone()
    {
        /** @var $model AtLeastValidatorTestModel */
        $model = $this->getModelMock(array(
            'rules' => array(
                array('phone', 'required'),
                array('email', 'email', 'allowEmpty' => false),
                array('email, phone', 'ext.atLeastValidator.atLeastValidator'),
            ),
        ));

        return $model;
    }

    public function testEmptyEmailAndPhone()
    {
        $model = $this->setupEmailANdPhone();
        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors('email'));
        $this->assertTrue($model->hasErrors('phone'));
    }

    public function testValidPhoneAndEmptyEmail()
    {
        $model = $this->setupEmailANdPhone();

        $model->phone = '12345';
        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors('email'));
        $this->assertFalse($model->hasErrors('phone'));
    }

    public function testValidEmailAndEmptyPhone()
    {
        $model = $this->setupEmailANdPhone();
        $model->email = 'test@test.com';
        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors('email'));
        $this->assertFalse($model->hasErrors('phone'));
    }

    public function testInvalidEmailAndEmptyPhone()
    {
        $model = $this->setupEmailANdPhone();

        $model->email = 'not_email_address';
        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors('email'));
        $this->assertTrue($model->hasErrors('phone'));
    }

    public function testInvalidEmailAndValidPhone()
    {
        $model = $this->setupEmailANdPhone();

        $model->email = 'not_email_address';
        $model->phone = '12345';
        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors('email'));
        $this->assertFalse($model->hasErrors('phone'));
    }

    public function testOneOfThree()
    {
        $model = $this->getModelMock(array(
            'rules' => array(
                array('email, im, phone', 'required'),
                array('email, im, phone', 'ext.atLeastValidator.atLeastValidator'),
            ),
        ));

        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors('email'));
        $this->assertTrue($model->hasErrors('im'));
        $this->assertTrue($model->hasErrors('phone'));

        $model->im = '1234567'; // Let's say ICQ number

        $this->assertTrue($model->validate());
        $this->assertFalse($model->hasErrors('email'));
        $this->assertFalse($model->hasErrors('im'));
        $this->assertFalse($model->hasErrors('phone'));
    }

    /**
     * Mocks up an object to test with
     *
     * @param array $params parameters to configure rule
     *
     * @return CFormModel
     */
    protected function getModelMock($params)
    {
        $rules = $params['rules'];

        $stub = $this->getMock('AtLeastValidatorTestModel', array('rules'));
        $stub->expects($this->any())
             ->method('rules')
             ->will($this->returnValue($rules));

        return $stub;
    }

}

class AtLeastValidatorTestModel extends CFormModel
{
    public $email = null;

    public $phone = null;

    public $im = null;

}