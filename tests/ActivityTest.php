<?php

namespace App\Tests;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ActivityTest extends WebTestCase
{
    // TEST LIST

    public function test_list()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();

    }

    public function test_list_when_logged_in()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun utilisateur trouvé pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }

    //TEST DETAIL

    public function test_detail()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun participant trouvé pour le test');

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/' . $activity->getId());

        $this->assertResponseIsSuccessful();
    }

    public function test_detail_not_found()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $invalidActivityId = 99999;

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->find($invalidActivityId);
        $this->assertNull($activity, 'L\'activité devrait être inexistante pour ce test');

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun participant trouvé pour le test');
        $client->loginUser($user);

        $client->request('GET', '/activity/' . $invalidActivityId);

        $this->assertResponseStatusCodeSame(404);
    }

    //TEST ADD

    public function test_add_need_login()
    {
        $client = static::createClient();

        $client->request('GET', '/activity/add');

        $this->assertResponseStatusCodeSame(302);

        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
    }

    public function test_add()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun utilisateur trouvé pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/add');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }

    public function test_add_form_submission()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun utilisateur trouvé pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/add');

        $form = $crawler->selectButton('Enregistrer')->form([
            'activity[name]' => 'Nouvelle Sortie Test',
            'activity[description]' => 'Description de test',
            'activity[dateStartTime]' => '2025-02-01 14:00:00',
            'activity[duration]' => 120,
            'activity[maxRegistration]' => 20,
            'activity[place]' => 1,
            'state' => 1,
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    //TEST EDIT

    public function test_edit_need_login()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->request('GET', '/activity/' . $activity->getId() . '/edit');

        $this->assertResponseStatusCodeSame(302);

        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
    }

    public function test_edit()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun utilisateur trouvé pour le test');

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/' . $activity->getId() . '/edit');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }

    public function test_edit_form_submission()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun utilisateur trouvé pour le test');

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/activity/' . $activity->getId() . '/edit');

        $form = $crawler->selectButton('Enregistrer')->form([
            'activity[name]' => 'Sortie modifiée Test',
            'activity[description]' => 'Description mise à jour',
            'activity[dateStartTime]' => '2025-02-05 18:00:00',
            'activity[duration]' => 150,
            'activity[maxRegistration]' => 25,
            'activity[place]' => 1,
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    //TEST REGISTER

    public function test_register_need_login()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->request('POST', '/activity/' . $activity->getId() . '/register');

        $this->assertResponseStatusCodeSame(302);

        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
    }

    public function test_register()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun participant trouvé pour le test');

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->loginUser($user);

        $client->request('POST', '/activity/' . $activity->getId() . '/register');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    //TEST UNREGISTER

    public function testUnregisterRedirectsGuestsToLogin()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $client->request('POST', '/activity/' . $activity->getId() . '/unregister');

        $this->assertResponseStatusCodeSame(302);

        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
    }

    public function test_unregister()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = $entityManager->getRepository(Participant::class)->findOneBy([]);
        $this->assertNotNull($user, 'Aucun participant trouvé pour le test');

        $activity = $entityManager->getRepository(\App\Entity\Activity::class)->findOneBy([]);
        $this->assertNotNull($activity, 'Aucune activité trouvée pour le test');

        $activity->addParticipant($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('POST', '/activity/' . $activity->getId() . '/unregister');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

    }
}
