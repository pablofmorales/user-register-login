<?php

namespace Tests\Unit;

use Mockery as m;

class SignUpTest extends \PHPUnit_Framework_Testcase
{

    /**
     * @dataProvider providerExpectionSaving
     * @expectedException \Exception
     */
    public function testTryingToSaveAndSaveFail($expected, $view, $validator, $user, $request)
    {
        $users = new \Controllers\Users($view, $validator, $user);
        $response = $users->create($request);
    }

    /**
     * @dataProvider providerWithoutUser
     */
    public function testWithoutRequestShouldReturnEmptyView($expected, $view, $validator, $user, $request)
    {
        $users = new \Controllers\Users($view, $validator, $user);

        $response = $users->create($request);

        $this->assertEquals($expected, $response);
    }

    /**
     * @dataProvider providerValidUser
     */
    public function testSendingValidUserShouldSave($expected, $view, $validator, $user, $request)
    {
        $users = new \Controllers\Users($view, $validator, $user);
        $response = $users->create($request);
        $this->assertEquals($expected, $response);
    }

    /**
     * @dataProvider providerInValidUser
     */
    public function testSendingInvalidDataShouldReturnErrors($expected, $view, $validator, $user, $request)
    {
        $users = new \Controllers\Users($view, $validator, $user);
        $response = $users->create($request);
        $this->assertEquals($expected, $response);
    }

    public function providerExpectionSaving()
    {
        $expected = ['errors' => 'Unexpected Error'];
        $view = m::mock('Twig\Twig');
        $view->shouldReceive('render')
            ->andReturn($expected);

        $validator = m::mock('\Models\Validators\Users');
        $validator->shouldReceive('isValid')
            ->andReturn(true);

        $params = m::mock('Symfony\Component\HttpFoundation\ParameterBa');
        $params->shouldReceive('all')
            ->andReturn([['testParam' =>  1]]);

        $request = m::mock('Symfony\Component\HttpFoundation\Request');
        $request->request = $params;

        $user = m::mock('\Models\Users');
        $user->shouldReceive('save')
            ->andThrow(new \Exception('Unexpected Error'));

        return [[$expected, $view, $validator, $user, $request]];
    }
    public function providerInValidUser()
    {
        $expected = ['success' => false];
        $view = m::mock('Twig\Twig');
        $view->shouldReceive('render')
            ->andReturn($expected);

        $validator = m::mock('\Models\Validators\Users');
        $validator->shouldReceive('isValid')
            ->andReturn(false)
            ->shouldReceive('getErrors')
            ->andReturn(['emial' => 'sad']);

        $params = m::mock('Symfony\Component\HttpFoundation\ParameterBa');
        $params->shouldReceive('all')
            ->andReturn(['password' =>  'sdasda']);

        $request = m::mock('Symfony\Component\HttpFoundation\Request');
        $request->request = $params;


        $conn = m::mock('Doctrine\DBAL\Connection');
        $user = new \Models\Users($conn);

        return [[$expected, $view, $validator, $user, $request]];
    }

    public function providerValidUser()
    {
        $expected = ['success' => true];
        $view = m::mock('\Twig\Twig');
        $view->shouldReceive('render')
            ->andReturn($expected);

        $conn = m::mock('Doctrine\DBAL\Connection');
        $conn->shouldReceive('insert')
            ->andReturn(1);

        $user = new \Models\Users($conn);;

        $validator = m::mock('\Models\Validators\Users');
        $validator->shouldReceive('isValid')
            ->andReturn(true);

        $params = m::mock('\Symfony\Component\HttpFoundation\ParameterBa');
        $params->shouldReceive('all')
            ->andReturn(['password' => 'sdasda']);

        $request = m::mock('\Symfony\Component\HttpFoundation\Request');
        $request->request = $params;

        return [[$expected, $view, $validator, $user, $request]];
    }

    public function providerWithoutUser()
    {
        $expected = ['success' => true];
        $view = m::mock('Twig\Twig');
        $view->shouldReceive('render')
            ->andReturn($expected);
        $user = m::mock('Models\Users');
        $validator = m::mock('Models\Validators\Users');

        $params = m::mock('Symfony\Component\HttpFoundation\ParameterBa');
        $params->shouldReceive('all')
            ->andReturn([]);

        $request = m::mock('Symfony\Component\HttpFoundation\Request');
        $request->request = $params;

        return [[$expected, $view, $validator, $user, $request]];
    }

}
