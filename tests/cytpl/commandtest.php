<?php

use cyclone\tpl;

class CyTpl_CommandTest extends Kohana_Unittest_TestCase {

    /**
     * @dataProvider providerValidate
     */
    public function testValidate($name, $descr, $args, $should_fail) {
        $failed = FALSE;
        try {
            new tpl\Command($name, $descr, $args);
        } catch (tpl\CommandException $ex) {
            $failed = TRUE;
        }
        $this->assertEquals($should_fail, $failed);
    }

    public function providerValidate() {
        return array(
            array('c', array(), array(), TRUE),
            array('c', array('callback' => 0), array(), TRUE),
            array('c', array('callback' => 0, 'params' => array(0)), array(), TRUE),
            array('c', array('callback' => 0, 'params' => array(0, 'asd'))
                , array(1, 'asd' => 2), FALSE)
        );
    }

    public static function mockCallback(array $params) {
        return $params[0];
    }

    public function testInvokeCallback() {
        $command = new tpl\Command('c', array(
            'callback' => array('CyTpl_CommandTest', 'mockCallback'),
            'params' => array(0)
        ), array('test'));
        $this->assertEquals('test', $command->invoke());
    }

    public function testInvokeLambda() {
        $command = new tpl\Command('c', array(
            'callback' => function($params){
                return $params[0];
            },
            'params' => array(0)
        ), array('test'));
        $this->assertEquals('test', $command->invoke());
    }
    
}