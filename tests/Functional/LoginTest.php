<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessfull(): void
    {
        $client = static::createClient();
        
        // Récupérer la route grâce à urlgenerator et le router
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

        // Formulaire

        $form = $crawler->filter("form[name=login]")->form([
            "email"=> "admin01@trt.fr",
            "password"=> "azerty"

        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur TRT Conseil');
    }

    //Mauvais mot de passe

    public function testIfLoginFailedWhenPasswordIsWrong(): void
    {
        $client = static::createClient();
        
        // Récupérer la route grâce à urlgenerator et le router
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

        // Formulaire

        $form = $crawler->filter("form[name=login]")->form([
            "email"=> "admin01@trt.fr",
            "password"=> "admin_"

        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('app_login');

        $this->assertSelectorTextContains(
            'div.alert-danger',
            'Invalid credentials.'
        );
    }
}