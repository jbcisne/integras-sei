<?php
use PHPUnit\Framework\TestCase;
use Element;

class ElementTest extends TestCase
{
    /**
     * @test
     * */
    public function elementRecebeConteudoConstrutor()
    {
        $content = __LINE__;
        $elm = new Element($content);
        $this->assertEquals($content, $elm->content());
    }

    /**
     * @test
     * */
    public function elementConentAwasReturnSameContentTypeLikeAsInteger()
    {
        $content = __LINE__;
        $elm = new Element((integer) $content);
        $this->assertTrue(is_integer($elm->content()));
    }

    /**
     * @test
     * */
    public function elementConentAwasReturnSameContentTypeLikeAsNull()
    {
        $content = __LINE__;
        $elm = new Element(null);
        $this->assertTrue(is_null($elm->content()));
    }

    /**
     * @test
     * */

    public function elementConentAwasReturnSameContentTypeLikeAsBoolean()
    {
        $content = __LINE__;
        $elm = new Element(true);
        $this->assertTrue(is_bool($elm->content()));
    }

}
