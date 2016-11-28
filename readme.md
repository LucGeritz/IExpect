##I Expect

IExpect is a small unit test library for use in PHP. It's syntax is intuitive.

The assertion

*I expect 3 &ast; 2 to equal 6* 

can be put to the test with

`$I->expect(3*2)->equals(6);`

How nice is that..

`equals` can be other things as well.. `contains`, `hasKey` (talking arrays here) and more.     
     
More the negative type? How about `$I->expect(1+1)->not()->equals(3)`.       
  
While I'm still working at the documentation please refer to the source of the `Expectation` class what more it can do. In the `demo` folder you can find a very useful example as well.

You want more checks? It's easy to extend the Expectation class.


##I don't Expect..
..you to expect is has the same functionality like say *PHPUnit*. If it did it wouldn't be small now would it? So don't expect *mocks*, *stubs* and things like that, or being able to test *exceptions thrown*.


