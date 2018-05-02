<?php

namespace App\Tests\Factory;

use App\Factory\ResponseFactory;
use App\Tests\TestCase;
use Mockery as m;
use Symfony\Component\Routing\RouterInterface;

class ResponseFactoryTest extends TestCase
{

    public function testCreateTemplateResponse_CallsTwigRender()
    {
        $template = "template.html.twig";
        $context = ['foo' => 'bar'];
        $twig = $this->mockTwig();
        $responseFactory = new ResponseFactory($twig, $this->mockRouter());

        $responseFactory->createTemplateResponse($template, $context);

        $twig->shouldHaveReceived('render')->with($template, $context)->once();
    }

    public function testCreateTemplateResponse_ReturnsResponse()
    {
        $template = "template.html.twig";
        $context = ['foo' => 'bar'];
        $content = "Lorem Ipsum";
        $twig = $this->mockTwig($content);
        $responseFactory = new ResponseFactory($twig, $this->mockRouter());

        $response = $responseFactory->createTemplateResponse($template, $context);

        $responseContent = $response->getContent();
        $responseStatus = $response->getStatusCode();
        $this->assertEquals($content, $responseContent);
        $this->assertEquals(200, $responseStatus);
    }

    public function testCreateRedirectResponse_CallsRouter()
    {
        $routeName = "foo_bar";
        $parameters = ['id' => '1'];
        $router = $this->mockRouter();
        $responseFactory = new ResponseFactory($this->mockTwig(), $router);

        $responseFactory->createRedirectResponse($routeName, $parameters);

        $router->shouldHaveReceived('generate')->with($routeName, $parameters)->once();
    }

    public function testCreateRedirectResponse_ReturnsRedirectResponse()
    {
        $routeName = "foo_bar";
        $parameters = ['id' => '1'];
        $generatedRoute = '/foo/bar';
        $router = $this->mockRouter($generatedRoute);
        $responseFactory = new ResponseFactory($this->mockTwig(), $router);

        $response = $responseFactory->createRedirectResponse($routeName, $parameters);

        $responseRoute = $response->getTargetUrl();
        $responseStatus = $response->getStatusCode();
        $this->assertEquals($generatedRoute, $responseRoute);
        $this->assertEquals(302, $responseStatus);
    }

    /**
     * @param string $renderedContent
     * @return \Twig_Environment|m\Mock
     */
    private function mockTwig(string $renderedContent = ''): \Twig_Environment
    {
        $twig = m::mock(\Twig_Environment::class);
        $twig->shouldReceive('render')->andReturn($renderedContent);
        return $twig;
    }

    /**
     * @return RouterInterface|m\Mock
     */
    private function mockRouter($generatedRoute = '/'): RouterInterface
    {
        $router = m::mock(RouterInterface::class);
        $router->shouldReceive('generate')->andReturn($generatedRoute);
        return $router;
    }
}
